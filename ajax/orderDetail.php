<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$ID = (int)$_REQUEST["ID"];

if ($ID > 0) {
    $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.order.detail",
        "clear",
        Array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CUSTOM_SELECT_PROPS" => array(""),
            "ID" => $ID,
            "PATH_TO_CANCEL" => "",
            "PATH_TO_COPY" => "",
            "PATH_TO_LIST" => "",
            "PATH_TO_PAYMENT" => "payment.php",
            "PICTURE_HEIGHT" => "110",
            "PICTURE_RESAMPLE_TYPE" => "1",
            "PICTURE_WIDTH" => "110",
            "PROP_1" => array(""),
            "PROP_2" => array(""),
            "SET_TITLE" => "N"
        )
    );
}
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>