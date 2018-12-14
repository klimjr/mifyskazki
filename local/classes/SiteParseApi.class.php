<?php
/**
 * Created by PhpStorm.
 * User: klim
 * Date: 31.07.2018
 * Time: 18:42
 */

set_time_limit (1000);
ini_set('memory_limit', '256M');
ini_set('default_socket_timeout', '300');

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

abstract class SiteParseApi
{
    public $site;
    public $bs;
    public $be;
    public $ibp;
    public $curl;
    public $error;
    public $iblock;
    public $hlblock_id = 1;
    public $logHLID;
    public $count = 0;

    public function __construct() {

        $this->curl = new Curl();
        $this->curl->params = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 5,
        );

        CModule::includeModule('iblock');
        CModule::IncludeModule('highloadblock');
        $this->bs = new CIBlockSection;
        $this->be = new CIBlockElement;
        $this->ibp = new CIBlockProperty;
    }

    /**
     * @param $what_to_clean
     * @param string $tidy_config
     * @return string
     */
    public function __cleaning($what_to_clean, $tidy_config='' ) {

        $config = array(
            'show-body-only' => false,
            'clean' => true,
            'char-encoding' => 'utf8',
            'add-xml-decl' => true,
            'add-xml-space' => true,
            'output-html' => false,
            'output-xml' => false,
            'output-xhtml' => true,
            'numeric-entities' => false,
            'ascii-chars' => false,
            'doctype' => 'strict',
            'bare' => true,
            'fix-uri' => true,
            'indent' => true,
            'indent-spaces' => 4,
            'tab-size' => 4,
            'wrap-attributes' => true,
            'wrap' => 0,
            'indent-attributes' => true,
            'join-classes' => false,
            'join-styles' => false,
            'enclose-block-text' => true,
            'fix-bad-comments' => true,
            'fix-backslash' => true,
            'replace-color' => false,
            'wrap-asp' => false,
            'wrap-jste' => false,
            'wrap-php' => false,
            'write-back' => true,
            'drop-proprietary-attributes' => false,
            'hide-comments' => false,
            'hide-endtags' => false,
            'literal-attributes' => false,
            'drop-empty-paras' => true,
            'enclose-text' => true,
            'quote-ampersand' => true,
            'quote-marks' => false,
            'quote-nbsp' => true,
            'vertical-space' => true,
            'wrap-script-literals' => false,
            'tidy-mark' => true,
            'merge-divs' => false,
            'repeated-attributes' => 'keep-last',
            'break-before-br' => true,
        );

        if( $tidy_config == '' ) {
            $tidy_config = &$config;
        }

        $tidy = new tidy();
        $out = $tidy->repairString($what_to_clean, $tidy_config, 'UTF8');
        unset($tidy);
        unset($tidy_config);
        return($out);
    }

    /**
     * Получение очищеной страницы
     * @param string $url
     * @param bool $icon
     * @param bool $show_data
     * @return mixed|string
     */
    public function __getPage($url = '', $icon = false, $show_data = false) {

        if (empty($url))
            $url = $this->getSite();
        else {
            if (!strstr($url, $this->getSite())) {
                $url = $this->getSite().$url;
            }
        }
        
        $HTMLCode = $this->curl->get($url);

        if ($show_data)
            return $HTMLCode;


        if ($icon)
            $HTMLCode = @iconv("windows-1251", "utf-8", $HTMLCode);

        $repaired = SelfFunctions::cleaning($HTMLCode);

        return $repaired;

    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $iblock
     */
    public function setIblock($iblock)
    {
        $this->iblock = $iblock;
    }

    /**
     * @return mixed
     */
    public function getIblock()
    {
        return $this->iblock;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Ивеличение счетчика на 1
     */
    public function incrementCount()
    {
        $this->count++;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
        $this->log('Ошибка при импорте', 'false', $error);
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Увеличение счетчика
     * @param $counter_id
     * @param $count
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function incCounter($counter_id, $count) {
        // Айди нашего хайлоад блока
        $hlblock_id = 11;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('ID' => $counter_id)
        ))->Fetch();


        $entity_data_class::update($counter_id, array(
            "UF_COUNT" => $rsData["UF_COUNT"] + $count,
        ));
    }

    /**
     * Установка счетчика
     * @param $counter_id
     * @param $code
     * @param $value
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function setCounter($counter_id, $code, $value) {
        // Айди нашего хайлоад блока
        $hlblock_id = 11;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $entity_data_class::update($counter_id, array($code => $value));
    }

    /**
     * Получение данных счетчика
     * @param $counter_id
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getCounter($counter_id) {
        // Айди нашего хайлоад блока
        $hlblock_id = 11;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('ID' => $counter_id)
        ))->Fetch();

        return $rsData;
    }

    /**
     * Логирование операции
     * @param $distributor
     * @param $text
     */
    public function log2File($distributor, $text) {
        $logFile = $_SERVER['DOCUMENT_ROOT'].'/'.$distributor.'.log';
        if (is_writeable($logFile)) {
            if (($fp = fopen($logFile, "a+")) !== false) {
                fwrite($fp, "
".$text);
                fclose($fp);
            }
        }
    }

    /**
     * Логирование операции
     * @param $title
     * @param $status
     * @param $description
     */
    public function log($title, $status, $description) {

        $hlblock = HL\HighloadBlockTable::getById($this->hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $entity_data_class::add(array(
            "UF_PARSING_SITE" => $this->getSite(),
            "UF_LOG_TITLE" => $title,
            "UF_LOG_DATE" => date("d.m.Y H:i:s"),
            "UF_LOG_STATUS" => $status,
            "UF_LOG_DESCRIPTION" => $description,
        ));
    }

    /**
     * Получение и Добавление категорий по ссылке
     * @param $link
     * @param int $parent_category
     * @param bool $check_subsections
     * @return array
     */
    public function getListSections($link, $parent_category = 0, $check_subsections = false) {

        $repaired = $this->__getPage($link, false);
        
//            echo $repaired;
//            exit;
        
        $dom = new DomDocument();
        @$dom->loadHTML( $repaired );
        $xpath = new DomXPath( $dom );

        $data = $this->getSectionsListDataFromPage($xpath);

        $sections_ids = array();
        $this->setCount(1);
        if (is_array($data) && !empty($data)) {
            foreach ($data AS $section) {
                $section_id = $this->addlistSections($section, $parent_category);
                $sections_ids[] = $section_id;

                // пытаемся получить дочерние категории
                if ($check_subsections && $section_id && isset($section["UF"]["PARSE_LINK"]) && !empty($section["UF"]["PARSE_LINK"])) {

                    $subsection_ids = $this->getListSections($section["UF"]["PARSE_LINK"], $section_id, true);
                    $sections_ids = array_merge($sections_ids, $subsection_ids);
                    
                }
            }
        }

        return $sections_ids;

    }

    /**
     * Добавление категории
     * @param $data
     * @param int $parent_category
     * @return bool
     */
    public function addlistSections($data, $parent_category = 0) {
        if (!$this->getIblock()) {
            $this->setError("IBLOCK ID false");
            return false;
        }

        if (!isset($data["NAME"]) || empty($data["NAME"])) {
            $this->setError("NAME is empty");
            return false;
        }

        $code = strtolower(preg_replace('/(\s|[[:punct:]]+)/', '-', SelfFunctions::Transliterate($data["NAME"])));

        // Проверяем существование категории
        $sectionRes = CIBlockSection::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => $this->getIblock(), "CODE" => $code), false, array("ID", "NAME"), false)->Fetch();

        $UFS = array();
        if (isset($data["UF"]) && !empty($data["UF"])) {
            $UFS = $this->getSectionUFProperty($data["UF"]);
        }

        if (isset($sectionRes["ID"]) && (int)$sectionRes["ID"] > 0) {
            $id = $sectionRes["ID"];
            $arFields = Array(
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => $parent_category == 0 ? false : (int)$parent_category,
                "NAME" => $data["NAME"],
                "SORT" => $this->getCount()*10,
            );

            if ($UFS) {
                $arFields = array_merge($arFields, $UFS);
            }

            $this->bs->Update($id, $arFields);

        } else {
            // Добавление категории
            $arFields = Array(
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => $parent_category == 0 ? false : (int)$parent_category,
                "IBLOCK_ID" => $this->getIblock(),
                "NAME" => $data["NAME"],
                "CODE" => $code,
                "SORT" => $this->getCount()*10,
            );

            if ($UFS) {
                $arFields = array_merge($arFields, $UFS);
            }

            $id = $this->bs->Add($arFields);
        }

        $this->incrementCount();

        return $id;
    }

    /**
     * Получение и добавление информации о категории
     * Загрузка списка элементов
     */
    public function addItemsListAndSectionData() {
        
        $sectionRes = CIBlockSection::GetList(array("DEPTH_LEVEL"=>"DESC"), array("IBLOCK_ID" => $this->getIblock(), "UF_LOAD_ELEMENTS" => false), false, array("ID", "IBLOCK_ID", "NAME", "UF_PARSE_LINK"), array("nTopCount" => 20));
        while($section = $sectionRes->GetNext()) {

            // Загрузка списка элементов
            $this->getSectionList($section["UF_PARSE_LINK"], $section["ID"]);

            $repaired = $this->__getPage($section["UF_PARSE_LINK"], false);

//            echo $repaired;
//            exit;

            $dom = new DomDocument();
            @$dom->loadHTML( $repaired );
            $xpath = new DomXPath( $dom );

            // Получение данных из XPATH
            $data = $this->getSectionDataFromPage($xpath);

            if (isset($data["PICTURE"]) && !empty($data["PICTURE"])) {
                $data["PICTURE"] = CFile::MakeFileArray($data["PICTURE"]);
            }

            $UFS = array();
            if (isset($data["UF"]) && !empty($data["UF"])) {
                $UFS = $this->getSectionUFProperty($data["UF"]);
                unset($data["UF"]);
            }

            // Отметка о загрузке данных
            $UFS["UF_LOAD_ELEMENTS"] = "Y";

            $arFields = array_merge($data, $UFS);

            $this->bs->Update($section["ID"], $arFields);

        }

    }

    /**
     * Получение и добавление списка записей для категории, по ссылке
     * @param $link
     * @param $category_id
     * @return array
     */
    public function getSectionList($link, $category_id = 0) {

        $repaired = $this->__getPage($link, false);

//        echo $repaired;
//        exit;

        $dom = new DomDocument();
        @$dom->loadHTML( $repaired );
        $xpath = new DomXPath( $dom );

        // Получение данных из xpath
        $data = $this->getListDataFromPage($xpath);

        $items_ids = array();
        if (is_array($data) && !empty($data)) {
            foreach ($data AS $item) {
                // Добавление записи в базу
                $item_id = $this->addlistItem($item, $category_id);
                $items_ids[] = $item_id;
            }
        }

        return $items_ids;
    }

    /**
     * Добавление записи в базу
     * @param $data
     * @param $category_id
     * @return bool
     */
    public function addlistItem($data, $category_id = 0) {

        if (!$this->getIblock()) {
            $this->setError("IBLOCK ID false");
            return false;
        }

        if (!isset($data["NAME"]) || empty($data["NAME"])) {
            $this->setError("NAME is empty");
            return false;
        }

        $code = strtolower(preg_replace('/(\s|[[:punct:]])+/', '-', SelfFunctions::Transliterate($data["NAME"])));

        // Проверяем существование записи
        $itemRes = $this->be->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => $this->getIblock(), "CODE" => $code), false, false, array())->Fetch();

        if (isset($itemRes["ID"]) && (int)$itemRes["ID"] > 0) {


            // Добавление записей к нескольким категориям
            if ($category_id != $itemRes["IBLOCK_SECTION_ID"]) {

                // Получение всех категорий к которвм принадлежит элемент
                $sectionsRes = CIBlockElement::GetElementGroups($itemRes["ID"], false, array("ID", "NAME"));
                $categories = array();
                while($elem_section = $sectionsRes->GetNext()) {
                    $categories[] = $elem_section["ID"];
                }

                if (!in_array($category_id, $categories)) {
                    $categories[] = $category_id;

                    $arFields = array(
                        "IBLOCK_SECTION" => $categories
                    );
                    $this->be->Update($itemRes["ID"], $arFields);
                }
            }

            return $itemRes["ID"];

        } else {

            $PROP = array();
            if (isset($data["PROPS"]) && !empty($data["PROPS"])) {
                $PROP = $this->getProperty($data["PROPS"]);
            }
            
            $arFields = Array(
                "IBLOCK_SECTION_ID" => $category_id > 0 ? $category_id : false,          // элемент лежит в корне раздела
                "IBLOCK_ID"      => $this->getIblock(),
                "PROPERTY_VALUES"=> $PROP,
                "NAME"           => $data["NAME"],
                "CODE"           => $code,
                "ACTIVE"         => "Y",            // активен
            );

            $id = $this->be->Add($arFields);

            $res = ($id>0);
            if(!$res) {
                $this->setError($this->be->LAST_ERROR);
                return false;

            } else {
                return $id;
            }
        }
    }

    /**
     * Загрузка подробной информации о записи
     * @return bool
     */
    public function loadItemsInfo() {

        // Получение списка товаров
        $getitemsRes = $this->be->GetList(array("ID"=>"ASC"), array("IBLOCK_ID" => $this->getIblock(), "PROPERTY_LOAD_INFO" => false), false, array("nPageSize"=>10), array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_PARSE_LINK", "PROPERTY_LOAD_INFO"));
        $data = array();
        while($item = $getitemsRes->GetNext()) {

            $repaired = $this->__getPage($item["PROPERTY_PARSE_LINK_VALUE"], false);

//            echo $repaired;
//            exit;

            $dom = new DomDocument();
            @$dom->loadHTML( $repaired );
            $xpath = new DomXPath( $dom );

            // Получение данных из Xpath
            $data = $this->getDataFromPage($xpath);

            if (empty($data)) {
                $this->setError('Недостаточно данных для обновления записи');
                return false;
            }

            // Получение изображения
            if (isset($data["DETAIL_PICTURE"])) {
                if (!strstr($data["DETAIL_PICTURE"], $this->getSite())) {
                    $data["DETAIL_PICTURE"] = $this->getSite().$data["IMAGE"];
                }

                $data["DETAIL_PICTURE"] = CFile::MakeFileArray($data["DETAIL_PICTURE"]);

                if (empty($item["PREVIEW_PICTURE"])) {
                    $data["PREVIEW_PICTURE"] = $data["DETAIL_PICTURE"];
                }
            }

            // Обновление элемента
            $this->be->UPDATE($item["ID"], $data);

            $PROPS = array();
            if (isset($data["PROPS"]) && !empty($data["PROPS"])) {
                $PROPS = $this->getProperty($data["PROPS"]);
            }

            $PROPS["LOAD_INFO"] = "Y";
            $this->be->SetPropertyValuesEx(
                $item["ID"],
                $item["IBLOCK_ID"],
                $PROPS,
                array()
            );

        }
        
    }

    /**
     * Добавление пользовательских свойств для категорий
     * @param $properties
     * @return array|bool
     */
    public function getSectionUFProperty($properties) {

        if (!$this->getIblock()) {
            $this->setError("IBLOCK ID false");
            return false;
        }

        $UFS = array();
        foreach($properties AS $UF_key => $UF_value) {

            // Проверяем существование пользовательского свойства
            $checkProperty = CUserTypeEntity::GetList(array("SORT" => "ASC"), array("ENTITY_ID" => "IBLOCK_".$this->getIblock()."_SECTION", "XML_ID" => $UF_key))->Fetch();
            
            if (!$checkProperty) {

                /**
                 * Добавление пользовательского свойства
                 */
                $oUserTypeEntity    = new CUserTypeEntity();
                $aUserFields    = array(
                    "ENTITY_ID"         => "IBLOCK_".$this->getIblock()."_SECTION",
                    "FIELD_NAME"        => "UF_".$UF_key,
                    "USER_TYPE_ID"      => "string",
                    "XML_ID"            => $UF_key,
                    "SORT"              => 500,
                    "MULTIPLE"          => "N",
                    "MANDATORY"         => "N",
                    "SHOW_FILTER"       => "N",
                    "SHOW_IN_LIST"      => "",
                    "EDIT_IN_LIST"      => "",
                    "IS_SEARCHABLE"     => "N",
                    'SETTINGS'          => array(
                        'DEFAULT_VALUE' => '',
                        'SIZE'          => '20',
                        'ROWS'          => '1',
                        'MIN_LENGTH'    => '0',
                        'MAX_LENGTH'    => '0',
                        'REGEXP'        => '',
                    )
                );

                $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields ); // int

                if ((int)$iUserFieldId > 0) {
                    $UFS["UF_".$UF_key] = trim($UF_value);
                }

            } else {
                $UFS["UF_".$UF_key] = trim($UF_value);
            }

        }

        return $UFS;
    }

    /**
     * Добавление характеристик товара
     * @param $articul
     * @param $currentItem
     * @return array
     */
    public function getProperty($properties) {

        if (!$this->getIblock()) {
            $this->setError("IBLOCK ID false");
            return false;
        }

        $PROP = array();
        foreach($properties AS $feature_key => $f_value) {

            // Проверяем существование свойства
            $checkProperty = CIBlockProperty::GetByID($feature_key, $this->getIblock())->GetNext();
            if (!$checkProperty) {
                if (trim($f_value) == 'Да' || trim($f_value) == 'Нет') {
                    $arPropertyFields = Array(
                        "NAME" => $feature_key,
                        "ACTIVE" => "Y",
                        "SORT" => "500",
                        "CODE" => $feature_key,
                        "PROPERTY_TYPE" => "L",
                        "IBLOCK_ID" => $this->getIblock(),
                        "LIST_TYPE" => "C",
                        "MULTIPLE" => "N",
                        "VERSION" => 1
                    );

                    $arPropertyFields["VALUES"][0] = Array(
                        "VALUE" => $f_value,
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
                        "NAME" => $feature_key,
                        "ACTIVE" => "Y",
                        "SORT" => "500",
                        "CODE" => $feature_key,
                        "PROPERTY_TYPE" => is_string($f_value) ? "S" : "N",
                        "IBLOCK_ID" => $this->getIblock(),
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

            $PROP[$feature_key] = trim($f_value);
        }

        return $PROP;
    }

    /**
     * Загрузка характеристик товаров
     * @param int $limit
     * @return array
     */
    public function getItemsProperties($limit = 0) {

        // Получение списка товаров, у которых время последнего импорта либо не определено, либо меньше заданного
        $arFilter = array(
            "IBLOCK_ID" => $this->iblock,
            array(
                "LOGIC" => "OR",
                "PROPERTY_LOAD_FEATURES_DATE" => false,
                "<PROPERTY_LOAD_FEATURES_DATE" => date("d.m.Y H:i:s", time() - 864000) // TODO: изменить количество секунд на 86400
            )
        );

        if ($limit > 0)
            $nPageSize = Array("nPageSize"=>$limit);
        else
            $nPageSize = false;

        $getItemsRes = $this->be->GetList(array("SORT"=>"ASC"), $arFilter, false, $nPageSize, array("ID", "IBLOCK_ID", "NAME", "PROPERTY_DISTRIBUTOR_ARTICLE"));
        $items = SelfFunctions::resultToArray($getItemsRes, true);


        foreach ($items AS $arItem) {
            $this->getProperty($arItem["PROPERTY_DISTRIBUTOR_ARTICLE_VALUE"], $arItem["ID"]);
        }

        return $items;

    }





    /**
     * Получение массива со списком Категорий из XPath
     * @param DOMXPath $xpath
     * @return mixed
     */
    abstract public function getSectionsListDataFromPage(DOMXPath $xpath);

    /**
     * Получение данных о категории из XPath
     * @param DOMXPath $xpath
     * @return mixed
     */
    abstract public function getSectionDataFromPage(DOMXPath $xpath);

    /**
     * Получение массива со списком записей из XPath
     * @param DOMXPath $xpath
     * @return mixed
     */
    abstract public function getListDataFromPage(DOMXPath $xpath);

    /**
     * Получение массива данных из XPath
     * @param DOMXPath $xpath
     * @return mixed
     */
    abstract public function getDataFromPage(DOMXPath $xpath);

}