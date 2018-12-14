<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::includeModule('iblock');
global $USER;
$itemId = (int)$_POST["itemID"];
$userId = $USER->GetID();

if ($userId && $itemId) {
    $result = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 31, "MODIFIED_USER_ID" => $userId), false, false, array("ID", "IBLOCK_ID", "NAME"));
    while($list = $result->GetNext()) {


        
        $userList = array();
        $propResult = CIBlockElement::GetProperty($list["IBLOCK_ID"], $list["ID"], array(), array("CODE" => "ITEMS"));
        while($prop = $propResult->GetNext()) {
            if (!empty($prop["VALUE"]))
                $userList[] = $prop["VALUE"];
        }

        if(($key = array_search($itemId, $userList)) !== FALSE){
            unset($userList[$key]);
            $userList = array_values($userList);
        }

        CIBlockElement::SetPropertyValuesEx(
            $list["ID"],
            $list["IBLOCK_ID"],
            array("ITEMS" => $userList),
            array()
        );
    }

    $data = SelfFunctions::getFavoriteItems($userId);

    $_SESSION["FAVORITE_USER_LIST"] = $data["FAVORITE_ITEMS"];
    $_SESSION["FAVORITE_LISTS"] = $data["FAVORITE_LISTS"];

    echo json_encode($data);
}
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>