<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::includeModule('iblock');
global $USER;

$itemId = (int)$_GET["id"];
$userId = $USER->GetID();

// получение всех организаций пользователя
$res = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 9, "PROPERTY_USER" => $userId, "!ID" => $itemId), false, false, array("ID"));

// Удаляем "главность" у остальных организаций
while ($item = $res->GetNext()) {
    CIBlockElement::SetPropertyValuesEx($item["ID"], 9, array("MAIN_COMPANY" => false), array());
}

CIBlockElement::SetPropertyValuesEx($itemId, 9, array("MAIN_COMPANY" => 33), array());

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>