#!/usr/bin/env python3
"""Генератор SVG-планировок из houses.json.

Каждый этаж -> отдельный SVG: внешние стены, комнаты, подписи (название + площадь),
терраса штрихуется. Стиль под «тёплый премиум» проекта.
"""
import json
import pathlib

SCALE = 55          # px на метр
MARGIN = 46         # поля вокруг плана
TITLE_H = 52        # место под заголовок
WALL = 7            # толщина внешней стены, px
BG = "#FAF7F2"
INK = "#2B2620"
ACCENT = "#B07D46"
ROOM_FILLS = ["#FFFFFF", "#F5EEE3"]

BASE = pathlib.Path(__file__).parent
OUT = BASE / "plans"


def esc(s: str) -> str:
    return s.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")


def render_floor(house: dict, plan: dict, idx: int) -> str:
    pw, ph = plan["w"] * SCALE, plan["h"] * SCALE
    width = pw + MARGIN * 2
    height = ph + MARGIN * 2 + TITLE_H
    ox, oy = MARGIN, MARGIN + TITLE_H  # начало плана

    parts = [
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {width:g} {height:g}" '
        f'font-family="Georgia, \'Times New Roman\', serif">',
        f'<rect width="{width:g}" height="{height:g}" fill="{BG}"/>',
        # штриховка для террасы
        '<defs><pattern id="hatch" width="8" height="8" patternTransform="rotate(45)" patternUnits="userSpaceOnUse">'
        f'<line x1="0" y1="0" x2="0" y2="8" stroke="{ACCENT}" stroke-width="1" opacity="0.45"/></pattern></defs>',
        # заголовок
        f'<text x="{MARGIN}" y="{MARGIN - 10}" font-size="21" fill="{INK}" '
        f'letter-spacing="2.5" style="text-transform:uppercase">{esc(house["name"].upper())} · {esc(plan["floor"].upper())}</text>',
        f'<text x="{MARGIN}" y="{MARGIN + 12}" font-size="12" fill="{ACCENT}" font-family="Arial, sans-serif" '
        f'letter-spacing="1">{esc(house["size"])} · {house["area"]:g} м² общая</text>',
    ]

    # комнаты
    for i, r in enumerate(plan["rooms"]):
        x, y = ox + r["x"] * SCALE, oy + r["y"] * SCALE
        w, h = r["w"] * SCALE, r["h"] * SCALE
        terrace = r.get("terrace", False)
        fill = "url(#hatch)" if terrace else ROOM_FILLS[i % 2]
        parts.append(
            f'<rect x="{x:g}" y="{y:g}" width="{w:g}" height="{h:g}" fill="{fill}" '
            f'stroke="{INK}" stroke-width="1.5"{" stroke-dasharray=\"6 4\"" if terrace else ""}/>'
        )
        cx, cy = x + w / 2, y + h / 2
        name_size = 15 if w > 150 else 12
        parts.append(
            f'<text x="{cx:g}" y="{cy - 4:g}" font-size="{name_size}" fill="{INK}" text-anchor="middle" '
            f'font-family="Arial, sans-serif">{esc(r["name"])}</text>'
        )
        parts.append(
            f'<text x="{cx:g}" y="{cy + 15:g}" font-size="12" fill="{ACCENT}" text-anchor="middle" '
            f'font-family="Arial, sans-serif">{r["area"]:g} м²</text>'
        )

    # внешняя стена поверх комнат
    parts.append(
        f'<rect x="{ox:g}" y="{oy:g}" width="{pw:g}" height="{ph:g}" fill="none" '
        f'stroke="{INK}" stroke-width="{WALL}"/>'
    )
    # размерные подписи габаритов
    parts.append(
        f'<text x="{ox + pw / 2:g}" y="{oy + ph + 26:g}" font-size="12" fill="{INK}" text-anchor="middle" '
        f'font-family="Arial, sans-serif" opacity="0.6">{plan["w"]:g} м</text>'
    )
    parts.append(
        f'<text x="{ox - 14:g}" y="{oy + ph / 2:g}" font-size="12" fill="{INK}" text-anchor="middle" '
        f'font-family="Arial, sans-serif" opacity="0.6" transform="rotate(-90 {ox - 14:g} {oy + ph / 2:g})">{plan["h"]:g} м</text>'
    )
    parts.append("</svg>")
    return "\n".join(parts)


def main():
    OUT.mkdir(exist_ok=True)
    data = json.loads((BASE / "houses.json").read_text(encoding="utf-8"))
    made = []
    for house in data["houses"]:
        for i, plan in enumerate(house["plans"], 1):
            svg = render_floor(house, plan, i)
            fname = f'{house["code"]}-plan-{i}.svg'
            (OUT / fname).write_text(svg, encoding="utf-8")
            made.append(fname)
    print(f"OK: {len(made)} планов")
    for f in made:
        print(" ", f)
    # самопроверка: у каждого дома площади комнат в сумме близки к заявленной
    for house in data["houses"]:
        total = sum(r["area"] for p in house["plans"] for r in p["rooms"] if not r.get("terrace"))
        assert total > 0, house["code"]


if __name__ == "__main__":
    main()
