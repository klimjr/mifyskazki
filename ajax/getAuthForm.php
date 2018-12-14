<? include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::includeModule('iblock');

if ($USER->IsAuthorized()) {
    $data = array(
        "AUTH" => true,
        "ERRORS" => false,
    );
    echo json_encode($data);
    exit;
} ?>

<?$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form",
    "pure",
    Array(
        "FORGOT_PASSWORD_URL" => "/forgot/",
        "PROFILE_URL" => "/personal/",
        "REGISTER_URL" => "/reg/",
        "SHOW_ERRORS" => "Y",
    )
);?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); ?>