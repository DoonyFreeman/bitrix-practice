<?php
/**
 * Миграция: тип инфоблока «Каталог», инфоблок «Проекты домов» (houses),
 * свойства, разделы, SEO-шаблоны и наполнение из data/houses.json.
 *
 * Запуск (из папки bitrixdock):
 *   docker compose exec -T -u www-data php php /var/www/bitrix/local/php_interface/migrations/001_create_houses.php
 *
 * Скрипт идемпотентен: существующие сущности пропускает.
 * FORCE=1 в окружении — пересоздать элементы (например, после смены фото).
 */

set_time_limit(0);
error_reporting(E_ERROR | E_PARSE);

$_SERVER['DOCUMENT_ROOT'] = '/var/www/bitrix';
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_NO_ACCELERATOR_RESET', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (!CModule::IncludeModule('iblock')) {
    fwrite(STDERR, "Модуль iblock недоступен\n");
    exit(1);
}

$dataDir = __DIR__ . '/data';
$data = json_decode(file_get_contents($dataDir . '/houses.json'), true);
if (!$data) {
    fwrite(STDERR, "Не прочитан houses.json\n");
    exit(1);
}

// ---------- 1. Тип инфоблока (шкаф) ----------
$typeId = 'catalog';
if (!CIBlockType::GetByID($typeId)->Fetch()) {
    $rs = (new CIBlockType())->Add([
        'ID' => $typeId,
        'SECTIONS' => 'Y',
        'IN_RSS' => 'N',
        'SORT' => 100,
        'LANG' => [
            'ru' => ['NAME' => 'Каталог', 'SECTION_NAME' => 'Разделы', 'ELEMENT_NAME' => 'Проекты'],
            'en' => ['NAME' => 'Catalog', 'SECTION_NAME' => 'Sections', 'ELEMENT_NAME' => 'Projects'],
        ],
    ]);
    echo $rs ? "[+] Тип инфоблока catalog\n" : '[!] Тип: ' . (new CIBlockType())->LAST_ERROR . "\n";
} else {
    echo "[=] Тип инфоблока catalog уже есть\n";
}

// ---------- 2. Инфоблок (ящик) ----------
$iblock = CIBlock::GetList([], ['TYPE' => $typeId, 'CODE' => 'houses', 'CHECK_PERMISSIONS' => 'N'])->Fetch();
if ($iblock) {
    $iblockId = (int)$iblock['ID'];
    echo "[=] Инфоблок houses уже есть (ID={$iblockId})\n";
} else {
    $ib = new CIBlock();
    $iblockId = $ib->Add([
        'ACTIVE' => 'Y',
        'NAME' => 'Проекты домов',
        'CODE' => 'houses',
        'IBLOCK_TYPE_ID' => $typeId,
        'SITE_ID' => ['s1'],
        'SORT' => 100,
        'LIST_PAGE_URL' => '/catalog/',
        'SECTION_PAGE_URL' => '/catalog/#SECTION_CODE#/',
        'DETAIL_PAGE_URL' => '/catalog/#SECTION_CODE#/#ELEMENT_CODE#/',
        'INDEX_ELEMENT' => 'Y',
        'INDEX_SECTION' => 'Y',
        'VERSION' => 1,
        'GROUP_ID' => ['2' => 'R'], // «все посетители» — чтение
        'FIELDS' => [
            'CODE' => [
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => [
                    'UNIQUE' => 'Y', 'TRANSLITERATION' => 'Y', 'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L', 'TRANS_SPACE' => '-', 'TRANS_OTHER' => '-', 'TRANS_EAT' => 'Y',
                ],
            ],
        ],
    ]);
    if (!$iblockId) {
        fwrite(STDERR, '[!] Инфоблок: ' . $ib->LAST_ERROR . "\n");
        exit(1);
    }
    echo "[+] Инфоблок houses (ID={$iblockId})\n";
}

