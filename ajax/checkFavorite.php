<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

global $USER;

$userId = $USER->GetID();
if ($userId > 0) {
    $rsUser = $USER->GetByID($userId);
    $arUser = $rsUser->Fetch();
    if (isset($arUser["UF_LINKED_ITEMS"]) && is_array($arUser["UF_LINKED_ITEMS"]))
        $selectedItems = $arUser["UF_LINKED_ITEMS"];
    else
        $selectedItems = array();

    echo json_encode(array("userAuth" => true, "items" => $selectedItems));
    exit;

} else {

    echo json_encode(array("userAuth" => false));
    exit;

}

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>