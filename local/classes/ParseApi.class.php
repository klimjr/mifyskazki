<?php

/**
 * Created by PhpStorm.
 * User: klim
 * Date: 10.08.16
 * Time: 20:18
 */
class ParseApi
{

    private $curl;
    private $site = '';
    private $error;
    private $bs;
    private $be;
    private $ibp;


    /**
     * MarvelApi constructor.
     */
    public function __construct() {

        $this->curl = new Curl();
        $this->curl->params = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 5,
        );

        CModule::includeModule('iblock');
        $this->bs = new CIBlockSection;
        $this->be = new CIBlockElement;
        $this->ibp = new CIBlockProperty;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Получение очищеной страницы
     * @param string $url
     * @param bool $icon
     * @return string
     */
    private function __getPage($url = '', $icon = false) {

        if (empty($url))
            $url = $this->site;
        else
            $url = $this->site.$url;

        $HTMLCode = $this->curl->get($url);
        if ($icon)
            $HTMLCode = @iconv("windows-1251", "utf-8", $HTMLCode);
        
        $repaired = SelfFunctions::cleaning($HTMLCode);

        return $repaired;

    }

    private function __addCategory($category, $parent_id = 0) {

        $categoryRes = $this->bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 2, "UF_VENDOR_ID" => $category['href']), false, array(), false)->Fetch();
        if (isset($categoryRes["ID"]) && (int)$categoryRes["ID"] > 0) {
            $id = $categoryRes["ID"];
        } else {
            $arFields = Array(
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => $parent_id == 0 ? false : (int)$parent_id,
                "IBLOCK_ID" => 2,
                "NAME" => $category['name'],
                "CODE" => preg_replace('/\s+/', '-', SelfFunctions::Transliterate($category['name'])),
                "SORT" => 500,
                "UF_VENDOR_NAME" => "ml-russia",
                "UF_VENDOR_ID" => $category['href'],
            );

            $id = $this->bs->Add($arFields);
        }

        
        $res = ($id>0);
        if(!$res) {
            echo $this->bs->LAST_ERROR;
            echo '<pre>';
            print_r($arFields);
            echo '</pre>';
        }

        return $id;
    }


    public function parse($url) {

        $html = $this->__getPage($url);

        echo $html;
        exit;

    }


    /**
     * Добавленеи категорий
     * @return int
     */
    public function addCategoriesInit() {
        $count = 0;

        $repaired = $this->__getPage();

        $dom = new DomDocument();
        @$dom->loadHTML( $repaired );
        $xpath = new DomXPath( $dom );
        $_res = $xpath->query(".//*[@id='firstpane']/p/a");
        $categories = array();

        foreach( $_res as $obj ) {

            $categories[] = array(
                'name' => $obj->nodeValue,
                'href' => $obj->getAttribute('href'),
            );
        }

        $count = 0;
        foreach ($categories AS $key => $category) {

            $id = $this->__addCategory($category);
            $count++;

            $repaired = $this->__getPage($category['href']);
            $dom = new DomDocument();
            @$dom->loadHTML( $repaired );
            $xpath = new DomXPath( $dom );
            $_resSub = $xpath->query(".//*[@id='firstpane']/div[contains(@class,'menu_body')]/a");
            echo '<pre>';
            print_r($_resSub->length);
            echo '</pre>';
            if (isset($_resSub->length) && $_resSub->length > 0) {
                $categories[$key]['sub'] = array();
                foreach( $_resSub as $objSub ) {
                    $subcategory = array(
                        'name' => $objSub->nodeValue,
                        'href' => $objSub->getAttribute('href'),
                    );
                    $categories[$key]['sub'][] = $subcategory;

                    $sub_id = $this->__addCategory($subcategory, $id);
                    $count++;
                }
            }
        }
        return $count;
    }


    /**
     * Получение и обраюотка всех товаров на странице
     * @param DOMXPath $xpath
     * @param int $category_id
     */
    private function getPageItems(DOMXPath $xpath, $category_id = 0) {
        $_res = $xpath->query(".//*[contains(@class, 'product-list')]/li");
        foreach( $_res as $obj ) {

            $item = array();
            // Получение превью
            $_resPreview = $xpath->query(".//*[contains(@class, 'product-item_img')]//img", $obj);
            if (isset($_resPreview->length) && $_resPreview->length > 0) {
                
                foreach( $_resPreview as $objPreview ) {
                    $item['preview'] = $objPreview->getAttribute('src');
                }
            }

            // название и ссылка
            $_resName = $xpath->query(".//*[contains(@class, 'product-item_title')]", $obj);
            if (isset($_resName->length) && $_resName->length > 0) {
                foreach( $_resName as $objName ) {
                    $item['link'] = $objName->getAttribute('href');
                    $item['name'] = $objName->nodeValue;
                }
            }
            // Краткое описание
            $_resDesc = $xpath->query(".//*[contains(@class, 'product-item_info-desc')]", $obj);
            if (isset($_resDesc->length) && $_resDesc->length > 0) {
                foreach( $_resDesc as $objDesc ) {
                    $item['desc'] = $objDesc->nodeValue;
                }
            }

            // Проверяем существование товара
            $itemRes = $this->be->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 2, "CODE" => preg_replace('/(\s|[[:punct:]]+)/', '-', SelfFunctions::Transliterate($item['name']))), false, false, array())->Fetch();



            if (isset($itemRes["ID"]) && (int)$itemRes["ID"] > 0) {
                if (isset($item['preview']) && !empty($item['preview'])) {
                    $arFile = CFile::MakeFileArray($this->site.$item['preview']);
                }

                $PROP = array(
                    'VENDOR_NAME' => 'ml-russia',
                    'VENDOR_LINK' => $item['link'],
                );
                $arFields = Array(
                    "IBLOCK_SECTION_ID" => $category_id > 0 ? $category_id : false,          // элемент лежит в корне раздела
                    "IBLOCK_ID"      => 2,
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => $item['name'],
                    "CODE"           => preg_replace('/(\s|[[:punct:]]+)/', '-', SelfFunctions::Transliterate($item['name'])),
                    "ACTIVE"         => "Y",            // активен
                    "PREVIEW_PICTURE" => isset($arFile) ? $arFile : false,
                    "PREVIEW_TEXT" => $item['desc'],
                    "PREVIEW_TEXT_TYPE" => 'html',
                );

                $this->be->Update($itemRes["ID"], $arFields);
                
                echo '
                TODO: обновить товар товар - '.$itemRes["ID"].' артикул - '.$item['name'];
                flush();
            } else {

                if (isset($item['preview']) && !empty($item['preview'])) {
                    $arFile = CFile::MakeFileArray($this->site.$item['preview']);
                }


                $PROP = array(
                    'VENDOR_NAME' => 'ml-russia',
                    'VENDOR_LINK' => $item['link'],
                );
                $arFields = Array(
                    "IBLOCK_SECTION_ID" => $category_id > 0 ? $category_id : false,          // элемент лежит в корне раздела
                    "IBLOCK_ID"      => 2,
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => $item['name'],
                    "CODE"           => preg_replace('/(\s|[[:punct:]]+)/', '-', SelfFunctions::Transliterate($item['name'])),
                    "ACTIVE"         => "Y",            // активен
                    "PREVIEW_PICTURE" => isset($arFile) ? $arFile : false,
                    "PREVIEW_TEXT" => $item['desc'],
                    "PREVIEW_TEXT_TYPE" => 'html',
                );

                $id = $this->be->Add($arFields);

                $res = ($id>0);
                if(!$res) {
                    echo $this->be->LAST_ERROR;

                    echo '<pre>';
                    print_r($arFields);
                    echo '</pre>';
                    exit;

                } else {
                    echo '
                Добавлена товар - '.$id;
                    flush();
                }
            }
        }
    }

    /**
     * Добавленеи товаров
     * @return int
     */
    public function addItemsInit($link) {


        $repaired = $this->__getPage($link, false);

        echo $repaired;
        exit;

        $dom = new DomDocument();
        @$dom->loadHTML( $repaired );
        $xpath = new DomXPath( $dom );









        $count = 0;
        // Получение списка категорий
        $getCategoryRes = $this->bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 2, "UF_VENDOR_NAME" => "ml-russia", "UF_CHECK_IMPORT" => 0), false, array("ID", "NAME", "IBLOCK_SECTION_ID", "UF_*"), false);

        $i = 1;
        $categories = array();
        while($getCategory = $getCategoryRes->GetNext()) {

            $categories[] = $getCategory;


            // Получение страниц "посраничной навигации"
            $_resPagen = $xpath->query(".//div[contains(@class, 'nav__list')]/a");
            $pagen = array();
            if (isset($_resPagen->length) && $_resPagen->length > 0) {
                foreach( $_resPagen as $objPagen ) {
                    $pagen[] = $objPagen->getAttribute('href');
                }
            }
            if (empty($pagen)) {
                $this->getPageItems($xpath, $getCategory["ID"]);
            } else {
                $pagen = array_unique($pagen);
                foreach ($pagen AS $value) {
                    $repaired = $this->__getPage($getCategory["UF_VENDOR_ID"]);
                    $dom = new DomDocument();
                    @$dom->loadHTML( $repaired );
                    $xpath = new DomXPath( $dom );
                    $this->getPageItems($xpath, $getCategory["ID"]);
                    sleep(rand(1,5));
                }
            }

            $this->bs->Update($getCategory["ID"], array("UF_CHECK_IMPORT" => 1));
            $i++;
            sleep(rand(1,5));
        }
        return $count;
    }



    public function loadFeaturesItems() {

        // Получение списка товаров
        $getitemsRes = $this->be->GetList(array("ID"=>"ASC"), array("IBLOCK_ID" => 2, "PROPERTY_FEATURES_LOAD" => false), false, array("nPageSize"=>20), array("ID", "IBLOCK_ID", "NAME", "PROPERTY_VENDOR_LINK", "FEATURES_LOAD"));
        $data = array();
        while($item = $getitemsRes->GetNext()) {
            $data[] = $item;
            
            $repaired = $this->__getPage($item["PROPERTY_VENDOR_LINK_VALUE"]);

            $dom = new DomDocument();
            @$dom->loadHTML( $repaired );
            $xpath = new DomXPath( $dom );

            // Получение изображений
            $_resImages = $xpath->query(".//*[@class='sliderkit-panels']//a");
            $images = array();
            if (isset($_resImages->length) && $_resImages->length > 0) {
                foreach( $_resImages as $objImages ) {
                    $images[] = $objImages->getAttribute('href');
                }
            }

            // Получение цены
            $_resPrice = $xpath->query(".//*[@class='product__price-new']");
            $price = 0;
            if (isset($_resPrice->length) && $_resPrice->length > 0) {
                foreach( $_resPrice as $objPrice ) {
                    $price = (int)preg_replace('/\D/i', '', $objPrice->nodeValue);
                }
            }
            // Получение описания
            $_resDescr = $xpath->query(".//*[@class='product__i__desc']");
            $description = '';
            if (isset($_resDescr->length) && $_resDescr->length > 0) {
                foreach( $_resDescr as $objDescr ) {
                    $description = $objDescr->nodeValue;
                }
            }

            // Получение характеристик
            $_resFeatures = $xpath->query(".//dl[contains(@class, 'props-block')]");
            $features = array();
            if (isset($_resFeatures->length) && $_resFeatures->length > 0) {
                foreach( $_resFeatures as $objFeatures ) {
                    // Получение названия группы характеристик
                    $_resFeaturesGroupTitle = $xpath->query("./dt/label", $objFeatures);
                    $title = '';
                    if (isset($_resFeaturesGroupTitle->length) && $_resFeaturesGroupTitle->length > 0) {
                        foreach( $_resFeaturesGroupTitle as $objFeaturesGroupTitle ) {
                            $title = $objFeaturesGroupTitle->nodeValue;
                        }
                    }
                    // Получение характеристик


                    // Получение названия группы характеристик
                    $_resFeaturesGroupItems = $xpath->query(".//*[contains(@class, 'prop__list__li')]", $objFeatures);
                    $featuresGroup = array();
                    if (isset($_resFeaturesGroupItems->length) && $_resFeaturesGroupItems->length > 0) {
                        foreach( $_resFeaturesGroupItems as $objFeaturesGroupItems ) {
                            // Получение названия характеристики
                            $_resFeaturesItemTitle = $xpath->query(".//*[contains(@class, 'prop__name')]/span", $objFeaturesGroupItems);
                            $itemTitle = '';
                            if (isset($_resFeaturesItemTitle->length) && $_resFeaturesItemTitle->length > 0) {
                                foreach( $_resFeaturesItemTitle as $objFeaturesItemTitle ) {
                                    $itemTitle = $objFeaturesItemTitle->nodeValue;
                                }
                            }

                            // Получение значения характеристики
                            $_resFeaturesItemValue = $xpath->query(".//*[contains(@class, 'prop__value--m')]", $objFeaturesGroupItems);
                            $itemValue = '';
                            if (isset($_resFeaturesItemValue->length) && $_resFeaturesItemValue->length > 0) {
                                foreach( $_resFeaturesItemValue as $objFeaturesItemValue ) {
                                    $itemValue = $objFeaturesItemValue->nodeValue;
                                }
                            }

                            $featuresGroup[$itemTitle] = $itemValue;
                        }
                    }
                    $features[$title] = $featuresGroup;
                }
            }
            $iblockFetures = array();
            if (!empty($features)) {
                foreach($features AS $featuresKey => $featuresValues) {
                    $key = 'GF_'.substr(strtoupper(preg_replace('/(\s|[[:punct:]]+)/', '', SelfFunctions::Transliterate($featuresKey))),0,8);
                    if (is_array($featuresValues) && !empty($featuresValues)) {
                        foreach($featuresValues AS $f_key => $f_value) {
                            $feature_key = $key.'_'.substr(strtoupper(preg_replace('/(\s|[[:punct:]])+/', '', SelfFunctions::Transliterate($f_key))),0,38);
                            $feature_key = preg_replace('/³/', '', $feature_key);
                            // Проверяем существование свойства
                            $checkProperty = CIBlockProperty::GetByID($feature_key, 2)->GetNext();
                            /*
                            $checkProperty = CIBlockProperty::GetByID($feature_key, 2);
                            while($test = $checkProperty->GetNext()) {
                                $this->ibp->Delete($test["ID"]);
                                echo '<pre>';
                                print_r($test["ID"]);
                                echo '</pre>';
                            }
                            */
                            if (!$checkProperty) {
                                if (trim($f_value) == 'Да') {
                                    $arPropertyFields = Array(
                                        "NAME" => $f_key,
                                        "ACTIVE" => "Y",
                                        "SORT" => "500",
                                        "CODE" => $feature_key,
                                        "PROPERTY_TYPE" => "L",
                                        "IBLOCK_ID" => 2,
                                        "LIST_TYPE" => "C",
                                        "MULTIPLE" => "N",
                                        "VERSION" => 1
                                    );

                                    $arPropertyFields["VALUES"][0] = Array(
                                        "VALUE" => "Да",
                                        "DEF" => "N",
                                        "SORT" => "100"
                                    );

                                    $property_id = $this->ibp->Add($arPropertyFields);
                                    $propValueRes = CIBlockProperty::GetPropertyEnum($property_id);
                                    while($propValue = $propValueRes->GetNext()) {
                                        if ($propValue["VALUE"] == trim($f_value)) {
                                            $f_value = $propValue["ID"];
                                        }
                                    }
                                } else {
                                    $arPropertyFields = Array(
                                        "NAME" => $f_key,
                                        "ACTIVE" => "Y",
                                        "SORT" => "500",
                                        "CODE" => $feature_key,
                                        "PROPERTY_TYPE" => is_string($f_value) ? "S" : "N",
                                        "IBLOCK_ID" => 2,
                                        "WITH_DESCRIPTION" => "N",
                                    );
                                    $property_id = $this->ibp->Add($arPropertyFields);
                                }

                            } elseif($checkProperty["PROPERTY_TYPE"] == "L") {
                                $propValueRes = CIBlockProperty::GetPropertyEnum($checkProperty["ID"]);
                                while($propValue = $propValueRes->GetNext()) {
                                    if ($propValue["VALUE"] == trim($f_value)) {
                                        $f_value = $propValue["ID"];
                                    }
                                }

                            }
                            $iblockFetures[$feature_key] = trim($f_value);
                            
                        }
                    }
                }
            }

            $arFile = false;
            if (!empty($images)) {
                $arFile = CFile::MakeFileArray($this->site.$images[0]);
            }
            $arFields = array(
                "DETAIL_PICTURE" => $arFile,
                "DETAIL_TEXT" => $description,
                "DETAIL_TEXT_TYPE" => "html",
            );
            $this->be->Update($item["ID"], $arFields);
            if (count($images) > 1) {
                for($i = 1;$i<count($images);$i++) {
                    $iblockFetures["MORE_PHOTO"][] = CFile::MakeFileArray($this->site.$images[$i]);        
                }
            }

            $iblockFetures["PRICE"] = $price;
            $iblockFetures["FEATURES_LOAD"] = 1;
            $this->be->SetPropertyValuesEx($item["ID"], 2, $iblockFetures);
            sleep(rand(1, 5));
        }
        
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }


}