<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::includeModule('iblock');
global $USER;

$be = new CIBlockElement;

$listId = (int)$_POST["listID"];
$itemId = (int)$_POST["itemID"];
$userId = $USER->GetID();
$userList = array();
$addList = true;

if ($userId && $itemId) {

    // Проверка списка товаров пользователя или создание базового списка
    if ($listId) {
        $list = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 31, "ID" => $listId, "MODIFIED_USER_ID" => $userId), false, false, array("ID", "NAME", "IBLOCK_ID"))->GetNext();
        if ($list["ID"]) {
            $addList = false;
        }
    } else {
        // получение базового списка
        $list = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 31, "CODE" => "base", "MODIFIED_USER_ID" => $userId), false, false, array("ID", "NAME", "IBLOCK_ID"))->GetNext();
        if ($list["ID"]) {
            $addList = false;
        }
    }

    if ($addList) {
        $arFields = Array(
            "IBLOCK_ID"      => 31,
            "NAME"           => "Основной список товаров пользователя",
            "CODE"           => "base",
            "ACTIVE"         => "Y",
        );

        $id = $be->Add($arFields);

        $list["ID"] = $id;
        $list["IBLOCK_ID"] = 31;
    } else {

        $propResult = CIBlockElement::GetProperty($list["IBLOCK_ID"], $list["ID"], array(), array("CODE" => "ITEMS"));
        while($prop = $propResult->GetNext()) {
            if (!empty($prop["VALUE"]))
                $userList[] = $prop["VALUE"];
        }

    }

    $userList[] = $itemId;
    $userList = array_unique($userList);

    CIBlockElement::SetPropertyValuesEx(
        $list["ID"],
        $list["IBLOCK_ID"],
        array("ITEMS" => $userList),
        array()
    );

    $data = SelfFunctions::getFavoriteItems($userId);

    $_SESSION["FAVORITE_USER_LIST"] = $data["FAVORITE_ITEMS"];
    $_SESSION["FAVORITE_LISTS"] = $data["FAVORITE_LISTS"];

    echo json_encode($data);

}

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>