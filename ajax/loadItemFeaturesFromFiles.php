<?
set_time_limit (1000);
ini_set('memory_limit', '256M');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$filepath = $_SERVER["DOCUMENT_ROOT"].'/upload/card_features_import_sync.txt';
$tmp = file_get_contents($filepath);
$data = json_decode($tmp);

$features = SelfFunctions::buildFeaturesGroups($data);

echo json_encode($features);
exit;


require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>