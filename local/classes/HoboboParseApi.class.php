<?php
/**
 * Created by PhpStorm.
 * User: klim
 * Date: 31.07.2018
 * Time: 18:42
 */

class HoboboParseApi extends SiteParseApi
{

    public function __construct($site, $iblock_id) {
        parent::__construct();
        $this->setSite($site);
        $this->setIblock($iblock_id);
    }

    /**
     * Получние списка категорий из XPath
     * @param DOMXPath $xpath
     * @return array|mixed
     */
    public function getSectionsListDataFromPage(DOMXPath $xpath)
    {
        $data = array();

        $_res = $xpath->query(".//*[contains(@class, 'category-filter__item category-filter__item--level-2')]//a | .//a[contains(@class, 'category-filter__item-title')]");
        if (isset($_res->length) && $_res->length > 0) {
            foreach ($_res as $obj) {
                $section = array(
                    "NAME" => trim($obj->nodeValue),
                    "UF" => array(
                        "PARSE_LINK" => $obj->getAttribute('href'),
                    ),
                );

                $data[] = $section;
            }
        }


        return $data;
    }

    /**
     * Получние данных о категории из XPath
     * @param DOMXPath $xpath
     * @return array|mixed
     */
    public function getSectionDataFromPage(DOMXPath $xpath)
    {
        $data = array();

        // Получение изображения
        $_resImage = $xpath->query(".//*[contains(@class, 'post__header')]//*[contains(@class, 'post__header-thumb')]/img")->item(0);
        if ($_resImage) {
            $data["PICTURE"] = $_resImage->getAttribute('src');
        }

        // Получение описания
        $_res = $xpath->query(".//*[contains(@class, 'page-description')]/p|div | .//*[contains(@class, 'post__content')]/p|div");
        $data["DESCRIPTION"] = "";
        $data["DESCRIPTION_TYPE"] = "html";
        if (isset($_res->length) && $_res->length > 0) {
            foreach ($_res as $obj) {
                $data["DESCRIPTION"] .= '<p>'.trim($obj->nodeValue).'</p>';  
            }
        }

        return $data;
    }


    /**
     * Получние списка  записей из XPath
     * @param DOMXPath $xpath
     * @return array|mixed
     */
    public function getListDataFromPage(DOMXPath $xpath)
    {
        $data = array();

        $_res = $xpath->query(".//*[contains(@class, 'js-datatable')]//tbody/tr");
        if (isset($_res->length) && $_res->length > 0) {
            foreach( $_res as $obj ) {

                $_resName = $xpath->query(".//td/a", $obj)->item(0);
                $_resAlbum = $xpath->query("./td[2]", $obj)->item(0);
                $_resRating = $xpath->query("./td[3]", $obj)->item(0);

                if (!$_resRating && (int)trim($_resAlbum->nodeValue) > 0) {
                    $_resRating = $_resAlbum;
                    unset($_resAlbum);
                }

                $item = array(
                    "NAME" => trim($_resName->nodeValue),
                    "PROPS" => array(
                        "PARSE_LINK" => $_resName->getAttribute('href'),
                        "ALBUM" => isset($_resAlbum) ? trim($_resAlbum->nodeValue) : "",
                        "RATING" => isset($_resRating) ? trim($_resRating->nodeValue) : "",
                    ),
                );

                $data[] = $item;
            }
        }

        $_res = $xpath->query(".//*[contains(@class, 'post__content')]//ul/li/a");
        if (isset($_res->length) && $_res->length > 0) {
            foreach( $_res as $obj ) {

                $item = array(
                    "NAME" => trim($obj->nodeValue),
                    "PROPS" => array(
                        "PARSE_LINK" => $obj->getAttribute('href'),
                    ),
                );

                $data[] = $item;
            }
        }



        return $data;
    }

    /**
     * Получение данных из Xpath
     * @param DOMXPath $xpath
     * @return array|mixed
     */
    public function getDataFromPage(DOMXPath $xpath)
    {
        $data = array();
        $data["IPROPERTY_TEMPLATES"] = array();

        // Получение title
        $_resTitle = $xpath->query("/html/head/title")->item(0);
        if ($_resTitle) {
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_TITLE"] = trim($_resTitle->nodeValue);
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_TITLE"] = str_replace('Хобобо', '', $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_TITLE"]);
        }

        $_resMetaDescr = $xpath->query("/html/head/meta[@name='description']")->item(0);
        if ($_resMetaDescr) {
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_DESCRIPTION"] = $_resMetaDescr->getAttribute('content');
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_DESCRIPTION"] = str_replace('Хобобо', '', $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_DESCRIPTION"]);
        }

        $_resPageTitle = $xpath->query(".//*[contains(@class, 'post__header')]//*[contains(@class, 'post__header-title')]//h1")->item(0);
        if ($_resPageTitle) {
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_PAGE_TITLE"] = trim($_resPageTitle->nodeValue);
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_PAGE_TITLE"] = str_replace('Хобобо', '', $data["IPROPERTY_TEMPLATES"]["ELEMENT_PAGE_TITLE"]);
        }

        $_resImg = $xpath->query(".//*[contains(@class, 'post__header')]//*[contains(@class, 'post__header-thumb')]//img")->item(0);
        if ($_resImg)
            $data["DETAIL_PICTURE"] = $_resImg->getAttribute("src");

        $_resContent = $xpath->query(".//*[contains(@class, 'post__content')]")->item(0);
        if ($_resContent) {
            $data["DETAIL_TEXT"] = $_resContent->ownerDocument->saveHTML($_resContent);
            $data["DETAIL_TEXT_TYPE"] = "html";

        }

        return $data;
    }




}


