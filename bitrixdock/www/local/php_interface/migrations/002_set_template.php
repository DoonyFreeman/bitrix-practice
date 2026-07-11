<?php
/**
 * Миграция: привязка шаблона bruswood к сайту s1 + имя сайта.
 *
 * Запуск (из папки bitrixdock):
 *   docker compose exec -T -u www-data php php /var/www/bitrix/local/php_interface/migrations/002_set_template.php
 */

$_SERVER['DOCUMENT_ROOT'] = '/var/www/bitrix';
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_NO_ACCELERATOR_RESET', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$site = new CSite();
$ok = $site->Update('s1', [
    'SITE_NAME' => 'БрусВуд',
    'TEMPLATE' => [
        ['CONDITION' => '', 'SORT' => 150, 'TEMPLATE' => 'bruswood'],
    ],
]);

if (!$ok) {
    fwrite(STDERR, '[!] ' . $site->LAST_ERROR . "\n");
    exit(1);
}

COption::SetOptionString('main', 'site_name', 'БрусВуд');

// сбрасываем кеш, чтобы шаблон применился сразу
BXClearCache(true);
$GLOBALS['CACHE_MANAGER']->CleanAll();
$GLOBALS['stackCacheManager']->CleanAll();

echo "[+] Шаблон bruswood привязан к сайту s1, кеш сброшен\n";