// ---------- 3. Свойства (графы карточки) ----------
$props = [
    ['CODE' => 'PRICE',      'NAME' => 'Цена, ₽',            'TYPE' => 'N', 'SMART' => 'Y', 'DISPLAY' => 'U', 'SORT' => 100],
    ['CODE' => 'AREA',       'NAME' => 'Площадь, м²',        'TYPE' => 'N', 'SMART' => 'Y', 'DISPLAY' => 'U', 'SORT' => 200],
    ['CODE' => 'FLOORS',     'NAME' => 'Этажность',          'TYPE' => 'L', 'SMART' => 'Y', 'DISPLAY' => 'F', 'SORT' => 300,
        'VALUES' => ['1 этаж', 'С мансардой', '2 этажа']],
    ['CODE' => 'BEDROOMS',   'NAME' => 'Спален',             'TYPE' => 'N', 'SMART' => 'Y', 'DISPLAY' => 'F', 'SORT' => 400],
    ['CODE' => 'MATERIAL',   'NAME' => 'Материал',           'TYPE' => 'L', 'SMART' => 'Y', 'DISPLAY' => 'F', 'SORT' => 500,
        'VALUES' => ['Профилированный брус', 'Клеёный брус']],
    ['CODE' => 'SIZE',       'NAME' => 'Габариты',           'TYPE' => 'S', 'SMART' => 'N', 'SORT' => 600],
    ['CODE' => 'BUILD_TIME', 'NAME' => 'Срок строительства', 'TYPE' => 'S', 'SMART' => 'N', 'SORT' => 700],
    ['CODE' => 'GALLERY',    'NAME' => 'Фотогалерея',        'TYPE' => 'F', 'SMART' => 'N', 'SORT' => 800,
        'MULTIPLE' => 'Y', 'FILE_TYPE' => 'jpg, jpeg, png, webp'],
    ['CODE' => 'PLANS',      'NAME' => 'Планировки',         'TYPE' => 'F', 'SMART' => 'N', 'SORT' => 900,
        'MULTIPLE' => 'Y', 'FILE_TYPE' => 'svg, png, jpg'],
    ['CODE' => 'EQUIPMENT',  'NAME' => 'Комплектация',       'TYPE' => 'S', 'SMART' => 'N', 'SORT' => 1000,
        'USER_TYPE' => 'HTML'],
];

foreach ($props as $p) {
    $exists = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $p['CODE']])->Fetch();
    if ($exists) {
        echo "[=] Свойство {$p['CODE']} уже есть\n";
        continue;
    }
    $fields = [
        'IBLOCK_ID' => $iblockId,
        'NAME' => $p['NAME'],
        'CODE' => $p['CODE'],
        'PROPERTY_TYPE' => $p['TYPE'],
        'SORT' => $p['SORT'],
        'MULTIPLE' => $p['MULTIPLE'] ?? 'N',
        'ACTIVE' => 'Y',
    ];
    if (!empty($p['USER_TYPE'])) $fields['USER_TYPE'] = $p['USER_TYPE'];
    if (!empty($p['FILE_TYPE'])) $fields['FILE_TYPE'] = $p['FILE_TYPE'];
    // фичи свойств: без LIST_PAGE_SHOW современный catalog.section не выведет свойство
    $fields['FEATURES'] = [
        ['MODULE_ID' => 'iblock', 'FEATURE_ID' => 'DETAIL_PAGE_SHOW', 'IS_ENABLED' => 'Y'],
        ['MODULE_ID' => 'iblock', 'FEATURE_ID' => 'LIST_PAGE_SHOW',
            'IS_ENABLED' => in_array($p['CODE'], ['PRICE', 'AREA', 'BEDROOMS', 'FLOORS', 'SIZE'], true) ? 'Y' : 'N'],
    ];
    if ($p['SMART'] === 'Y') {
        $fields['SMART_FILTER'] = 'Y';
        $fields['SECTION_PROPERTY'] = 'Y';
        $fields['DISPLAY_TYPE'] = $p['DISPLAY'];
    }
    if (!empty($p['VALUES'])) {
        $fields['VALUES'] = array_map(
            fn($v, $i) => ['VALUE' => $v, 'SORT' => ($i + 1) * 100, 'DEF' => 'N'],
            $p['VALUES'], array_keys($p['VALUES'])
        );
    }
    $propId = (new CIBlockProperty())->Add($fields);
    echo $propId ? "[+] Свойство {$p['CODE']}\n" : "[!] Свойство {$p['CODE']}: ошибка\n";
}

// карта значений списков: 'Клеёный брус' => ID варианта
$enumMap = [];
$rsEnum = CIBlockPropertyEnum::GetList([], ['IBLOCK_ID' => $iblockId]);
while ($e = $rsEnum->Fetch()) {
    $enumMap[$e['PROPERTY_CODE']][$e['VALUE']] = $e['ID'];
}

