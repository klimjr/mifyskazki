<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$data = array(
    "COUNT" => count($_SESSION["FAVORITE_USER_LIST"]),
    "COUNT_TITLE" => SelfFunctions::getNumberWord(count($_SESSION["FAVORITE_USER_LIST"]), array("Товар", "Товара", "Товаров")),
    "FAVORITE_ITEMS" => $_SESSION["FAVORITE_USER_LIST"],
    "FAVORITE_LISTS" => $_SESSION["FAVORITE_LISTS"]
);

echo json_encode($data);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>