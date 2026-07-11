<?php
$arUrlRewrite=array (
  // ЧПУ каталога: /catalog/{раздел}/ и /catalog/{раздел}/{дом}/
  100 =>
  array (
    'CONDITION' => '#^/catalog/([\\w-]+)/(?:([\\w-]+)/)?$#',
    'RULE' => 'SECTION_CODE=$1&ELEMENT_CODE=$2',
    'ID' => NULL,
    'PATH' => '/catalog/index.php',
    'SORT' => 90,
  ),
  0 =>
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
);
