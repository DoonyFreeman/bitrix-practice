<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

if (empty($arResult)) {
    return '';
}

// хлебные крошки выводятся отложенно, поэтому шаблон ВОЗВРАЩАЕТ строку
$html = '<nav class="crumbs" aria-label="Вы здесь"><ol class="crumbs__list">';
$last = count($arResult) - 1;

foreach ($arResult as $i => $item) {
    $title = htmlspecialcharsex($item['TITLE']);
    if ($i < $last && $item['LINK'] !== '') {
        $html .= '<li><a href="' . $item['LINK'] . '">' . $title . '</a></li>';
    } else {
        $html .= '<li aria-current="page">' . $title . '</li>';
    }
}

$html .= '</ol></nav>';

return $html;
