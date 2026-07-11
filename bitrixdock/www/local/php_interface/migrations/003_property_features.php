<?php
/**
 * Миграция: «фичи свойств» инфоблока houses.
 * Современный catalog.section игнорирует параметр PROPERTY_CODE, если включены
 * PropertyFeature: он выводит только свойства с флагами «показывать в списке /
 * на детальной». Проставляем флаги.
 *
 * Запуск (из папки bitrixdock):
 *   docker compose exec -T -u www-data php php /var/www/bitrix/local/php_interface/migrations/003_property_features.php
 */

$_SERVER['DOCUMENT_ROOT'] = '/var/www/bitrix';
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_NO_ACCELERATOR_RESET', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Iblock\Model\PropertyFeature;

if (!CModule::IncludeModule('iblock')) {
    fwrite(STDERR, "Модуль iblock недоступен\n");
    exit(1);
}

$iblock = CIBlock::GetList([], ['TYPE' => 'catalog', 'CODE' => 'houses', 'CHECK_PERMISSIONS' => 'N'])->Fetch();
if (!$iblock) {
    fwrite(STDERR, "Инфоблок houses не найден\n");
    exit(1);
}
$iblockId = (int)$iblock['ID'];

$listPageCodes = ['PRICE', 'AREA', 'BEDROOMS', 'FLOORS', 'SIZE'];

$rs = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId]);
while ($prop = $rs->Fetch()) {
    $features = [
        ['MODULE_ID' => 'iblock', 'FEATURE_ID' => 'DETAIL_PAGE_SHOW', 'IS_ENABLED' => 'Y'],
        [
            'MODULE_ID' => 'iblock',
            'FEATURE_ID' => 'LIST_PAGE_SHOW',
            'IS_ENABLED' => in_array($prop['CODE'], $listPageCodes, true) ? 'Y' : 'N',
        ],
    ];
    $result = PropertyFeature::setFeatures((int)$prop['ID'], $features);
    echo ($result->isSuccess() ? '[+] ' : '[!] ') . $prop['CODE']
        . (in_array($prop['CODE'], $listPageCodes, true) ? ' (список+детальная)' : ' (детальная)') . "\n";
}

BXClearCache(true);
$GLOBALS['CACHE_MANAGER']->CleanAll();
echo "Готово, кеш сброшен.\n";
