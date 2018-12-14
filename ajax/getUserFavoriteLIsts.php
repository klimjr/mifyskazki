<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::includeModule('iblock');
global $USER;
$lists = array();
$data = array();
if($USER->IsAuthorized()):

    $userId = $USER->GetID();
    $result = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 31, "!CODE" => "base", "MODIFIED_USER_ID" => $userId), false, false, array("ID", "NAME"));

    while($item = $result->GetNext()) {
        $lists[] = $item;
    }

    $data["USER"] = $userId;
    $data["LISTS"] = $lists;

else:

    $data["USER"] = false;

endif;
echo json_encode($data);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>