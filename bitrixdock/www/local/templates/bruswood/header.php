<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

/** Путь к ассету с версией по filemtime — сбрасывает браузерный кеш при изменении файла. */
function bwAsset(string $path): string
{
    $file = $_SERVER['DOCUMENT_ROOT'] . $path;
    return $path . '?v=' . (is_file($file) ? filemtime($file) : 1);
}

$asset = Asset::getInstance();
$asset->addCss(bwAsset(SITE_TEMPLATE_PATH . '/assets/css/tokens.css'));
$asset->addCss(bwAsset(SITE_TEMPLATE_PATH . '/assets/css/main.css'));
$asset->addCss(bwAsset(SITE_TEMPLATE_PATH . '/assets/css/home.css'));
$asset->addCss(bwAsset(SITE_TEMPLATE_PATH . '/assets/css/catalog.css'));

// на главной шапка лежит поверх hero и прозрачна до скролла
$isHome = $APPLICATION->GetCurPage(true) === '/index.php';
$asset->addString('<link rel="preload" href="' . SITE_TEMPLATE_PATH . '/assets/fonts/prata-v22-cyrillic_latin-regular.woff2" as="font" type="font/woff2" crossorigin>');
$asset->addString('<link rel="preload" href="' . SITE_TEMPLATE_PATH . '/assets/fonts/manrope-v20-cyrillic_latin-regular.woff2" as="font" type="font/woff2" crossorigin>');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $APPLICATION->ShowHead(); ?>
    <title><?php $APPLICATION->ShowTitle(); ?></title>
</head>
<body<?= $isHome ? ' class="page-home"' : '' ?>>
<?php $APPLICATION->ShowPanel(); ?>
<div class="top-sentinel" id="top-sentinel" aria-hidden="true"></div>

<header class="header<?= $isHome ? ' header--overlay' : '' ?>" id="header">
    <div class="container header__in">
        <a href="/" class="logo" aria-label="БрусВуд — на главную">Брус<span>Вуд</span></a>

        <nav class="nav" id="nav" aria-label="Основное меню">
            <?php $APPLICATION->IncludeComponent('bitrix:menu', 'top', [
                'ROOT_MENU_TYPE' => 'top',
                'MAX_LEVEL' => 1,
                'MENU_CACHE_TYPE' => 'A',
                'MENU_CACHE_TIME' => '3600',
                'MENU_CACHE_USE_GROUPS' => 'N',
                'USE_EXT' => 'N',
                'DELAY' => 'N',
                'ALLOW_MULTI_SELECT' => 'N',
            ]); ?>
        </nav>

        <div class="header__side">
            <?php $APPLICATION->IncludeComponent('bitrix:main.include', '', [
                'AREA_FILE_SHOW' => 'file',
                'PATH' => SITE_TEMPLATE_PATH . '/include/phone.php',
                'EDIT_TEMPLATE' => '',
            ]); ?>
            <button class="burger" id="burger" type="button" aria-label="Меню" aria-expanded="false"><span></span></button>
        </div>
    </div>
</header>

<main class="main">
