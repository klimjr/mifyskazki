<?
set_time_limit (1000);
ini_set('memory_limit', '256M');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$filepath = $_SERVER["DOCUMENT_ROOT"].'/upload/analogs_import.txt';
$tmp = file_get_contents($filepath);
$data = json_decode($tmp);

$analogs = SelfFunctions::importItemAnalogs($data);

echo json_encode($analogs);
exit;


require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>