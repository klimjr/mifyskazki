<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::includeModule('iblock');
global $USER;

$type = $_REQUEST["type"];
$cartInfo = $_REQUEST["cartInfo"];

if (CModule::IncludeModule("catalog") && CModule::IncludeModule("sale")) {

    if ($type == "replace") {
        $fuserID = CSaleBasket::GetBasketUserID();
        CSaleBasket::DeleteAll($fuserID, False);
    }

    if (is_array($cartInfo) && !empty($cartInfo)) {
        foreach ($cartInfo AS $itemID => $count) {
            Add2BasketByProductID($itemID, $count);
        }
    }
}

$APPLICATION->IncludeComponent(
    "bitrix:sale.basket.basket.line",
    "ajax",
    array(
        "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
        "PATH_TO_PERSONAL" => SITE_DIR."personal/",
        "SHOW_PERSONAL_LINK" => "N",
        "SHOW_NUM_PRODUCTS" => "Y",
        "SHOW_TOTAL_PRICE" => "Y",
        "SHOW_PRODUCTS" => "Y",
        "POSITION_FIXED" =>"N",
        "SHOW_AUTHOR" => "N",
        "PATH_TO_REGISTER" => SITE_DIR."login/",
        "PATH_TO_PROFILE" => SITE_DIR."personal/",
    ),
    false,
    array()
);



require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>