<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/personal/companies/#',
    'RULE' => '',
    'ID' => 'bitrix:iblock.element.add',
    'PATH' => '/personal/companies/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/personal/order/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/order/index.php',
    'SORT' => 100,
  ),
  26 => 
  array (
    'CONDITION' => '#^/oborydovanie/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/oborydovanie/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.section',
    'PATH' => '/personal/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/vendors/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/vendors/index.php',
    'SORT' => 100,
  ),
  29 => 
  array (
    'CONDITION' => '#^/actions/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/actions/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/store/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store',
    'PATH' => '/store/index.php',
    'SORT' => 100,
  ),
  30 => 
  array (
    'CONDITION' => '#^/promo/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/promo/index.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/club/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork',
    'PATH' => '/club/index.php',
    'SORT' => 100,
  ),
  23 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  27 => 
  array (
    'CONDITION' => '#^/info/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/index.php',
    'SORT' => 100,
  ),
  28 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  31 => 
  array (
    'CONDITION' => '#^/mify/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/mify/index.php',
    'SORT' => 100,
  ),
);
