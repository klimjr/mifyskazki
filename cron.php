<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__));
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$data = new HoboboParseApi('http://www.hobobo.ru', 37);
$data->loadItemsInfo();
?>