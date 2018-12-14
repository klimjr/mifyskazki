<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$curPage = $APPLICATION->GetCurPage(true);
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <title><?$APPLICATION->ShowTitle()?><? $APPLICATION->ShowProperty("PAGINATION_TITLE"); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/favicon.png?v1.0" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png?v1.0" type="image/x-icon">

    <link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-180x180.png">

    <?


    use Bitrix\Main\Page\Asset;

    //    Для подключения css
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/styles.min.css");

    //  Для подключения скриптов
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.fancybox.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.fancybox.pack.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.jscrollpane.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.mousewheel.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.bxslider.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/functions.min.js");

    // Подключение мета тегов или сторонних файлов
    //    Asset::getInstance()->addString("<link rel='shortcut icon' href='/local/images/favicon.ico' />");

    ?>


    <!--  BASE STYLES  -->
    <? /* SelfFunctions::insertStyles($_SERVER["DOCUMENT_ROOT"].'local/templates/dist/css/preload_styles_base.min.css'); ?>

    <? if ($curPage == SITE_DIR."index.php"): ?>
    <!--  MAIN PAGE STYLES  -->
    <? SelfFunctions::insertStyles($_SERVER["DOCUMENT_ROOT"].'local/templates/dist/css/preload_styles_main_page.min.css'); ?>
    <? endif; ?>

    <!--  PAGE STYLES  -->
    <? $APPLICATION->ShowProperty("PAGE_PRELOAD_STYLES"); */ ?>

    <? global $USER;
    if($USER->IsAdmin()){
        // Вывод стандартной шапки
        $APPLICATION->ShowHead();
    } else
    {
        // Вывод кастомной шапки
        SelfFunctions::ShowHeadCustom(true);
    } ?>

    <link rel="alternate" hreflang="x-default" href="<?=$_SERVER["HTTP_X_FORWARDED_PROTOCOL"];?>://<?=$_SERVER["HTTP_HOST"];?><?=$_SERVER["REQUEST_URI"];?>" />

</head>
<body>
    <div id="panel"><?$APPLICATION->ShowPanel();?></div>

    <div class="container">
        <div class="header-line">
            <div class="row">
                <div class="col-xs-12 col-sm-3">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"));?>
                </div>
                <div class="col-xs-12 col-sm-9">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        ".default",
                        Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "top",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(""),
                            "MENU_CACHE_TIME" => "36000",
                            "CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "N",
                            "ROOT_MENU_TYPE" => "top",
                            "USE_EXT" => "Y"
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <section class="content-block">
