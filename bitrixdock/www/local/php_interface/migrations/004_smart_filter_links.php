<?php
/**
 * Миграция: привязки свойств к умному фильтру.
 * CIBlockProperty::Add игнорирует ключи SMART_FILTER/SECTION_PROPERTY —
 * их пишет только админ-форма. Создаём записи b_iblock_section_property
 * через ORM (SECTION_ID=0 — привязка уровня инфоблока).
 *
 * Запуск (из папки bitrixdock):
 *   docker compose exec -T -u www-data php php /var/www/bitrix/local/php_interface/migrations/004_smart_filter_links.php
 */

$_SERVER['DOCUMENT_ROOT'] = '/var/www/bitrix';
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_NO_ACCELERATOR_RESET', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Iblock\SectionPropertyTable;

if (!CModule::IncludeModule('iblock')) {
    fwrite(STDERR, "Модуль iblock недоступен\n");
    exit(1);
}

$iblock = CIBlock::GetList([], ['TYPE' => 'catalog', 'CODE' => 'houses', 'CHECK_PERMISSIONS' => 'N'])->Fetch();
$iblockId = (int)$iblock['ID'];

// без флага SECTION_PROPERTY=Y на инфоблоке GetArray игнорирует все привязки
if ($iblock['SECTION_PROPERTY'] !== 'Y') {
    (new CIBlock())->Update($iblockId, ['SECTION_PROPERTY' => 'Y']);
    echo "[+] Инфоблоку включён SECTION_PROPERTY\n";
}

// DISPLAY_TYPE: U — ползунок-диапазон для чисел, F — чекбоксы
$smart = ['PRICE' => 'U', 'AREA' => 'U', 'BEDROOMS' => 'F', 'FLOORS' => 'F', 'MATERIAL' => 'F'];

$rs = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId]);
while ($prop = $rs->Fetch()) {
    if (!isset($smart[$prop['CODE']])) {
        continue;
    }
    $exists = SectionPropertyTable::getList([
        'filter' => ['=IBLOCK_ID' => $iblockId, '=SECTION_ID' => 0, '=PROPERTY_ID' => $prop['ID']],
    ])->fetch();
    if ($exists) {
        echo "[=] {$prop['CODE']} уже привязано\n";
        continue;
    }
    $result = SectionPropertyTable::add([
        'IBLOCK_ID' => $iblockId,
        'SECTION_ID' => 0,
        'PROPERTY_ID' => (int)$prop['ID'],
        'SMART_FILTER' => 'Y',
        'DISPLAY_TYPE' => $smart[$prop['CODE']],
        'DISPLAY_EXPANDED' => 'Y',
    ]);
    echo ($result->isSuccess() ? '[+] ' : '[!] ') . $prop['CODE'] . ' → фильтр (' . $smart[$prop['CODE']] . ")\n";
}

BXClearCache(true);
$GLOBALS['CACHE_MANAGER']->CleanAll();
echo "Готово, кеш сброшен.\n";