// ---------- 4. Разделы (папки) ----------
$sectionMap = [];
foreach ($data['sections'] as $s) {
    $exists = CIBlockSection::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $s['code'], 'CHECK_PERMISSIONS' => 'N'])->Fetch();
    if ($exists) {
        $sectionMap[$s['code']] = (int)$exists['ID'];
        echo "[=] Раздел {$s['code']} уже есть\n";
        continue;
    }
    $sectId = (new CIBlockSection())->Add([
        'IBLOCK_ID' => $iblockId,
        'NAME' => $s['name'],
        'CODE' => $s['code'],
        'SORT' => $s['sort'],
        'ACTIVE' => 'Y',
    ]);
    $sectionMap[$s['code']] = (int)$sectId;
    echo "[+] Раздел {$s['name']}\n";
}

// ---------- 5. SEO-шаблоны ----------
$seo = new \Bitrix\Iblock\InheritedProperty\IblockTemplates($iblockId);
$seo->set([
    'ELEMENT_META_TITLE' => '{=this.Name} — проект дома из бруса {=property.AREA} м², цена {=property.PRICE} ₽ | БрусВуд',
    'ELEMENT_META_DESCRIPTION' => 'Проект дома «{=this.Name}»: {=property.AREA} м², спален: {=property.BEDROOMS}, срок строительства {=property.BUILD_TIME}. Строим дома из бруса под ключ в СНТ.',
    'SECTION_META_TITLE' => '{=this.Name} дома из бруса — проекты и цены | БрусВуд',
    'SECTION_META_DESCRIPTION' => '{=this.Name} дома из бруса под ключ: проекты с планировками, фото и ценами.',
]);
echo "[+] SEO-шаблоны инфоблока\n";

// ---------- 6. Элементы (карточки домов) ----------
$el = new CIBlockElement();
$force = getenv('FORCE') === '1';
foreach ($data['houses'] as $h) {
    $exists = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $h['code'], 'CHECK_PERMISSIONS' => 'N'], false, false, ['ID'])->Fetch();
    if ($exists && !$force) {
        echo "[=] {$h['name']} уже есть\n";
        continue;
    }
    if ($exists && $force) {
        CIBlockElement::Delete($exists['ID']);
        echo "[-] {$h['name']} удалён (FORCE)\n";
    }

    $gallery = [];
    foreach ($h['gallery'] as $i => $photo) {
        $gallery['n' . $i] = CFile::MakeFileArray($dataDir . '/photos/' . $photo);
    }

    $plans = [];
    foreach ($h['plans'] as $i => $plan) {
        $file = CFile::MakeFileArray($dataDir . '/plans/' . $h['code'] . '-plan-' . ($i + 1) . '.svg');
        $file['description'] = $plan['floor'];
        $plans['n' . $i] = $file;
    }

    $id = $el->Add([
        'IBLOCK_ID' => $iblockId,
        'IBLOCK_SECTION_ID' => $sectionMap[$h['section']],
        'NAME' => $h['name'],
        'CODE' => $h['code'],
        'ACTIVE' => 'Y',
        'PREVIEW_TEXT' => $h['preview_text'],
        'PREVIEW_TEXT_TYPE' => 'text',
        'DETAIL_TEXT' => $h['detail_text'],
        'DETAIL_TEXT_TYPE' => 'html',
        'PREVIEW_PICTURE' => CFile::MakeFileArray($dataDir . '/photos/' . $h['preview_photo']),
        'DETAIL_PICTURE' => CFile::MakeFileArray($dataDir . '/photos/' . $h['preview_photo']),
        'PROPERTY_VALUES' => [
            'PRICE' => $h['price'],
            'AREA' => $h['area'],
            'FLOORS' => $enumMap['FLOORS'][$h['floors']] ?? null,
            'BEDROOMS' => $h['bedrooms'],
            'MATERIAL' => $enumMap['MATERIAL'][$h['material']] ?? null,
            'SIZE' => $h['size'],
            'BUILD_TIME' => $h['build_time'],
            'GALLERY' => $gallery,
            'PLANS' => $plans,
            'EQUIPMENT' => ['VALUE' => ['TYPE' => 'HTML', 'TEXT' => $h['equipment']]],
        ],
    ]);
    echo $id ? "[+] {$h['name']} (ID={$id})\n" : "[!] {$h['name']}: {$el->LAST_ERROR}\n";
}

echo "Готово.\n";
