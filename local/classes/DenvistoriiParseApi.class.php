<?php
/**
 * Created by PhpStorm.
 * User: klim
 * Date: 31.07.2018
 * Time: 18:42
 */

class DenvistoriiParseApi extends SiteParseApi
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

        $_res = $xpath->query(".//*[contains(@class, 'region-primary-menu')]//a | .//a[contains(@class, 'daybuttons')]/a");
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
        $_resImage = $xpath->query(".//*[@id='block-days-content']//*[contains(@class, 'field--name-field-image')]//img")->item(0);
        if ($_resImage) {
            $data["PICTURE"] = $this->getSite().$_resImage->getAttribute('src');
        }

        // Получение описания
        $_resDescription = $xpath->query(".//*[@id='block-days-content']//*[contains(@class, 'taxonomy-term--type-dni')]//*[contains(@class, 'text-formatted')]")->item(0);
        $data["DESCRIPTION"] = "";
        $data["DESCRIPTION_TYPE"] = "html";

        // Поиск ссылок в тексте
        $data["DESCRIPTION"] = $this->changeLinksInText($xpath, $_resDescription);

        $data["DESCRIPTION"] = preg_replace('/class="([^"]+)"/Usi', '', $data["DESCRIPTION"]);

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

        $_res = $xpath->query(".//*[contains(@class, 'region-content')]//*[contains(@class, 'views-element-container')]");
        if (isset($_res->length) && $_res->length > 0) {
            foreach( $_res as $obj ) {

                // Получение заголовка блока
                $block_title = "";
                $_resBlockTitle = $xpath->query("./h2", $obj)->item(0);
                if ($_resBlockTitle) {
                    $block_title = trim($_resBlockTitle->nodeValue);
                }

                // Получение списка записей
                $_resItems = $xpath->query(".//*[contains(@class, 'view-content')]//*[contains(@class, 'dni')]/*[contains(@class, 'dni')]", $obj);
                if (isset($_resItems->length) && $_resItems->length > 0) {
                    foreach( $_resItems as $objItem ) {

                        $_resName = $xpath->query(".//h3", $objItem)->item(0);
                        $_resImage = $xpath->query(".//a/img", $objItem)->item(0);
                        $_resDescription = $xpath->query(".//*[contains(@class, 'views-field-body')]/*[contains(@class, 'field-content')] | .//*[contains(@class, 'field--name-body')]", $objItem)->item(0);
                        $_resLink = $xpath->query(".//*[contains(@class, 'views-field-view-node')]//a", $objItem)->item(0);

                        // Поиск ссылок в тексте
                        $preview_text = $this->changeLinksInText($xpath, $_resDescription);

                        $item = array(
                            "NAME" => trim($_resName->nodeValue),
                            "PREVIEW_PICTURE" => ($_resImage ? $_resImage->getAttribute('src') : ''),
                            "PREVIEW_TEXT" => $preview_text,
                            "PREVIEW_TEXT_TYPE" => "html",
                            "PROPS" => array(
                                "PARSE_LINK" => $_resLink->getAttribute('href'),
                                "TYPE" => $block_title,
                            ),
                        );

                        $data[] = $item;

                    }
                }

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


            if (preg_match('/(\d{1,2})(\s*)(\S+)(\s*)(\d{2,4})/i', $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_TITLE"], $matches)) {
                if (isset($matches[5])) {

                    switch ($matches[3]) {
                        case 'января': $month = 1; break;
                        case 'февраля': $month = 2; break;
                        case 'марта': $month = 3; break;
                        case 'апреля': $month = 4; break;
                        case 'мая': $month = 5; break;
                        case 'июня': $month = 6; break;
                        case 'июля': $month = 7; break;
                        case 'августа': $month = 8; break;
                        case 'сентября': $month = 9; break;
                        case 'октября': $month = 10; break;
                        case 'ноября': $month = 11; break;
                        case 'декабря': $month = 12; break;
                        default: $month = 1;
                    }

                    $data["PROPS"]["EVENT_DATE"] = $matches[1].'.'.$month.'.'.$matches[5];

                }
            }

        }

        $_resMetaDescr = $xpath->query("/html/head/meta[@name='description']")->item(0);
        if ($_resMetaDescr) {
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_META_DESCRIPTION"] = $_resMetaDescr->getAttribute('content');
        }

        $_resPageTitle = $xpath->query(".//*[contains(@class, 'post__header')]//*[contains(@class, 'post__header-title')]//h1")->item(0);
        if ($_resPageTitle) {
            $data["IPROPERTY_TEMPLATES"]["ELEMENT_PAGE_TITLE"] = trim($_resPageTitle->nodeValue);
        }

        $_resImg = $xpath->query(".//*[@id='block-days-content']//*[contains(@class, 'field--name-field-image')]//a")->item(0);
        if ($_resImg) {
            $data["DETAIL_PICTURE"] = $this->getSite().$_resImg->getAttribute("href");
        }


        $_resContent = $xpath->query(".//*[@id='block-days-content']//*[contains(@class, 'field--name-body')]")->item(0);
        if ($_resContent) {

            // Поиск ссылок в тексте
            $data["DETAIL_TEXT"] = $this->changeLinksInText($xpath, $_resContent);
            $data["DETAIL_TEXT_TYPE"] = "html";

        }

        return $data;
    }


    /**
     * Поиск ссылок в тексте
     * @param $xpath
     * @param $_resDescription
     * @return mixed|string
     */
    public function changeLinksInText(DOMXPath &$xpath, &$_resDescription) {

        // Поиск ссылок в тексте
        $_resTextLinks = $xpath->query(".//a", $_resDescription);
        $text_links = array();
        if (isset($_resTextLinks->length) && $_resTextLinks->length > 0) {
            foreach ($_resTextLinks as $objTextLinks) {
                $current_pure_link = $objTextLinks->getAttribute('href');
                $current_link = str_replace($this->getSite(), '', $objTextLinks->getAttribute('href'));
                // Ищем совпадения
                $sectionRes = $this->bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => $this->getIblock(), "UF_PARSE_LINK" => $current_link), false, array("ID", "NAME", "SECTION_PAGE_URL"), false)->GetNext();
                if ($sectionRes["ID"]) {
                    $text_links["OLD_LINK"][] = $current_pure_link;
                    $text_links["NEW_LINK"][] = $sectionRes["SECTION_PAGE_URL"];
                } else {
                    $text_links["OLD_LINK"][] = $current_pure_link;
                    $text_links["NEW_LINK"][] = "#";
                }
            }
        }

        $preview_text = "";
        if ($_resDescription) {
            $preview_text = $_resDescription->ownerDocument->saveHTML($_resDescription);
            $preview_text = str_replace($text_links["OLD_LINK"], $text_links["NEW_LINK"], $preview_text);

        }

        return $preview_text;

    }


}


