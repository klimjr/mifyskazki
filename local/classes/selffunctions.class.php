<?php

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

/**
 * Created by PhpStorm.
 * User: klim
 * Date: 29.07.15
 * Time: 6:06
 */
class SelfFunctions
{

    public static $nav_string = '';
    public static $image_width = 300;
    public static $image_height = 220;
    public static $matrix_iblock = 24;
    public static $vendors_iblock = 6; 
    public static $catalog_iblock = 2;
    public static $sku_iblock = 3;
    public static $productProperties_HLblock = 8;
    public static $documents_iblock = 14;
    public static $catalog_features_groups_iblock = 25;
    public static $iblock_properties = array();


    /**
     * Конструктор класса
     */
    public function __construct() {}

    /**
     * Разбиение числа на разряды
     * @param $num
     * @return string
     */
    public static function getNumByRate($num, $sep = ';psbn&', $returnValue = false) {

        $num_temp = preg_split('/,|\./', $num);
        $number = $num_temp[0];

        $num_minus = '';
        if ($number < 0) {
            $num_minus = '-';
            $number = abs($number);
        }


        $n = strrev($number);
        $l = strlen($n);
        $tmp = '';
        for($i=0;$i<$l;$i++) {
            $tmp .= $n[$i].(($i+1)%3==0 && ($i+1) != $l ? $sep : '');
        }
        $number = strrev($tmp);
        unset($tmp);
        unset($n);

        if ($returnValue) {
            return $num_minus.(string)$number.(isset($num_temp[1]) ? '.'.$num_temp[1] : '');
        } else {
            return $num_minus.(string)$number.(isset($num_temp[1]) ? '.'.$num_temp[1] : '');
        }

    }

    /**
     * Добавление знкаов после запятой
     * @param $num
     * @return string
     */
    public static function getFloat($num, $mantissaCount = 2, $returnValue = false) {

        $num_temp = preg_split('/,|\./', $num);

        
        $number = $num_temp[0];
        $mantissa = '';
        if (isset($num_temp[1])) {
            $mantissa = (string)$num_temp[1];
            $n = strlen($mantissa);
            if ($n < $mantissaCount) {
                while($n < $mantissaCount) {
                    $mantissa .= '0';
                    $n++;
                }

            }
        }

        if ($returnValue) {
            return (string)$number.'.'.$mantissa;
        } else {
            echo (string)$number.'.'.$mantissa;
        }

    }

    /**
     * Получение слова зависимого от числительного
     * @param int $num
     * @param array $word
     * @return mixed
     */
    public static function getNumberWord($num, $word) {


        $l = strlen($num);

        // Если в числительном 1 разряд
        if ($l == 1) {
            if ($num == 1)
                return $word[0];
            elseif ($num == 2 or $num == 3 or $num == 4)
                return $word[1];
            else
                return $word[2];
        }
        // Если в числительном больше одного разряда
        else {
            // Получеам последнюю цифру
            $tmp = substr($num, -1, 1);
            // Получеам предпоследнюю цифру
            $tmp1 = substr($num, -2, 1);

            if ($tmp1 == 1)
                return $word[2];
            elseif ($tmp == 1)
                return $word[0];
            elseif ($tmp == 2 or $tmp == 3 or $tmp == 4)
                return $word[1];
            else
                return $word[2];
        }
    }

    /**
     * Вывод числа с впереди стоящими нулями
     * @param $num
     * @param int $s_count
     * @return string
     */
    public static function getStringNum($num, $s_count=4) {

        $num = (string)$num;
        $l = strlen($num);
        $string_num = '';
        if ($l < $s_count) {
            for($i=0;$i<$s_count-$l;$i++)
                $string_num .= '0';
        }
        $string_num .= $num;

        return $string_num;

    }

    /**
     * Добавление http к ссылке
     * @param $link
     * @return string
     */
    public static function checkHttp($link)
    {

        if (!preg_match('/^http:\/\//i', $link)) {
            $link = 'http://'.$link;
        }

        return $link;
    }

    /**
     * Получение разницы дат (учитывая только рабочее время)
     * @param $startTime
     * @param $endTime
     * @return int
     */
    public static function calcTimeDifferent($startTime, $endTime)
    {
        $startTimestamp = MakeTimeStamp($startTime, "DD.MM.YYYY HH:MI:SS");
        $endTimestamp = MakeTimeStamp($endTime, "DD.MM.YYYY HH:MI:SS");

        $timeDifferent = $endTimestamp - $startTimestamp;

        // Получение времени нерабочих часов.



    }

    /**
     * Получение количества рабочих часов между датами
     * @param $date1
     * @param $date2
     * @param array $working_hours
     * @return float
     */
    public static function workHoursDifferent($date1, $date2, $working_hours = array(9, 18)) {

        if ($date1 > $date2) {
            $tmp = $date1;
            $date1 = $date2;
            $date2 = $tmp;
            unset($tmp);
            $sign = -1;
        } else
            $sign = 1;

        if ($date1 == $date2) return 0;

        $days = 0;
        $working_days = array(1,2,3,4,5); // Понедельник - пятница
        $current_date = $date1;
        $begin_hour = floor($working_hours[0]); $begin_minute = ($working_hours[0]*60)%60;
        $end_hour = floor($working_hours[1]); $end_mimute = ($working_hours[1]*60)%60;


        if (!in_array(date('w',$current_date) , $working_days)) {
            // Выходной

            // Начало рабочего для
            $current_date = mktime( $begin_hour, $begin_minute, 0, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
            // Поиск первого рабочего дня
            while ( !in_array(date('w',$current_date) , $working_days) ) {
                $current_date += 24*3600;
            }
        } else {


            $date0 = mktime( $begin_hour, $begin_minute, 0, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );

            if ($current_date<$date0) $current_date = $date0;

            $date3 = mktime( $end_hour, $end_mimute, 59, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
            if ($date3 < $current_date) {

                $current_date += 24*3600; // Следующий день

                $current_date = mktime( $begin_hour, $begin_minute, 0, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );
                while ( !in_array(date('w',$current_date) , $working_days) ) {
                    $current_date += 24*3600;
                }
            }
        }
        if (date('d.m.Y', $date1) == date('d.m.Y', $date2)) {

            if (mktime(date("H", $date2), date("i", $date2), 0, 0, 0, 0) < mktime($end_hour, $end_mimute, 0, 0, 0, 0)) {
                $end_hour = date("H", $date2);
                $end_mimute = date("i", $date2);
            }

            $date0 = mktime( $end_hour, $end_mimute, 59, date('n',$date2), date('j',$date2), date('Y',$date2) );
        } else
            $date0 = mktime( $end_hour, $end_mimute, 59, date('n',$current_date), date('j',$current_date), date('Y',$current_date) );

        $seconds = $date0-$current_date+1;


//        printf("\nFrom %s To %s : %d seconds\n",date('d/m/y H:i',$date1),date('d/m/y H:i',$date0),$seconds);

        $date3 = mktime( $begin_hour, $begin_minute, 0, date('n',$date2), date('j',$date2), date('Y',$date2) );
        while ( $current_date < $date3 ) {
            $current_date += 24*3600;
            if (in_array(date('w',$current_date) , $working_days) ) $days++;
        }
        if ($days>0) $days--;

//        printf("\nFrom %s To %s : %d working days\n",date('d/m/y H:i',$date1),date('d/m/y H:i',$date3),$days);


        if (date('d.m.Y', $date1) == date('d.m.Y', $date2))
            $date0 = mktime( $end_hour, 0, 0, date('n',$date2), date('j',$date2), date('Y',$date2) );
        else
            $date0 = mktime( $begin_hour, 0, 0, date('n',$date2), date('j',$date2), date('Y',$date2) );
        
        if ($date2<$date0) {
            // it's before, so nothing more !
        } else {
            // is it after ?
            $date3 = mktime( $end_hour, $end_mimute, 59, date('n',$date2), date('j',$date2), date('Y',$date2) );
            if ($date2>$date3) $date2=$date3;
            $tmp = $date2-$date0+1;
            $seconds += $tmp;

//            printf("\nFrom %s To %s : %d hours\n",date('d/m/y H:i',$date2),date('d/m/y H:i',$date3),$tmp/3600);
        }
        $seconds += 3600*($working_hours[1]-$working_hours[0])*$days;

//        printf("\nFrom %s To %s : %d hours\n",date('d/m/y H:i',$date1),date('d/m/y H:i',$date2),$seconds/3600);

        return $sign * $seconds/3600; // to get hours
    }

    /**
     * Получение списка всех папок в заданной директории
     * @param string $dir
     * @return array
     */
    public static function getDirInfo($dir='') {
        $data = array();
        $directory = $dir == '' ? self::getDir() : $dir;

        if (is_dir($directory))
            // Получение списка всех елементов
            if ($dh = opendir($directory)) {
                while (($d = readdir($dh)))
                    // определение дочерней директории
                    if (is_file($directory.$d) && $d != '.' && $d != '..')
                        $data[] = $d;
                closedir($dh);
            }
        return $data;
    }

    /**
     * Транслитерация
     * @param $string
     * @return mixed
     */
    public static function Transliterate($string)
    {
        $cyr=array(
            "Щ",  "Ш", "Ч", "Ц","Ю", "Я", "Ж", "А","Б","В","Г","Д","Е","Ё","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х", "Ь","Ы","Ъ","Э","Є","Ї",
            "щ",  "ш", "ч", "ц","ю", "я", "ж", "а","б","в","г","д","е","ё","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х", "ь","ы","ъ","э","є","ї"
        );
        $lat=array(
            "Shh","Sh","Ch","C","Ju","Ja","Zh","A","B","V","G","D","Je","Jo","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","Kh","'","Y","`","E","Je","Ji",
            "shh","sh","ch","c","ju","ja","zh","a","b","v","g","d","je","jo","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","'","y","`","e","je","ji"
        );
        for($i=0; $i<count($cyr); $i++)
        {
            $c_cyr = $cyr[$i];
            $c_lat = $lat[$i];
            $string = str_replace($c_cyr, $c_lat, $string);
        }
        $string = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/", "\${1}e", $string);
        $string = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/", "\${1}'", $string);
        $string = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $string);
        $string = preg_replace("/^kh/", "h", $string);
        $string = preg_replace("/^Kh/", "H", $string);
        return $string;
    }

    /**
     * Транслитерация URL
     * @param $string
     * @return mixed|string
     */
    public static function UrlTranslit($string)
    {
        $string = preg_replace("/[_\s\.,?!\[\](){}]+/", "_", $string);
        $string = preg_replace("/-{2,}/", "--", $string);
        $string = preg_replace("/_-+_/", "--", $string);
        $string = preg_replace("/[_\-]+$/", "", $string);
        $string = self::Transliterate($string);
        $string = strtolower($string);
        $string = preg_replace("/j{2,}/", "j", $string);
        $string = preg_replace("/[^0-9a-z_\-]+/", "", $string);
        return $string;
    }

    /**
     * Получение расширения файла
     * @param $name
     * @return mixed
     */
    public static function get_ext($name) {
        $f = preg_split("/\./", $name);
        return end($f);
    }

    /**
     * Синхронизация
     */
    public static function syncVendorItems() {

        CModule::includeModule('iblock');
        CModule::IncludeModule('highloadblock');
        $be = new CIBlockElement;
        $ibp = new CIBlockProperty;

        $res = CIBlockElement::GetList(
            array("SORT"=>"ASC"),
            array("IBLOCK_ID" => self::$catalog_iblock, "PROPERTY_SYNC" => array(false, "Y", "YY")),
            false,
            false,
            array("ID", "IBLOCK_ID", "NAME", "PROPERTY_VENDOR_ARTICLE", "PROPERTY_VENDOR_NAME")
        );

        $data = array();
        $i = 0;
        while ($item = $res->GetNext()) {
            $i++;
            $data[$item["PROPERTY_VENDOR_ARTICLE_VALUE"]][] = array(
                "ID" => $item["ID"],
                "IBLOCK_ID" => $item["IBLOCK_ID"],
                "VENDOR" => $item["PROPERTY_VENDOR_NAME_VALUE"]
            );
        }

        foreach($data AS $artucul => $item) {
            $currentItem = array();
            $currentProperties = array();
            $api_properties = array();
            $PROP = array();

            /*
            for($i=0;$i<count($item);$i++) {

                CIBlockElement::SetPropertyValuesEx(
                    $item[$i]["ID"],
                    $item[$i]["IBLOCK_ID"],
                    array("SYNC" => "YY"),
                    array()
                );


                $res = CIBlockElement::GetByID($item[$i]["ID"]);
                if($ar_res = $res->GetNext()) {

                    $testProp = $currentItemProperties = array();
                    $resProp = CIBlockElement::GetProperty($ar_res["IBLOCK_ID"], $ar_res["ID"], array(), array());
                    while($itemProp = $resProp->Fetch()) {
                        if ($itemProp["CODE"] != "VENDOR_NAME")
                            $testProp[] = $currentItemProperties[$itemProp["CODE"]] = array(
                                "NAME" => $itemProp["NAME"],
                                "VALUE" => $itemProp["VALUE"]
                            );
                    }


                    $currentItem = array_merge($currentItem, $ar_res);
                    $currentProperties = array_merge($currentProperties, $currentItemProperties);

                }

                $className = $item[$i]["VENDOR"].'Api';
                if (class_exists($className)) {
                    $api = new $className();

                    $properties = $api->getProperty($artucul, $item[$i]);
                    if (!empty($properties)) {

                        // Добавление свойств
                        foreach($properties AS $f_key => $f_value) {

                            $feature_key = substr(strtoupper(preg_replace('/(\s|[[:punct:]])+/', '_', SelfFunctions::Transliterate($f_value["NAME"]))),0,38);
                            $feature_key = preg_replace('/³/', '', $feature_key);

                            // Проверяем существование свойства
                            $checkProperty = CIBlockProperty::GetByID($feature_key, 2)->GetNext();
                            /*
                            $checkProperty = CIBlockProperty::GetByID($feature_key, 2);
                            while($test = $checkProperty->GetNext()) {
                                $ibp->->Delete($test["ID"]);
                                echo '<pre>';
                                print_r($test["ID"]);
                                echo '</pre>';
                            }
                            * /
                            if (!$checkProperty) {
                                if (trim($f_value["VALUE"]) == 'Да') {
                                    $arPropertyFields = Array(
                                        "NAME" => $f_value["NAME"],
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

                                    $property_id = $ibp->Add($arPropertyFields);
                                    $propValueRes = CIBlockProperty::GetPropertyEnum($property_id);
                                    while($propValue = $propValueRes->GetNext()) {
                                        if ($propValue["VALUE"] == trim($f_value["VALUE"])) {
                                            $f_value["VALUE"] = $propValue["ID"];
                                        }
                                    }
                                } else {
                                    $arPropertyFields = Array(
                                        "NAME" => $f_value["NAME"],
                                        "ACTIVE" => "Y",
                                        "SORT" => "500",
                                        "CODE" => $feature_key,
                                        "PROPERTY_TYPE" => is_string($f_value["VALUE"]) ? "S" : "N",
                                        "IBLOCK_ID" => 2,
                                        "WITH_DESCRIPTION" => "N",
                                    );


                                    $property_id = $ibp->Add($arPropertyFields);
                                }

                            } elseif($checkProperty["PROPERTY_TYPE"] == "L") {
                                $propValueRes = CIBlockProperty::GetPropertyEnum($checkProperty["ID"]);
                                while($propValue = $propValueRes->GetNext()) {
                                    if ($propValue["VALUE"] == trim($f_value["VALUE"])) {
                                        $f_value["VALUE"] = $propValue["ID"];
                                    }
                                }

                            }
                            $properties[$f_key] = array(
                                'NAME' => $f_value["NAME"],
                                'VALUE' => trim($f_value["VALUE"]),
                                'CODE' => $feature_key,
                            );

                        }
                    }
                    $api_properties = array_merge($api_properties, $properties);
                }


            }

            if (empty($api_properties)) {

            } else {

                foreach($currentProperties AS $code => $val)
                    $PROP[$code] = $val["VALUE"];

                foreach($api_properties AS $key => $val)
                    $PROP[$val["CODE"]] = $val["VALUE"];

                
                CIBlockElement::SetPropertyValuesEx(
                    $currentItem["ID"],
                    $currentItem["IBLOCK_ID"],
                    $PROP,
                    array()
                );
                continue;

            }

            */

            // Получение сопоставленной категории

            
            // Айди нашего хайлоад блока
            $hlblock_id = 6;

            $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

            if (empty($hlblock))
            {
                
                echo '<pre>';
                print_r('HL BLOCK 404');
                echo '</pre>';
                exit;
                
                ShowError('404');
                return;
            }

            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();


            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array("ID" => "ASC"),
                "filter" => array('UF_VENDOR_CAT_ID' => $currentItem["IBLOCK_SECTION_ID"])
            ));

            $categories = array();
            while($arData = $rsData->Fetch())
            {
                if ($arData["UF_CUR_CATEGORY_ID"] != 0)
                    $categories[] = $arData["UF_CUR_CATEGORY_ID"];
            }
            

            echo '<pre>';
            print_r($currentItem);
            echo '</pre>';
            exit;
            if (count($categories) > 0) {

                foreach($currentProperties AS $code => $val)
                    $PROP[$code] = $val["VALUE"];

                foreach($api_properties AS $key => $val)
                    $PROP[$val["CODE"]] = $val["VALUE"];

                echo '<pre>';
                print_r($PROP);
                echo '</pre>';

                $PROP["SYNC"] = "Y";
                $arFields = Array(
                    "IBLOCK_SECTION" => array($currentItem["IBLOCK_SECTION_ID"], $categories[0]),
                    "IBLOCK_ID"      => 2,
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => $currentItem["NAME"],
                    "CODE"           => $currentItem["CODE"],
                    "ACTIVE"         => "Y",            // активен
                );


                $be->Update($currentItem["ID"], $arFields);

                echo '<pre>update';
                print_r($currentItem["ID"]);
                echo '</pre>';


            } else {
                echo '<pre>';
                print_r($item);
                echo '</pre>';

                flush();
            }

        }

    }

    /**
     * Импорт категорий для сопоставления
     * @param $file
     * @param $disributorName
     */
    public static function categoryHiloadBlockImport($file, $disributorName) {
        CModule::IncludeModule('highloadblock');

        $start = time();


        global $DB;

        // Айди нашего хайлоад блока
        $hlblock_id = 6;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
        {
            echo '<pre>';
            print_r('HL BLOCK 404');
            echo '</pre>';
            exit;

            ShowError('404');
            return;
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('UF_VENDOR_CATEGORY' => $disributorName),
        ));

        $categories = array();
        while($arData = $rsData->Fetch())
        {
            $categories[$arData["UF_VENDOR_CAT_ID"]][] = $arData;
        }
        

        $row = 0;
        if (($handle = fopen($_SERVER["DOCUMENT_ROOT"].$file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                if ($row > 1 && $data[2] == 'Ocs' && (int)$data[3] > 0) {
                    
                    if (isset($categories[$data[0]])) {
                        // Обновление записи
                        if (count($categories[$data[0]]) > 1) {
                            echo '<pre>';
                            print_r($categories[$data[0]]);
                            echo '</pre>';
                            exit;
                        } else {

                            if ($data[3] != $categories[$data[0]][0]["UF_CUR_CATEGORY_ID"]) {
                                // Получение названия текущей категории
                                $currentCat = CIBlockSection::GetByID($data[3])->Fetch();

                                $query = "
                                  UPDATE 
                                    `vendors_categories` 
                                  SET 
                                    `UF_VENDOR_CAT_ID` = ".$data[0].",
                                    `UF_VENDOR_CAT_NAME` = '".addslashes($data[1])."', 
                                    `UF_VENDOR_CATEGORY` = ".$disributorName.", 
                                    `UF_CUR_CATEGORY_ID` = ".$data[3].", 
                                    `UF_CURRENT_CATEGORY` = '".addslashes($currentCat["NAME"])."' 
                                  WHERE 
                                    `ID` = ".$categories[$data[0]][0]["ID"]."
                                ";

                                $DB->Query($query);

                            }
                        }
                    } else {

                        // Получение названия текущей категории
                        $currentCat = CIBlockSection::GetByID($data[3])->Fetch();

                        // Добавление записи
                        $query = "INSERT INTO `vendors_categories` 
                        (`UF_VENDOR_CAT_ID`, `UF_VENDOR_CAT_NAME`, `UF_VENDOR_CATEGORY`, `UF_CUR_CATEGORY_ID`, `UF_CURRENT_CATEGORY`) VALUES 
                        (".$data[0].", '".addslashes($data[1])."', ".$disributorName.", ".$data[3].", '".addslashes($currentCat["NAME"])."')";

                        $DB->Query($query);
                    }
                    
                    
                   
                }
                $row++;
            }
        }



    }

    /**
     * Синхронизирование категорий
     * @param $distributor
     * @param $distributorID
     */
    public static function categorySync($distributor, $distributorID, $updateCheckPoint = "CATEGORIES_SYNC") {
        CModule::includeModule('iblock');
        CModule::IncludeModule('highloadblock');
        $be = new CIBlockElement;
        $ibp = new CIBlockProperty;


        $start = time();

        $arFilter = array("IBLOCK_ID" => self::$catalog_iblock, "!PROPERTY_SYNC" => $updateCheckPoint, "PROPERTY_VENDOR_NAME" => $distributor);
//        $arFilter = array("IBLOCK_ID" => 2, "ID" => "29673", "PROPERTY_VENDOR_NAME" => $distributor);
        
        $res = CIBlockElement::GetList(
            array("SORT"=>"ASC"),
            $arFilter,
            false,
            false,
            array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_VENDOR_ARTICLE", "PROPERTY_VENDOR_NAME")
        );
        
        // Айди нашего хайлоад блока
        $hlblock_id = 6;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
        {
            echo '<pre>';
            print_r('HL BLOCK 404');
            echo '</pre>';
            exit;

            ShowError('404');
            return;
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('UF_VENDOR_CATEGORY' => $distributorID)
        ));

        $disctributorCategories = array();
        while($arData = $rsData->Fetch())
        {
            if ($arData["UF_CUR_CATEGORY_ID"] != 0)
                $disctributorCategories[$arData["UF_VENDOR_CAT_ID"]] = $arData;
        }
        
        $i = 0;
        while ($item = $res->GetNext()) {
            if (time() - 120 > $start) {
                echo '<pre>';
                print_r($i);
                echo '</pre>';
                exit;
            }

            // Получение всех категорий элемента
            $resSections = CIBlockElement::GetElementGroups($item["ID"], false, array("ID", "NAME", "UF_VENDOR_NAME"));
            $sections = array();
            while($sect = $resSections->GetNext()) {
                $sections[$sect["ID"]] = $sect;
            }
            
            if (count($sections) == 0) continue;
            $disctributor_category = 0;
            foreach ($sections AS $key => $value) {
                if (isset($disctributorCategories[$key])) {
                    $disctributor_category = $key;
                }
            }
            

            if ($disctributor_category == 0) continue;
            $check_synk_item = false;
            if (count($sections) > 1) {
                foreach ($sections AS $key => $value) {
                    if (isset($sections[$disctributorCategories[$disctributor_category]["UF_CUR_CATEGORY_ID"]])) {
                        $check_synk_item = true;
                    }
                }
            }
            if ($check_synk_item) continue;

            if (isset($disctributorCategories[$disctributor_category])) {

                $PROP["SYNC"] = $updateCheckPoint;
                $arFields = Array(
                    "IBLOCK_SECTION" => array($item["IBLOCK_SECTION_ID"], $disctributorCategories[$disctributor_category]["UF_CUR_CATEGORY_ID"]),
                    "IBLOCK_ID"      => 2,
                    "ACTIVE"         => "Y",            // активен
                );

                $be->Update($item["ID"], $arFields);

                CIBlockElement::SetPropertyValuesEx(
                    $item["ID"],
                    $item["IBLOCK_ID"],
                    $PROP,
                    array()
                );

                echo '<pre>update';
                print_r($item["ID"]);
                echo '</pre>';
                flush();

            } else {
                echo '<pre>have no category';
                print_r($item);
                echo '</pre>';

                flush();
            }

            $i++;
        }
        
        
        echo '<pre>';
        print_r($i);
        echo '</pre>';
        exit;
        
    }

    /**
     * @param $what_to_clean
     * @param string $tidy_config
     * @return string
     */
    public static function cleaning($what_to_clean, $tidy_config='' ) {
        
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
     * Получение изображения
     * @param $file
     * @param int $width
     * @param int $height
     * @param int $mobile_width
     * @param int $mobile_height
     * @param int $tablet_width
     * @param int $tablet_height
     * @param int $quality
     * @param bool $watermark
     * @return array|bool
     */
    public static function getResizeImage($file, $width = 0, $height = 0, $mobile_width = 0, $mobile_height = 0, $tablet_width = 0, $tablet_height = 0, $quality = 80, $watermark = false) {

        if (empty($file))
            return false;


        if ($width == 0 && $height == 0) {
            $width = self::$image_width;
            $height = self::$image_height;
        }

        $arFile = $arFileRetina = $arMobileFile = $arMobileFileRetina = $arTabletFile = $arTabletFileRetina = array();
        if (!is_array($file)) {
            $arFile = $arFileRetina = $arMobileFile = $arMobileFileRetina = $arTabletFile = $arTabletFileRetina = CFileWater::GetFileArray($file);
        }

        if ($height == 0) {
            if ($arFile["WIDTH"] > $width) {
                $height = round($arFile["HEIGHT"] * $width / $arFile["WIDTH"]);
            }
        } elseif ($width == 0) {
            if ($arFile["HEIGHT"] > $height) {
                $width = round($arFile["WIDTH"]*$height/$arFile["HEIGHT"]);
            }
        } elseif ($arFile["WIDTH"] < $width || $arFile["HEIGHT"] < $height) {

            if ($arFile["WIDTH"]/$arFile["HEIGHT"] > $width/$height) {
                $width = round($width*$arFile["HEIGHT"]/$height);
                $height = $arFile["HEIGHT"];
            } else {
                $height = round($arFile["WIDTH"]*$height/$width);
                $width = $arFile["WIDTH"];
            }
        }

        $arFile["RETINA"] = array();
        if ($resizeFile =  CFileWater::ResizeImageGet($arFile, array("width" => $width, "height" => $height), BX_RESIZE_IMAGE_EXACT, true, false, false, $quality, $watermark)) {
            $arFile["SRC"] = $resizeFile['src'];
            $arFile["WIDTH"] = $resizeFile['width'];
            $arFile["HEIGHT"] = $resizeFile['height'];
        }
        if ($resizeRetinaFile = CFileWater::ResizeImageGet($arFileRetina, array("width" => 2*$width, "height" => 2*$height), BX_RESIZE_IMAGE_EXACT, true, false, false, $quality, $watermark)) {
            $arFile["RETINA"]["SRC"] = $resizeRetinaFile['src'];
            $arFile["RETINA"]["WIDTH"] = $resizeRetinaFile['width'];
            $arFile["RETINA"]["HEIGHT"] = $resizeRetinaFile['height'];
        }

        $filemtime = filemtime($_SERVER["DOCUMENT_ROOT"].$arFile["SRC"]);
        $arFile["SRC"] .= '?'.$filemtime;
        $arFile["RETINA"]["SRC"] .= '?'.$filemtime;

        // Ресайз для смартфонов
        if ($mobile_width > 0 || $mobile_height > 0) {
            if ($mobile_height == 0) {
                if ($arMobileFile["WIDTH"] > $mobile_width) {
                    $mobile_height = round($arMobileFile["HEIGHT"] * $mobile_width / $arMobileFile["WIDTH"]);
                }
            } elseif ($mobile_width == 0) {
                if ($arMobileFile["HEIGHT"] > $height) {
                    $mobile_width = round($arMobileFile["WIDTH"]*$mobile_height/$arMobileFile["HEIGHT"]);
                }
            } elseif ($arMobileFile["WIDTH"] < $mobile_width || $arMobileFile["HEIGHT"] < $mobile_height) {

                if ($arMobileFile["WIDTH"]/$arMobileFile["HEIGHT"] > $mobile_width/$mobile_height) {
                    $mobile_width = round($mobile_width*$arMobileFile["HEIGHT"]/$mobile_height);
                    $mobile_height = $arMobileFile["HEIGHT"];
                } else {
                    $mobile_height = round($arMobileFile["WIDTH"]*$mobile_height/$mobile_width);
                    $mobile_width = $arMobileFile["WIDTH"];
                }
            }

            if ($resizeMobileFile = CFileWater::ResizeImageGet($arMobileFile, array("width" => $mobile_width, "height" => $mobile_height), BX_RESIZE_IMAGE_EXACT, true, false, false, $quality, $watermark)) {
                $arFile["MOBILE"]["SRC"] = $resizeMobileFile['src'];
                $arFile["MOBILE"]["WIDTH"] = $resizeMobileFile['width'];
                $arFile["MOBILE"]["HEIGHT"] = $resizeMobileFile['height'];
            }
            if ($resizeRetinaMobileFile = CFileWater::ResizeImageGet($arMobileFileRetina, array("width" => 2*$mobile_width, "height" => 2*$mobile_height), BX_RESIZE_IMAGE_EXACT, true, false, false, $quality, $watermark)) {
                $arFile["MOBILE"]["RETINA"]["SRC"] = $resizeRetinaMobileFile['src'];
                $arFile["MOBILE"]["RETINA"]["WIDTH"] = $resizeRetinaMobileFile['width'];
                $arFile["MOBILE"]["RETINA"]["HEIGHT"] = $resizeRetinaMobileFile['height'];
            }

            $filemtime = filemtime($_SERVER["DOCUMENT_ROOT"].$arFile["MOBILE"]["SRC"]);
            $arFile["MOBILE"]["SRC"] .= '?'.$filemtime;
            $arFile["MOBILE"]["RETINA"]["SRC"] .= '?'.$filemtime;
        }
        // Ресайз для планшетов
        if ($tablet_width > 0 || $tablet_height > 0) {

            if ($tablet_height == 0) {
                if ($arTabletFile["WIDTH"] > $tablet_width) {
                    $tablet_height = round($arTabletFile["HEIGHT"] * $tablet_width / $arTabletFile["WIDTH"]);
                }
            } elseif ($tablet_width == 0) {
                if ($arTabletFile["HEIGHT"] > $height) {
                    $tablet_width = round($arTabletFile["WIDTH"]*$tablet_height/$arTabletFile["HEIGHT"]);
                }
            } elseif ($arTabletFile["WIDTH"] < $tablet_width || $arTabletFile["HEIGHT"] < $tablet_height) {

                if ($arTabletFile["WIDTH"]/$arTabletFile["HEIGHT"] > $tablet_width/$tablet_height) {
                    $tablet_width = round($tablet_width*$arTabletFile["HEIGHT"]/$tablet_height);
                    $tablet_height = $arTabletFile["HEIGHT"];
                } else {
                    $tablet_height = round($arTabletFile["WIDTH"]*$tablet_height/$tablet_width);
                    $tablet_width = $arTabletFile["WIDTH"];
                }
            }

            if ($resizeTabletFile = CFileWater::ResizeImageGet($arTabletFile, array("width" => $tablet_width, "height" => $tablet_height), BX_RESIZE_IMAGE_EXACT, true, false, false, $quality, $watermark)) {
                $arFile["TABLET"]["SRC"] = $resizeTabletFile['src'];
                $arFile["TABLET"]["WIDTH"] = $resizeTabletFile['width'];
                $arFile["TABLET"]["HEIGHT"] = $resizeTabletFile['height'];
            }

            if ($resizeRetinaTabletFile = CFileWater::ResizeImageGet($arTabletFileRetina, array("width" => 2*$tablet_width, "height" => 2*$tablet_height), BX_RESIZE_IMAGE_EXACT, true, false, false, $quality, $watermark)) {
                $arFile["TABLET"]["RETINA"]["SRC"] = $resizeTabletFile['src'];
                $arFile["TABLET"]["RETINA"]["WIDTH"] = $resizeRetinaTabletFile['width'];
                $arFile["TABLET"]["RETINA"]["HEIGHT"] = $resizeRetinaTabletFile['height'];
            }

            $filemtime = filemtime($_SERVER["DOCUMENT_ROOT"].$arFile["TABLET"]["SRC"]);
            $arFile["TABLET"]["SRC"] .= '?'.$filemtime;
            $arFile["TABLET"]["RETINA"]["SRC"] .= '?'.$filemtime;


        }

        unset($arFileRetina, $arMobileFile, $arMobileFileRetina, $arTabletFile, $arTabletFileRetina);
        return $arFile;

    }
    /**
     * Получение всех категорий позиции
     * @param $ELEMENT_ID
     * @return array
     */
    public static function getItemsCategories($ELEMENT_ID) {
        CModule::includeModule('iblock');


        $db_old_groups = CIBlockElement::GetElementGroups($ELEMENT_ID, true, array("ID", "NAME"));;
        $ar_new_groups = array();
        while($ar_group = $db_old_groups->Fetch()) {

            $res = CIBlockSection::GetList(
                array("SORT"=>"ASC"),
                array("IBLOCK_ID" => 2, "ID" => $ar_group["ID"]),
                false,
                array("ID", "NAME", "UF_VENDOR_NAME"),
                false
            )->Fetch();
            
            $ar_new_groups[] = $res;
        }
            

        return $ar_new_groups;
    }

    /**
     * Скрытие категорий вендора
     * @param $vendor
     * @return array
     */
    public static function hideCategories($vendor) {
        CModule::includeModule('iblock');
        $bs = new CIBlockSection;

        $res = CIBlockSection::GetList(
            array("SORT"=>"ASC"),
            array("IBLOCK_ID" => 2, "UF_VENDOR_NAME" => $vendor),
            false,
            array("ID", "NAME", "UF_VENDOR_NAME"),
            false
        );
        
        while($section = $res->GetNext()) {
            $arFields = Array("ACTIVE" => "N");
            $bs->Update($section["ID"], $arFields);
        }
        
    }

    /**
     * Копирование кодов свойств элемента в HL block
     */
    public static function elementPropertiesHLSync() {
        CModule::includeModule('iblock');
        CModule::IncludeModule('highloadblock');
        $be = new CIBlockElement;
        $ibp = new CIBlockProperty;
        
        // Получение всех свойств елементов каталога
        $rsProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => self::$catalog_iblock));
        $properties = array();

        // Айди нашего хайлоад блока
        $hlblock_id = 8;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
        {
            ShowError('404');
            return;
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        while($prop = $rsProperty->Fetch()) {

            $rsData = $entity_data_class::getList(array("select" => array("*"), "order" => array("ID" => "ASC"), "filter" => array('UF_PROPERTY_CODE' => $prop["CODE"])))->Fetch();
            if (!isset($rsData["ID"]) && !strstr($rsData["UF_PROPERTY_CODE"], "LIST_") && !strstr($rsData["UF_PROPERTY_CODE"], "MODIF_")) {
                $result = $entity_data_class::add(array(
                    "UF_PROPERTY_NAME" => $prop["NAME"],
                    "UF_XML_ID" => $prop["CODE"],
                    "UF_PROPERTY_CODE" => $prop["CODE"],
                ));
            }
        }

        // Получение всех свойств елементов товарных предложений
        $rsSKUProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => 3));


        // Айди нашего хайлоад блока
        $hlblock_id = 9;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
        {
            ShowError('404');
            return;
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        while($prop = $rsSKUProperty->Fetch()) {

            $rsData = $entity_data_class::getList(array("select" => array("*"), "order" => array("ID" => "ASC"), "filter" => array('UF_SKU_PROPERTY_CODE' => $prop["CODE"])))->Fetch();
            if (!isset($rsData["ID"])) {
                $result = $entity_data_class::add(array(
                    "UF_SKU_PROPERTY_NAME" => $prop["NAME"],
                    "UF_XML_ID" => $prop["CODE"],
                    "UF_SKU_PROPERTY_CODE" => $prop["CODE"],
                ));
            }
        }
    }

    /**
     * Построение дерева из массива
     * @param $sections
     * @param int $base_depth
     * @param array $tree
     * @return array
     */
    public static function buildTree($sections, $base_depth = 0, $tree = array()) {

        // Получение наименьшей глубины
        $max_depth = 0;
        $min_depth = 0;
        foreach ($sections AS $key => $section) {
            if ($base_depth == 0) {
                if ($base_depth == 0 || $base_depth > $section["DEPTH_LEVEL"])
                    $base_depth = $section["DEPTH_LEVEL"];
            }

            if ($min_depth == 0 || $min_depth > $section["DEPTH_LEVEL"])
                $min_depth = $section["DEPTH_LEVEL"];
            if ($max_depth == 0 || $max_depth < $section["DEPTH_LEVEL"])
                $max_depth = $section["DEPTH_LEVEL"];
        }

        if ($min_depth > 1) {
            foreach ($sections AS $section) {
                if (empty($tree)) {
                    // Получение корневого элемента
                    $navRes = CIBlockSection::GetNavChain($section["IBLOCK_ID"], $section["ID"], array("ID", "IBLOCK_ID", "NAME", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SECTION_PAGE_URL"));
                    if ($rootSection = $navRes->GetNext()) {
                        $tree[] = $rootSection;
                    }
                } else {
                    $checkRoot = false;
                    foreach ($tree AS $branch) {
                        if ($section["LEFT_MARGIN"] > $branch["LEFT_MARGIN"] && $section["RIGHT_MARGIN"] < $branch["RIGHT_MARGIN"]) {
                            $checkRoot = true;
                        }
                    }
                    if (!$checkRoot) {
                        // Получение корневого элемента
                        $navRes = CIBlockSection::GetNavChain($section["IBLOCK_ID"], $section["ID"], array("ID", "IBLOCK_ID", "NAME", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SECTION_PAGE_URL"));
                        if ($rootSection = $navRes->GetNext()) {
                            $tree[] = $rootSection;
                        }
                    }
                }
            }
        }


        if (empty($tree)) {
            foreach ($sections AS $section) {
                if ($section["DEPTH_LEVEL"] == $base_depth) {
                    $tree[] = $section;
                }
            }
        } else {
            foreach ($tree AS $key => $branch) {
                foreach ($sections AS $section) {
                    if ($section["DEPTH_LEVEL"] == $base_depth && $section["LEFT_MARGIN"] > $branch["LEFT_MARGIN"] && $section["RIGHT_MARGIN"] < $branch["RIGHT_MARGIN"]) {
                        $tree[$key]["CHILDREN"][] = $section;
                    }
                }
            }
        }

        if ($base_depth < $max_depth)
            $tree[$key]["CHILDREN"] = self::buildTree($sections, $base_depth+1, $tree);


        return $tree;
    }

    public static function buildTreeFromArray($array, $maxLevel = 0, $currentLevel = 1) {
        // Получение максимальной грубины
        $maxDepth = 0;
        foreach ($array AS $key => $value) {
            if ($value["DEPTH_LEVEL"] > $maxDepth)
                $maxDepth = $value["DEPTH_LEVEL"];
        }

        if ($currentLevel > $maxLevel) {
            return $array;
        }

        $tmp = array();

        foreach ($array AS $key => $value) {
            if ($value["DEPTH_LEVEL"] == $currentLevel) {
                $tmp[$key] = $value;
            } else {
                foreach ($array AS $parentKey => $parentValue) {
                    if ($value["LEFT_MARGIN"] > $parentValue["LEFT_MARGIN"] && $value["RIGHT_MARGIN"] < $parentValue["RIGHT_MARGIN"] && $parentValue["DEPTH_LEVEL"] == $currentLevel) {
                        if (!isset($tmp[$parentKey])) {
                            $tmp[$parentKey] = $parentValue;
                        }
                        $tmp[$parentKey]["CHILDREN"][] = $value;
                    }
                }
            }
        }

        if ($currentLevel < $maxDepth) {
            foreach ($tmp AS $key => $value) {
                if (isset($tmp["CHILDREN"]) && !empty($tmp["CHILDREN"])) {
                    $tmp["CHILDREN"] = self::buildTreeFromArray($tmp["CHILDREN"], $maxLevel, $currentLevel+1);
                }
            }
        }


        return $tmp;
    }


    /**
     * Загрузка матрицы сопоставления
     * @param PHPExcel $objPHPExcel
     * @param int $categoriesLevel
     * @return array
     * @throws PHPExcel_Exception
     */
    public static function matrixUpload(PHPExcel $objPHPExcel, $categoriesLevel = 3) {

        CModule::includeModule('iblock');
        $bs = new CIBlockSection;
        $be = new CIBlockElement;

        $undefinedItems = array();

        $sheet = $objPHPExcel->getActiveSheet(0);

        //    Identify the range that we need to extract from the worksheet
        $maxCol = $sheet->getHighestColumn();
        $maxRow = $sheet->getHighestRow();

        $mergedCellsRange = $sheet->getMergeCells();

        $categories = array();
        $items = array();
        for($i=1;$i<=$maxRow&&$i<=$categoriesLevel;$i++) {

            $last_column = $maxCol;
            $column_number = 0;
            do {
                $column_name = (($t = floor($column_number / 26)) == 0 ? '' : chr(ord('A')+$t-1)).chr(ord('A')+floor($column_number % 26));
                $column_number++;

                $categories[$i][$column_number] = self::getCellValue($sheet, $column_name.$i);
            } while ($column_name != $last_column);

        }

        for($i=$categoriesLevel+1;$i<=$maxRow;$i++) {

            $last_column = $maxCol;
            $column_number = 0;
            do {
                $column_name = (($t = floor($column_number / 26)) == 0 ? '' : chr(ord('A')+$t-1)).chr(ord('A')+floor($column_number % 26));
                $column_number++;

                $items[$i][$column_number] = self::getCellValue($sheet, $column_name.$i);
            } while ($column_name != $last_column);

        }

        $checkedCategories = array();
        // Заполнение категорий
        foreach ($categories AS $categoryRowKey => $categoryRowItem) {
            foreach ($categoryRowItem AS $categoryCellKey => $categoryCellItem) {
                if (!isset($categories[$categoryRowKey-1][$categoryCellKey]) || $categories[$categoryRowKey-1][$categoryCellKey] != $categoryCellItem) {
                    if (!isset($categoryRowItem[$categoryCellKey-1]) || $categoryRowItem[$categoryCellKey-1] != $categoryCellItem) {

                        // Получение идентификатора категории каталога

                        $categoryRes = $bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 21, "NAME" => $categoryCellItem), false, array("ID"), false)->Fetch();

                        if (!isset($categoryRes["ID"])) {

                            if (isset($categories[$categoryRowKey-1][$categoryCellKey])) {
                                // Получение родительского элемента
                                $categoryRes = $bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 21, "NAME" => $categories[$categoryRowKey-1][$categoryCellKey]), false, array("ID"), false)->Fetch();
                                $parent = $categoryRes["ID"];
                            } else {
                                $parent = false;
                            }


                            $arFields = Array(
                                "ACTIVE" => "Y",
                                "IBLOCK_SECTION_ID" => $parent,
                                "IBLOCK_ID" => 21,
                                "NAME" => $categoryCellItem,
                                "CODE" => preg_replace('/\s+/', '-', SelfFunctions::Transliterate($categoryCellItem)),
                                "SORT" => 500,
                            );

                            $id = $bs->Add($arFields);
                            $checkedCategories[$categoryCellItem] = $id;
                        } else {
                            $checkedCategories[$categoryCellItem] = $categoryRes["ID"];
                        }

                    }
                }
            }
        }

        $lastCateroryRow = end($categories);
        // Заполнение позиций
        foreach ($items AS $itemsRowKey => $itemsRow) {
            foreach ($itemsRow AS $itemsCellKey => $itemsCell) {
                if (!empty($itemsCell)) {

                    // Получение родительской категории
                    if (isset($lastCateroryRow[$itemsCellKey])) {
                        // Получение родительского элемента

                        if (!isset($checkedCategories[$lastCateroryRow[$itemsCellKey]])) {
                            $categoryRes = $bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 21, "NAME" => $lastCateroryRow[$itemsCellKey]), false, array("ID"), false)->Fetch();
                            $parent = $categoryRes["ID"];
                        } else {
                            $parent = $checkedCategories[$lastCateroryRow[$itemsCellKey]];
                        }


                        // Проверка существования позиции
                        $itemRes = $be->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 21, "NAME" => $itemsCell), false, false, array("ID"))->Fetch();
                        if (!isset($itemRes["ID"])) {

                            $arFields = Array(
                                "IBLOCK_SECTION_ID" => $parent,
                                "IBLOCK_ID"      => 21,
                                "NAME"           => $itemsCell,
                                "CODE"           => strtolower(preg_replace('/(\s|[[:punct:]]+)/', '-', SelfFunctions::Transliterate($itemsCell))),
                                "ACTIVE"         => "Y",            // активен
                            );

                            $id = $be->Add($arFields);

                        }

                    } else {
                        echo '<pre>';
                        print_r("ERROR - NOT FOUND CATEGORY");
                        echo '</pre>';
                        exit;
                    }
                }
            }
        }

        return $undefinedItems;
    }



    public static function getCellValue($activeSheet, $cellOrCol, $row = null, $format = 'd.m.Y')
    {
        //column set by index
        if(is_numeric($cellOrCol)) {
            $cell = $activeSheet->getCellByColumnAndRow($cellOrCol, $row);
        } else {
            $lastChar = substr($cellOrCol, -1, 1);
            if(!is_numeric($lastChar)) { //column contains only letter, e.g. "A"
                $cellOrCol .= $row;
            }
            $cell = $activeSheet->getCell($cellOrCol);
        }

        //try to find current coordinate in all merged cells ranges
        $mergedCellsRange = $activeSheet->getMergeCells();
        foreach($mergedCellsRange as $currMergedRange){
            if($cell->isInRange($currMergedRange)) {
                $currMergedCellsArray = PHPExcel_Cell::splitRange($currMergedRange);
                $cell = $activeSheet->getCell($currMergedCellsArray[0][0]);
                break;
            }
        }

        //simple value
        $val = $cell->getValue();
        if ($val instanceof PHPExcel_RichText) {

            $cellData = '';
            // Loop through rich text elements
            $elements = $val->getRichTextElements();

            foreach ($elements as $element) {

                // Rich text start?
                if ($element instanceof PHPExcel_RichText_Run) {
                    $cellData .= '';

                    if ($element->getFont()->getSuperScript()) {
                        $cellData .= '<sup>';
                    } elseif ($element->getFont()->getSubScript()) {
                        $cellData .= '<sub>';
                    }
                }

                // Convert UTF8 data to PCDATA

                $cellText = $element->getText();

                $openP = false;
                if (preg_match('/^\\n/Usi', $cellText)) {
                    $cellText = preg_replace('/^\\n/Usi', '<p>', $cellText);
                    $openP = true;
                }
                if (preg_match('/\\n$/Usi', $cellText) && $openP) {
                    $cellText = preg_replace('/\\n$/Usi', '</p>', $cellText);
                    $openP = false;
                } elseif (preg_match('/\\n$/Usi', $cellText) && !$openP) {
                    $cellText = preg_replace('/\\n$/Usi', '<br />', $cellText);
                }
                if ($openP)
                    $cellText = $cellText.'</p>';


                $cellText = preg_replace('/\\n/Usi', '<br />', $cellText);

                if ($element->getFont()->getBold()) {
                    if (preg_match('/^<p/Usi', $cellText)) {
                        $cellText = preg_replace('/^<p/Usi', '<p style="font-weight: bold;"', $cellText);
                    } else {
                        $cellText = '<strong>'.$cellText.'</strong>';
                    }

                }



//                $cellData .= htmlspecialchars($cellText);
                $cellData .= $cellText;



                if ($element instanceof PHPExcel_RichText_Run) {
                    if ($element->getFont()->getSuperScript()) {
                        $cellData .= '</sup>';
                    } elseif ($element->getFont()->getSubScript()) {
                        $cellData .= '</sub>';
                    }

                    $cellData .= '';
                }
            }
            $val = $cellData;
        } else {

            //date
//            if(PHPExcel_Shared_Date::isDateTime($cell)) {
//                $val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val));
//            }

            //for incorrect formulas take old value
            if((substr($val,0,1) === '=' ) && (strlen($val) > 1)){
                $val = $cell->getOldCalculatedValue();
            }

            $val = preg_replace('/\\n/Usi', '<br />', $val);
        }



        return $val;
    }



    public static function importItem($data) {


        CModule::includeModule('iblock');
        $bs = new CIBlockSection;
        $be = new CIBlockElement;
        $ibp = new CIBlockProperty;

        if (is_object($data)) {
            $data = (array)$data;
            foreach ($data AS $key => $value) {
                $data[$key] = (array)$value;
            }
        }


        if (!isset($data["ИНФОРМАЦИЯ ДЛА АДМИНКИ, КАТАЛОГА И КТ. ВЕНДОР - АДМИНКА+КАТАЛОГ, АРТИКУЛ - АДМИНКА+КАТАЛОГ+КТ, МОДЕЛЬ - КАТАЛОГ"]["Артикул"]) || empty($data["ИНФОРМАЦИЯ ДЛА АДМИНКИ, КАТАЛОГА И КТ. ВЕНДОР - АДМИНКА+КАТАЛОГ, АРТИКУЛ - АДМИНКА+КАТАЛОГ+КТ, МОДЕЛЬ - КАТАЛОГ"]["Артикул"])) {
            return false;
        }

        $articul = $data["ИНФОРМАЦИЯ ДЛА АДМИНКИ, КАТАЛОГА И КТ. ВЕНДОР - АДМИНКА+КАТАЛОГ, АРТИКУЛ - АДМИНКА+КАТАЛОГ+КТ, МОДЕЛЬ - КАТАЛОГ"]["Артикул"];
        $model = $data["ИНФОРМАЦИЯ ДЛА АДМИНКИ, КАТАЛОГА И КТ. ВЕНДОР - АДМИНКА+КАТАЛОГ, АРТИКУЛ - АДМИНКА+КАТАЛОГ+КТ, МОДЕЛЬ - КАТАЛОГ"]["Название модели"];
        $vendor = $data["ИНФОРМАЦИЯ ДЛА АДМИНКИ, КАТАЛОГА И КТ. ВЕНДОР - АДМИНКА+КАТАЛОГ, АРТИКУЛ - АДМИНКА+КАТАЛОГ+КТ, МОДЕЛЬ - КАТАЛОГ"]["Вендор"];

        $returnName = $model.' '.$articul.' ('.$vendor.')';

        $group = $data["АДРЕС ТОВАРА В КАТАЛОГЕ/АДМИНКЕ"]["Группа"];
        $category = $data["АДРЕС ТОВАРА В КАТАЛОГЕ/АДМИНКЕ"]["Категория"];

        $subcategory = array();
        foreach ($data["АДРЕС ТОВАРА В КАТАЛОГЕ/АДМИНКЕ"] AS $key => $value) {
            if (strstr($key, 'Подкатегория')) {
                $subcategory[] = $value;
            }
        }

        // Получение/Добавление категории
        if ($group == 'Оборудование') {
            $checkBaseCategory = $bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => self::$catalog_iblock, "NAME" => $category, "DEPTH_LEVEL" => 1, "UF_VENDOR_NAME" => false), false, array("ID", "NAME"), false)->GetNext();
            if (!$checkBaseCategory["ID"]) {
                // Добавление категории
                $arFields = Array(
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => self::$catalog_iblock,
                    "NAME" => $category,
                    "CODE" => self::UrlTranslit($category),
                    "SORT" => 500,
                );
                $id = $bs->Add($arFields);
                $parentCategory = $id;

            } else {
                $parentCategory = $checkBaseCategory["ID"];
            }

            foreach ($subcategory AS $subcat) {
                $checkSubCategory = $bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => self::$catalog_iblock, "NAME" => $subcat, "SECTION_ID" => $parentCategory, "UF_VENDOR_NAME" => false), false, array("ID", "NAME"), false)->GetNext();

                if (!$checkSubCategory["ID"]) {
                    // Добавление категории
                    $arFields = Array(
                        "ACTIVE" => "Y",
                        "IBLOCK_SECTION_ID" => $parentCategory,
                        "IBLOCK_ID" => self::$catalog_iblock,
                        "NAME" => $subcat,
                        "CODE" => self::UrlTranslit($subcat),
                        "SORT" => 500,
                    );
                    $id = $bs->Add($arFields);
                    $parentCategory = $id;

                } else {
                    $parentCategory = $checkSubCategory["ID"];
                }
            }
        }

        $features = $data["ПОДРОБНЫЕ ХАРАКТЕРИСТИКИ (СЛЕВА - НАЗВАНИЕ ГРУППЫ, СПРАВА - ХАРАКТЕРИСТИКА И ПАРАМЕТР; ПУСТЫЕ ЗНАЧЕНИЯ - НЕ СОЗДАЁМ ХАРАКТЕРИСТИКУ; ЖЁЛТЫЕ ПОЛЯ - ТЕХНИЧЕСКИЕ, НЕ СОЗДАЁМ)"];
        $seo = $data["SEO-ИНФОРМАЦИЯ"];

        // Определение/добавление характеристик
        $PROP = array();

        // Получение списка всех хараетеристик инфоблока
        if (empty(self::$iblock_properties)) {
            $iblockPropRes = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => self::$catalog_iblock));
            while($prop = $iblockPropRes->GetNext()) {
                self::$iblock_properties[strtolower(trim($prop["NAME"]))] = array(
                    "ID" => $prop["ID"],
                    "PROPERTY_TYPE" => $prop["PROPERTY_TYPE"],
                    "CODE" => $prop["CODE"],
                );
            }
        }

        foreach ($features AS $key => $value) {

            if (isset(self::$iblock_properties[strtolower(trim($key))])) {
                if (self::$iblock_properties[strtolower(trim($key))]["PROPERTY_TYPE"] == "L") {
                    $propValueRes = CIBlockProperty::GetPropertyEnum(self::$iblock_properties[strtolower(trim($key))]["ID"]);
                    while($propValue = $propValueRes->GetNext()) {
                        if ($propValue["VALUE"] == trim($value)) {
                            $PROP[self::$iblock_properties[strtolower(trim($key))]["CODE"]] = $propValue["ID"];
                        }
                    }
                } else {
                    $PROP[self::$iblock_properties[strtolower(trim($key))]["CODE"]] = $value;
                }

            } else {
                // Добавление характеристики
                $propertyCode = strtoupper(preg_replace('/[[:punct:]]+/i', '_', self::UrlTranslit($key)));

                if (strlen($propertyCode) > 50) {
                    $propertyCode = substr($propertyCode, 0,50);
                }

                // Проверяем существование свойства
                $checkProperty = CIBlockProperty::GetByID($propertyCode, self::$catalog_iblock)->GetNext();
                if (!$checkProperty) {
                    if (trim($value) == 'Да' || trim($value) == 'да') {
                        $arPropertyFields = Array(
                            "NAME" => $key,
                            "ACTIVE" => "Y",
                            "SORT" => "500",
                            "CODE" => $propertyCode,
                            "PROPERTY_TYPE" => "L",
                            "IBLOCK_ID" => self::$catalog_iblock,
                            "LIST_TYPE" => "C",
                            "MULTIPLE" => "N",
                            "VERSION" => 1
                        );

                        $arPropertyFields["VALUES"][0] = Array(
                            "VALUE" => "Да",
                            "DEF" => "N",
                            "SORT" => "100"
                        );

                        $property_id = $ibp->Add($arPropertyFields);

                        self::$iblock_properties[strtolower(trim($key))] = array(
                            "ID" => $property_id,
                            "PROPERTY_TYPE" => "L",
                            "CODE" => $propertyCode,
                        );


                        $propValueRes = CIBlockProperty::GetPropertyEnum($property_id);
                        while($propValue = $propValueRes->GetNext()) {
                            if ($propValue["VALUE"] == trim($value)) {
                                $value = $propValue["ID"];
                            }
                        }

                        $PROP[$propertyCode] = $value;

                    } else {
                        $arPropertyFields = Array(
                            "NAME" => $key,
                            "ACTIVE" => "Y",
                            "SORT" => "500",
                            "CODE" => $propertyCode,
                            "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                            "IBLOCK_ID" => self::$catalog_iblock,
                            "WITH_DESCRIPTION" => "N",
                        );

                        $property_id = $ibp->Add($arPropertyFields);

                        self::$iblock_properties[strtolower(trim($key))] = array(
                            "ID" => $property_id,
                            "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                            "CODE" => $propertyCode,
                        );


                        $PROP[$propertyCode] = $value;
                    }

                } elseif($checkProperty["PROPERTY_TYPE"] == "L") {
                    $propValueRes = CIBlockProperty::GetPropertyEnum($checkProperty["ID"]);
                    while($propValue = $propValueRes->GetNext()) {
                        if ($propValue["VALUE"] == trim($value)) {
                            $value = $propValue["ID"];
                        }
                    }
                    $PROP[$checkProperty["CODE"]] = $value;
                } else {
                    $PROP[$checkProperty["CODE"]] = $value;
                }
            }
        }

        // Заполняем характеристики для списка товаров
        $listFeatures = $data["ЗАПИСЬ ДЛЯ ИСПОЛЬЗОВАНИЯ В ВЕРТИКАЛЬНЫХ ПЛИТКАХ КАТАЛОГА. ПУСТЫЕ СТРОКИ - НЕ СОЗДАЁМ ХАРАКТЕРИСТИКУ"];
        foreach ($listFeatures AS $key => $value) {

            if (isset(self::$iblock_properties[strtolower(trim("LIST_".$key))])) {
                if (self::$iblock_properties[strtolower(trim("LIST_".$key))]["PROPERTY_TYPE"] == "L") {
                    $propValueRes = CIBlockProperty::GetPropertyEnum(self::$iblock_properties[strtolower(trim("LIST_".$key))]["ID"]);
                    while($propValue = $propValueRes->GetNext()) {
                        if ($propValue["VALUE"] == trim($value)) {
                            $PROP[self::$iblock_properties[strtolower(trim("LIST_".$key))]["CODE"]] = $propValue["ID"];
                        }
                    }
                } else {
                    $PROP[self::$iblock_properties[strtolower(trim("LIST_".$key))]["CODE"]] = $value;
                }

            } else {
                // Добавление характеристики
                $propertyCode = strtoupper(preg_replace('/[[:punct:]]+/i', '_', self::UrlTranslit("LIST_".$key)));
                if (strlen($propertyCode) > 50) {$propertyCode = substr($propertyCode, 0,50);}
                // Проверяем существование свойства
                $checkProperty = CIBlockProperty::GetByID($propertyCode, self::$catalog_iblock)->GetNext();
                if (!$checkProperty) {
                    if (trim($value) == 'Да' || trim($value) == 'да') {
                        $arPropertyFields = Array(
                            "NAME" => $key,
                            "ACTIVE" => "Y",
                            "SORT" => "500",
                            "CODE" => $propertyCode,
                            "PROPERTY_TYPE" => "L",
                            "IBLOCK_ID" => self::$catalog_iblock,
                            "LIST_TYPE" => "C",
                            "MULTIPLE" => "N",
                            "VERSION" => 1
                        );

                        $arPropertyFields["VALUES"][0] = Array(
                            "VALUE" => "Да",
                            "DEF" => "N",
                            "SORT" => "100"
                        );

                        $property_id = $ibp->Add($arPropertyFields);

                        self::$iblock_properties[strtolower(trim("LIST_".$key))] = array(
                            "ID" => $property_id,
                            "PROPERTY_TYPE" => "L",
                            "CODE" => $propertyCode,
                        );


                        $propValueRes = CIBlockProperty::GetPropertyEnum($property_id);
                        while($propValue = $propValueRes->GetNext()) {
                            if ($propValue["VALUE"] == trim($value)) {
                                $value = $propValue["ID"];
                            }
                        }

                        $PROP[$propertyCode] = $value;

                    } else {
                        $arPropertyFields = Array(
                            "NAME" => $key,
                            "ACTIVE" => "Y",
                            "SORT" => "500",
                            "CODE" => $propertyCode,
                            "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                            "IBLOCK_ID" => self::$catalog_iblock,
                            "WITH_DESCRIPTION" => "N",
                        );

                        $property_id = $ibp->Add($arPropertyFields);

                        self::$iblock_properties[strtolower(trim("LIST_".$key))] = array(
                            "ID" => $property_id,
                            "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                            "CODE" => $propertyCode,
                        );


                        $PROP[$propertyCode] = $value;
                    }

                } elseif($checkProperty["PROPERTY_TYPE"] == "L") {
                    $propValueRes = CIBlockProperty::GetPropertyEnum($checkProperty["ID"]);
                    while($propValue = $propValueRes->GetNext()) {
                        if ($propValue["VALUE"] == trim($value)) {
                            $value = $propValue["ID"];
                        }
                    }
                    $PROP[$checkProperty["CODE"]] = $value;
                } else {
                    $PROP[$checkProperty["CODE"]] = $value;
                }
            }
        }

        // Заполняем характеристики для модификаций
        $modFeatures = $data['ТАБЛИЦА СРАВНЕНИЯ ДЛЯ ТАБА "МОДИФИКАЦИИ"'];
        foreach ($modFeatures AS $key => $value) {

            if (isset(self::$iblock_properties[strtolower(trim("MODIF_".$key))])) {
                if (self::$iblock_properties[strtolower(trim("MODIF_".$key))]["PROPERTY_TYPE"] == "L") {
                    $propValueRes = CIBlockProperty::GetPropertyEnum(self::$iblock_properties[strtolower(trim("MODIF_".$key))]["ID"]);
                    while($propValue = $propValueRes->GetNext()) {
                        if ($propValue["VALUE"] == trim($value)) {
                            $PROP[self::$iblock_properties[strtolower(trim("MODIF_".$key))]["CODE"]] = $propValue["ID"];
                        }
                    }
                } else {
                    $PROP[self::$iblock_properties[strtolower(trim("MODIF_".$key))]["CODE"]] = $value;
                }

            } else {
                // Добавление характеристики
                $propertyCode = strtoupper(preg_replace('/[[:punct:]]+/i', '_', self::UrlTranslit("MODIF_".$key)));
                if (strlen($propertyCode) > 50) {$propertyCode = substr($propertyCode, 0,50);}
                // Проверяем существование свойства
                $checkProperty = CIBlockProperty::GetByID($propertyCode, self::$catalog_iblock)->GetNext();
                if (!$checkProperty) {
                    if (trim($value) == 'Да' || trim($value) == 'да') {
                        $arPropertyFields = Array(
                            "NAME" => $key,
                            "ACTIVE" => "Y",
                            "SORT" => "500",
                            "CODE" => $propertyCode,
                            "PROPERTY_TYPE" => "L",
                            "IBLOCK_ID" => self::$catalog_iblock,
                            "LIST_TYPE" => "C",
                            "MULTIPLE" => "N",
                            "VERSION" => 1
                        );

                        $arPropertyFields["VALUES"][0] = Array(
                            "VALUE" => "Да",
                            "DEF" => "N",
                            "SORT" => "100"
                        );

                        $property_id = $ibp->Add($arPropertyFields);

                        self::$iblock_properties[strtolower(trim("MODIF_".$key))] = array(
                            "ID" => $property_id,
                            "PROPERTY_TYPE" => "L",
                            "CODE" => $propertyCode,
                        );


                        $propValueRes = CIBlockProperty::GetPropertyEnum($property_id);
                        while($propValue = $propValueRes->GetNext()) {
                            if ($propValue["VALUE"] == trim($value)) {
                                $value = $propValue["ID"];
                            }
                        }

                        $PROP[$propertyCode] = $value;

                    } else {
                        $arPropertyFields = Array(
                            "NAME" => $key,
                            "ACTIVE" => "Y",
                            "SORT" => "500",
                            "CODE" => $propertyCode,
                            "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                            "IBLOCK_ID" => self::$catalog_iblock,
                            "WITH_DESCRIPTION" => "N",
                        );

                        $property_id = $ibp->Add($arPropertyFields);

                        self::$iblock_properties[strtolower(trim("MODIF_".$key))] = array(
                            "ID" => $property_id,
                            "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                            "CODE" => $propertyCode,
                        );


                        $PROP[$propertyCode] = $value;
                    }

                } elseif($checkProperty["PROPERTY_TYPE"] == "L") {
                    $propValueRes = CIBlockProperty::GetPropertyEnum($checkProperty["ID"]);
                    while($propValue = $propValueRes->GetNext()) {
                        if ($propValue["VALUE"] == trim($value)) {
                            $value = $propValue["ID"];
                        }
                    }
                    $PROP[$checkProperty["CODE"]] = $value;
                } else {
                    $PROP[$checkProperty["CODE"]] = $value;
                }
            }
        }


        // Получение идентификатора вендора
        $vendorItem = CIBlockElement::GetList(
            array("SORT"=>"ASC"),
            array(
                "IBLOCK_ID" => self::$vendors_iblock, 
                array(
                    "LOGIC" => "OR", 
                    "NAME" => $vendor,
                    "PROPERTY_SHORT_NAME" => $vendor
                )
            ),
            false,
            false,
            array("ID", "NAME")
        )->GetNext();
        
        // Добавление вендора
        $PROP["VENDOR_ITEM"] = isset($vendorItem["ID"]) ? $vendorItem["ID"] : "";

        // Добавление артикула
        $PROP["ARTNUMBER"] = $articul;

        // Добавление скороговорки
        $PROP["PATTER"] = $data['КРАТКИЕ ТЕХНИЧЕСКИЕ ХАРАКТЕРИСТИКИ (СЛЕВА ОТ КНОПКИ "КОНФИГУРИРОВАТЬ")']["Скороговорка на карточку"];

        // Добавление отметки об импорте через excel
        $PROP["XLSX_IMPORT"] = 56;

        // Добавление изображений
        $more_photos = array();
        $images = $data["ФОТОГРАФИИ И ВИДЕО ТОВАРА ДЛЯ КТ"];
        foreach($images AS $imageKey => $imageItem) {
            if (file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$imageItem)) {
                $arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$imageItem);
                $more_photos[] = array('VALUE' => $arFile, 'DESCRIPTION' => $imageKey);
            }
        }
        $PROP["MORE_PHOTO"] = $more_photos;


        // Добавление сертификата и изображения сертификата
        $sertFile = isset($data["ПАРТНЁРСКИЙ СЕРТИФИКАТ И ЛОГОТИП ДЛЯ КТ"]["Партнёрский сертификат"]) ? $data["ПАРТНЁРСКИЙ СЕРТИФИКАТ И ЛОГОТИП ДЛЯ КТ"]["Партнёрский сертификат"] : "";
        $sertImage = isset($data["ПАРТНЁРСКИЙ СЕРТИФИКАТ И ЛОГОТИП ДЛЯ КТ"]["Партнёрский логоти"]) ? $data["ПАРТНЁРСКИЙ СЕРТИФИКАТ И ЛОГОТИП ДЛЯ КТ"]["Партнёрский логоти"] : "";

        if (!empty($sertFile) && file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$sertFile)) {
            $PROP["CERTIFICATE_FILE"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$sertFile);
        } else $PROP["CERTIFICATE_FILE"] = "";

        if (!empty($sertImage) && file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$sertImage)) {
            $PROP["CERTIFICATE_IMAGE"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$sertImage);
        } else $PROP["CERTIFICATE_IMAGE"] = "";


        $detailImageName = $data["ДЕТАЛЬНАЯ КАРТИНКА = КАРТИНКА ДЛЯ АНОНСА"]["Детальная картинка"];
        $arFile = array();
        if (file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$detailImageName)) {
            $arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$detailImageName);
        }

        $arFields = Array(
            "IBLOCK_SECTION_ID" => isset($parentCategory) && (int)$parentCategory > 0 ? $parentCategory : false,          // элемент лежит в корне раздела
            "IBLOCK_ID"         => self::$catalog_iblock,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $model,
            "CODE"              => self::UrlTranslit($articul),
            "ACTIVE"            => "Y",            // активен
            "PREVIEW_PICTURE"   => $arFile,
            "PREVIEW_TEXT"      => $data["КРАТКОЕ ОПИСАНИЕ ДЛЯ ГЛАВНОГО ТАБА КАРТОЧКИ ТОВАРА"]["Краткое описание"],
            "PREVIEW_TEXT_TYPE" => "html",
            "DETAIL_PICTURE"    => $arFile,
            "DETAIL_TEXT"       => $data['ПОДРОБНОЕ ОПИСАНИЕ ТОВАРА ДЛЯ ТАБА С ХАРАКТЕРИСТИКАМИ. СМ. ЯЧЕЙКУ "ВЕСЬ ТЕКСТ"']["Весь текст подробного описания"],
            "DETAIL_TEXT_TYPE"  => "html",
            "TAGS"              => $data["ТЕГИ ДЛЯ КТ, РАЗДЕЛИТЕЛИ ЧЕРЕЗ ЗАПЯТЫЕ"]["Теги"],
            "IPROPERTY_TEMPLATES" => array(
                "ELEMENT_META_TITLE"                    => $seo["Шаблон META TITLE"],
                "ELEMENT_META_KEYWORDS"                 => $seo["Шаблон META KEYWORDS"],
                "ELEMENT_META_DESCRIPTION"              => $seo["Шаблон META DESCRIPTION"],
                "ELEMENT_PAGE_TITLE"                    => $seo["Заголовок элемента"],
                "ELEMENT_PREVIEW_PICTURE_FILE_ALT"      => $seo["Шаблон ALT (анонс)"],
                "ELEMENT_PREVIEW_PICTURE_FILE_TITLE"    => $seo["Шаблон TITLE (анонс)"],
                "ELEMENT_PREVIEW_PICTURE_FILE_NAME"     => self::UrlTranslit($seo["Шаблон имени файла (анонс)"]),
                "ELEMENT_DETAIL_PICTURE_FILE_ALT"       => $seo["Шаблон ALT (детальный)"],
                "ELEMENT_DETAIL_PICTURE_FILE_TITLE"     => $seo["Шаблон TITLE (детальный)"],
                "ELEMENT_DETAIL_PICTURE_FILE_NAME"      => self::UrlTranslit($seo["Шаблон имени файла (детальный)"]),
            )

        );

        // Проверка существования товара
        $checkItem = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => self::$catalog_iblock, "PROPERTY_ARTNUMBER" => $articul), false, false, array("ID", "NAME", "PROPERTY_ARTNUMBER"))->GetNext();

        if ($checkItem["ID"]) {

            $PROP["MORE_PHOTO"] = array();
            $PROP["CERTIFICATE_FILE"]["DEL"] = "Y";
            $PROP["CERTIFICATE_IMAGE"]["DEL"] = "Y";

            $arFields["PROPERTY_VALUES"] = $PROP;

            // Обновление продукта
            $be->UPDATE($checkItem["ID"], $arFields);
            $item_id = $checkItem["ID"];

            $returnName = 'Обновлен товар - '.$returnName;

        } else {
            // Добавление продукта

            $item_id = $be->Add($arFields);

            $res = ($item_id>0);
            if(!$res) {
                echo $be->LAST_ERROR;

                echo '<pre>';
                print_r($arFields);
                echo '</pre>';
                exit;

            } else {
                $returnName = 'Добавлен товар - '.$returnName;
            }
        }

        // Получение документации
        $documents = $data["НАПОЛНЕНИЕ ДЛЯ ТАБА \"ДОКУМЕНТАЦИЯ\". КАЖДЫЙ ЭЛЕМЕНТ - ГРУППА ИЗ 5 ЯЧЕЕК, ОПИСЫВАЮЩИХ ЭЛЕМЕНТ"];
        $docs = array();
        $i=0;
        foreach ($documents AS $key => $value) {
            if (strstr($key, 'Категория')) {
                $i++;
            }
            $docs[$i][preg_replace('/_(\d+)/i', '', $key)] = trim($value);
        }


        foreach ($docs AS $key => $doc) {

            $checkDocsCategory = $bs->GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => self::$documents_iblock, "NAME" => $doc["Категория"]), false, array("ID", "NAME"), false)->GetNext();
            if (!$checkDocsCategory["ID"]) {
                // Добавление категории
                $arFields = Array(
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => self::$documents_iblock,
                    "NAME" => $doc["Категория"],
                    "CODE" => self::UrlTranslit($doc["Категория"]),
                    "SORT" => 500,
                );
                $id = $bs->Add($arFields);
                $parentDocCategory = $id;

            } else {
                $parentDocCategory = $checkDocsCategory["ID"];
            }

            $DOC_PROP = array(
                "CATALOG_ITEM" => $item_id,
                "VENDOR_ITEM" => isset($vendorItem["ID"]) ? $vendorItem["ID"] : ""
            );

            if (isset($doc["Название файла"]) && file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$doc["Название файла"])) {
                $DOC_PROP["FILE"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/card_import_files/'.$doc["Название файла"]);
            }

            if (isset($doc["Ссылка для скачивания"])) {
                $DOC_PROP["LINK"] = $doc["Ссылка для скачивания"];
            }



            $arFields = Array(
                "IBLOCK_SECTION_ID" => isset($parentDocCategory) && (int)$parentDocCategory > 0 ? $parentDocCategory : false,          // элемент лежит в корне раздела
                "IBLOCK_ID"         => self::$documents_iblock,
                "PROPERTY_VALUES"   => $DOC_PROP,
                "NAME"              => $doc["Название"],
                "CODE"              => self::UrlTranslit($doc["Название"]),
                "ACTIVE"            => "Y",            // активен
                "PREVIEW_TEXT"      => $doc["Описание"],
                "PREVIEW_TEXT_TYPE" => "html",
            );

            // Проверка существования документа
            $checkDoc = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => self::$documents_iblock, "NAME" => $doc["Название"]), false, false, array("ID", "NAME"))->GetNext();
            if (isset($checkDoc["ID"])) {

                if (isset($DOC_PROP["FILE"]))
                    $DOC_PROP["FILE"]["DEL"] = "Y";

                $catalogItemPropRes = CIBlockElement::GetProperty(self::$documents_iblock, $checkDoc["ID"], array(), array("CODE" => "CATALOG_ITEM"));
                $propItems = array();
                while($prop = $catalogItemPropRes->GetNext()) {
                    $propItems[] = $prop["VALUE"];
                }

                if (!in_array($item_id, $propItems)) {
                    $propItems[] = $item_id;
                }

                $DOC_PROP["CATALOG_ITEM"] = $propItems;
                $arFields["PROPERTY_VALUES"] = $DOC_PROP;

                $be->UPDATE($checkDoc["ID"], $arFields);
            } else {
                $document_id = $be->Add($arFields);
            }


        }

        return $returnName;
    }

    public static function importItemAnalogs($data) {

        self::$iblock_properties = array();
        CModule::includeModule('iblock');
        $bs = new CIBlockSection;
        $be = new CIBlockElement;
        $ibp = new CIBlockProperty;

        $items = array();
        $analogs = array();
        
        foreach ($data AS $itemKey => $itemValue) {
            $item = (array)$itemValue;
            $articul = (array)$item["ИНФОРМАЦИЯ ДЛА АДМИНКИ, КАТАЛОГА И КТ. ВЕНДОР - АДМИНКА+КАТАЛОГ, АРТИКУЛ - АДМИНКА+КАТАЛОГ+КТ, МОДЕЛЬ - КАТАЛОГ"];
            $items[] = $articul["Артикул"];

            $features = (array)$item['АНАЛОГИ, ТАБЛИЦА СРАВНЕНИЯ ХАРАКТЕРИСТИК ДЛЯ ТАБА "АНАЛОГИ"'];

            // Получение товара с данным артикулом
            $res = CIBlockElement::GetList(
                array("SORT"=>"ASC"),
                array("IBLOCK_ID" => self::$catalog_iblock, "PROPERTY_ARTNUMBER" => $articul["Артикул"]),
                false,
                false,
                array("ID", "NAME", "IBLOCK_ID")
            );

            if ($product = $res->GetNext()) {
                $analogs[] = $product;

                $PROP = array();
                // Заполнение характеристик аналогов
                foreach ($features AS $key => $value) {

                    if (isset(self::$iblock_properties[strtolower(trim("ANALOG_".$key))])) {
                        if (self::$iblock_properties[strtolower(trim("ANALOG_".$key))]["PROPERTY_TYPE"] == "L") {
                            $propValueRes = CIBlockProperty::GetPropertyEnum(self::$iblock_properties[strtolower(trim("ANALOG_".$key))]["ID"]);
                            while($propValue = $propValueRes->GetNext()) {
                                if ($propValue["VALUE"] == trim($value)) {
                                    $PROP[self::$iblock_properties[strtolower(trim("ANALOG_".$key))]["CODE"]] = $propValue["ID"];
                                }
                            }
                        } else {
                            $PROP[self::$iblock_properties[strtolower(trim("ANALOG_".$key))]["CODE"]] = $value;
                        }

                    } else {
                        // Добавление характеристики
                        $propertyCode = strtoupper(preg_replace('/[[:punct:]]+/i', '_', self::UrlTranslit("ANALOG_".$key)));
                        if (strlen($propertyCode) > 50) {$propertyCode = substr($propertyCode, 0,50);}
                        // Проверяем существование свойства
                        $checkProperty = CIBlockProperty::GetByID($propertyCode, self::$catalog_iblock)->GetNext();
                        if (!$checkProperty) {
                            if (trim($value) == 'Да' || trim($value) == 'да') {
                                $arPropertyFields = Array(
                                    "NAME" => $key,
                                    "ACTIVE" => "Y",
                                    "SORT" => "500",
                                    "CODE" => $propertyCode,
                                    "PROPERTY_TYPE" => "L",
                                    "IBLOCK_ID" => self::$catalog_iblock,
                                    "LIST_TYPE" => "C",
                                    "MULTIPLE" => "N",
                                    "VERSION" => 1
                                );

                                $arPropertyFields["VALUES"][0] = Array(
                                    "VALUE" => "Да",
                                    "DEF" => "N",
                                    "SORT" => "100"
                                );

                                $property_id = $ibp->Add($arPropertyFields);

                                self::$iblock_properties[strtolower(trim("MODIF_".$key))] = array(
                                    "ID" => $property_id,
                                    "PROPERTY_TYPE" => "L",
                                    "CODE" => $propertyCode,
                                );


                                $propValueRes = CIBlockProperty::GetPropertyEnum($property_id);
                                while($propValue = $propValueRes->GetNext()) {
                                    if ($propValue["VALUE"] == trim($value)) {
                                        $value = $propValue["ID"];
                                    }
                                }

                                $PROP[$propertyCode] = $value;

                            } else {
                                $arPropertyFields = Array(
                                    "NAME" => $key,
                                    "ACTIVE" => "Y",
                                    "SORT" => "500",
                                    "CODE" => $propertyCode,
                                    "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                                    "IBLOCK_ID" => self::$catalog_iblock,
                                    "WITH_DESCRIPTION" => "N",
                                );

                                $property_id = $ibp->Add($arPropertyFields);

                                self::$iblock_properties[strtolower(trim("MODIF_".$key))] = array(
                                    "ID" => $property_id,
                                    "PROPERTY_TYPE" => is_string($value) ? "S" : "N",
                                    "CODE" => $propertyCode,
                                );


                                $PROP[$propertyCode] = $value;
                            }

                        } elseif($checkProperty["PROPERTY_TYPE"] == "L") {
                            $propValueRes = CIBlockProperty::GetPropertyEnum($checkProperty["ID"]);
                            while($propValue = $propValueRes->GetNext()) {
                                if ($propValue["VALUE"] == trim($value)) {
                                    $value = $propValue["ID"];
                                }
                            }
                            $PROP[$checkProperty["CODE"]] = $value;
                        } else {
                            $PROP[$checkProperty["CODE"]] = $value;
                        }
                    }
                }

                CIBlockElement::SetPropertyValuesEx(
                    $product["ID"],
                    $product["IBLOCK_ID"],
                    $PROP,
                    array()
                );
            }
        }

        // Заполнение характеристики аналоги
        foreach ($analogs AS $analogKey => $analogValue) {
            $ids = array();
            foreach ($analogs AS $analogKey2 => $analogValue2) {
                if ($analogValue2["ID"] != $analogValue["ID"]){
                    $ids[] = $analogValue2["ID"];
                }
            }

            CIBlockElement::SetPropertyValuesEx(
                $analogValue["ID"],
                $analogValue["IBLOCK_ID"],
                array("ITEM_ANALOG" => $ids),
                array()
            );
        }

        return $analogs;

    }

    /**
     * Получение списка ибранных товаров
     * @param $userId
     * @return array
     */
    public static function getFavoriteItems($userId) {

        $result = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => 31, "MODIFIED_USER_ID" => $userId), false, false, array("ID", "IBLOCK_ID", "NAME"));
        $favorite_items = array();
        $favorite_list_items = array();
        while($list = $result->GetNext()) {
            $propResult = CIBlockElement::GetProperty($list["IBLOCK_ID"], $list["ID"], array(), array("CODE" => "ITEMS"));
            $userList = array();
            while($prop = $propResult->GetNext()) {
                if (!empty($prop["VALUE"]))
                    $userList[] = $prop["VALUE"];
            }

            $favorite_list_items[$list["ID"]] = array(
                "ID" => $list["ID"],
                "NAME" => $list["NAME"],
                "ITEMS" => $userList
            );

            $favorite_items = array_merge($favorite_items, $userList);
        }
        $favorite_items = array_unique($favorite_items);


        $result = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => CATALOG_IBLOCK, "ID" => $favorite_items), false, false, array("ID", "NAME"));
        while($item = $result->GetNext()) {
            foreach ($favorite_list_items AS &$listItem) {
                foreach ($listItem["ITEMS"] AS &$arItem) {
                    if ($arItem == $item["ID"]) {
                        $arItem = $item;
                    }
                }
            }
        }

        $data = array(
            "COUNT" => count($favorite_items),
            "FAVORITE_ITEMS" => $favorite_items,
            "FAVORITE_LISTS" => $favorite_list_items,
        );

        unset($favorite_items);
        unset($favorite_list_items);

        return $data;
    }


    /**
     * Постоение групп свойст товара
     * @param $importFeatures
     */
    public static function buildFeaturesGroups($importFeatures) {

        // Синхронизация свойств
        self::elementPropertiesHLSync();

        CModule::IncludeModule('highloadblock');
        CModule::includeModule('iblock');

        $bs = new CIBlockSection;
        $be = new CIBlockElement;
        $ibp = new CIBlockProperty;

        $itemsFeatures = array();

        if (is_object($importFeatures)) {
            $importFeatures = (array)$importFeatures;
            foreach ($importFeatures AS $key => $value) {
                $importFeatures[$key] = (array)$value;
            }
        }


        // Айди нашего хайлоад блока
        $hlblock_id = self::$productProperties_HLblock;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
        {
            ShowError('404');
            return;
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $i = 0;
        foreach ($importFeatures AS $item => $features) {
            $i++;
            // Проверка элемента
            $itemCheck = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => self::$catalog_features_groups_iblock, "NAME" => $item), false, false, array("ID", "NAME"))->GetNext();
            if (isset($itemCheck["ID"])) {
                $item_id = $itemCheck["ID"];
            } else {
                // Создаем элемент
                $arFields = Array(
                    "IBLOCK_ID"         => self::$catalog_features_groups_iblock,
                    "NAME"              => $item,
                    "CODE"              => self::UrlTranslit($item),
                    "ACTIVE"            => "Y",            // активен
                    "SORT"              => 10*$i,
                );
                $item_id = $be->Add($arFields);
            }

            $properties = array();
            foreach ($features AS $feature) {
                $rsData = $entity_data_class::getList(array("select" => array("*"), "order" => array("ID" => "ASC"), "filter" => array('UF_PROPERTY_NAME' => $feature)))->Fetch();
                if (isset($rsData["ID"])) {
                    $properties[] = $rsData["UF_XML_ID"];
                }
            }

            $itemPropRes = CIBlockElement::GetProperty(self::$catalog_features_groups_iblock, $item_id, array(), array("CODE" => "ITEM_FEATURE"));
            $itemProperties = array();
            while($prop = $itemPropRes->GetNext()) {
                $itemProperties[] = $prop["VALUE"];
            }

            $properties = array_unique(array_merge($properties, $itemProperties));

            CIBlockElement::SetPropertyValuesEx($item_id, self::$catalog_features_groups_iblock, array("ITEM_FEATURE" => $properties), array());
            $itemsFeatures[] = $item_id;
        }

        return $itemsFeatures;
    }




    /**
     * Редиректы со старого сайта на новый
     */
    public static function uaRedirect() {

        if ($_SERVER["REQUEST_URI"] == "/ru?track=artcl_mrkt_src" || $_SERVER["REQUEST_URI"] == '/bitrix/urlrewrite.php?track=artcl_mrkt_src') {
            LocalRedirect('https://'.$_SERVER["HTTP_HOST"].'/knowbase/stati/718/', false, '301 Moved permanently');
            exit;
        }
        
        if (strstr($_SERVER["REQUEST_URI"], '/knowbase/')) {
            $_SERVER["REQUEST_URI"] = str_replace('/knowbase/', '/info/', $_SERVER["REQUEST_URI"]);
            LocalRedirect($_SERVER["REQUEST_URI"], false, '301 Moved permanently');
            exit;
        }


        if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != 'on') {
            LocalRedirect('https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"], false, '301 Moved permanently');
            exit;
        }

        if (strstr($_SERVER["QUERY_STRING"], 'getfilter')) {
            $_SERVER["REQUEST_URI"] = preg_replace('/^([^\?]+)\?(.+)$/', '$1', $_SERVER["REQUEST_URI"]);
            LocalRedirect($_SERVER["REQUEST_URI"], false, '301 Moved permanently');
            exit;
        }

        CModule::IncludeModule('highloadblock');

        // Айди нашего хайлоад блока
        $hlblock_id = 10;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
            return false;

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();


        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('UF_OLD_URL' => $_SERVER["REQUEST_URI"])
        ));

        $arData = $rsData->Fetch();

        $queryString = false;
        if (!isset($arData["UF_NEW_URL"]) || empty($arData["UF_NEW_URL"])) {
            $queryString = true;
            $currentUrl = preg_replace('/^([^\?]+)\?(.*)$/', '$1', $_SERVER["REQUEST_URI"]);

            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array("ID" => "ASC"),
                "filter" => array('UF_OLD_URL' => $currentUrl)
            ));

            $arData = $rsData->Fetch();

        }

        if (isset($arData["UF_NEW_URL"]) && !empty($arData["UF_NEW_URL"])) {


            if ($queryString) {
                $tmp = explode('&', $_SERVER["QUERY_STRING"]);
                foreach ($tmp AS $key => $value) {
                    if (strstr($value, 'country') || strstr($value, 'region') || strstr($value, 'producer')) {
                        unset($tmp[$key]);
                    }
                }
                $_SERVER["QUERY_STRING"] = implode('&', $tmp);
                $arData["UF_NEW_URL"] .= (isset($_SERVER["QUERY_STRING"]) && !empty($_SERVER["QUERY_STRING"])) ? '?'.$_SERVER["QUERY_STRING"] : '';
            }

            LocalRedirect($arData["UF_NEW_URL"], false, '301 Moved permanently');
        }

        return false;
    }




    /**
     * Выгрузка структуры каталога
     * @param int $parentCategory
     * @param string $active
     * @param int $iblock_id
     * @return array
     */
    public static function exportCategoriesTree($parentCategory = 0, $active = "Y", $iblock_id = 2) {

        $res = CIBlockSection::GetList(array("LEFT_MARGIN"=>"ASC"), array("IBLOCK_ID" => $iblock_id, "ACTIVE" => $active, "SECTION_ID" => $parentCategory, "!ID" => 9440), false, array("ID", "NAME", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "CODE"), false);
        $categories = array();
        while($cat = $res->GetNext()) {
            $categories[] = $cat;
            if ($cat["LEFT_MARGIN"] != $cat["RIGHT_MARGIN"]-1) {
                $subcat = self::exportCategoriesTree($cat["ID"], $active, $iblock_id);
                $categories = array_merge($categories, $subcat);
            }
        }

        return $categories;
    }

    /**
     * Получение массива элементов из выюорки GetList
     * @param CIBlockResult $res
     * @param bool $keyID
     * @return array
     */
    public static function resultToArray(&$res, $keyID = false)
    {
        $items = array();
        while($item = $res->GetNext()) {
            if ($keyID) {
                $items[$item["ID"]] = $item;
            } else {
                $items[] = $item;
            }
        }
        return $items;
    }

    /**
     * Добавление данных в HL блок
     * @param $hlblock_id
     * @param $data
     */
    public static function addHLData($hlblock_id, $data) {

        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $entity_data_class::add($data);

    }


    /**
     * Получение хеш для корзины пользователя
     * @param $user_id
     * @param $fuser_id
     * @return bool|string
     */
    public static function getCartHash($user_id, $fuser_id)
    {
        CModule::IncludeModule('highloadblock');
        global $DB;

        if ((int)$fuser_id == 0)
            return false;

        $hash = md5($user_id."-".$fuser_id);

        // Айди нашего хайлоад блока
        $hlblock_id = 14;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
            return false;

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('UF_CART_HASH' => $hash)
        ));

        if ($rsData->Fetch()) {
            return $hash;
        }


        $entity_data_class::add(array(
            "UF_USER_ID" => (int)$user_id,
            "UF_FUSER_ID" => (int)$fuser_id,
            "UF_CART_HASH" => $hash,
        ));

        return $hash;
    }

    /**
     * Получение ID покупателя по значению HASH
     * @param $hash
     * @return bool
     */
    public static function getFuserByHash($hash) {
        CModule::IncludeModule('highloadblock');
        global $DB;

        if ($hash == "")
            return false;

        // Айди нашего хайлоад блока
        $hlblock_id = 14;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock))
            return false;

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('UF_CART_HASH' => $hash)
        ));

        $data = $rsData->Fetch();
        if ($data) {
            return $data;
        }

        return false;
    }


    /**
     * Удаление елементов инфоблока
     * @param $iblock_id
     * @param int $limit
     */
    public static function softIblockElementsDelete($iblock_id, $limit = 10) {
        CModule::includeModule('iblock');
        $be = new CIBlockElement;

        $res = CIBlockElement::GetList(
            array("SORT"=>"ASC"),
            array("IBLOCK_ID" => $iblock_id),
            false,
            array("nTopCount"=>$limit),
            array("ID")
        );

        while($item = $res->GetNext()) {
            CIBlockElement::Delete($item["ID"]);
        }
    }



    /**
     * Кастомный вывод битриксовской шапки
     * @param bool $bXhtmlStyle
     * @param bool $showCss
     * @param bool $showJs
     */
    public static function ShowHeadCustom($bXhtmlStyle = true, $showCss = false, $showJs = false)
    {
        global $APPLICATION;
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'"'.($bXhtmlStyle? ' /':'').'>'."\n";

        $APPLICATION->ShowMeta("robots", false, $bXhtmlStyle);
        $APPLICATION->ShowMeta("keywords", false, $bXhtmlStyle);
        $APPLICATION->ShowMeta("description", false, $bXhtmlStyle);
        $APPLICATION->ShowLink("canonical", null, $bXhtmlStyle);

        $APPLICATION->ShowHeadStrings();
        $APPLICATION->ShowHeadScripts();

        if ($showCss)
            $APPLICATION->ShowCSS(true, $bXhtmlStyle);

//        if ($showJs)
//            $APPLICATION->ShowHeadScripts();

    }

    /**
     * Вывод стилей
     * @param $path
     * @param bool $print
     * @return bool|string
     */
    public static function insertStyles($path, $print = true) {
        if (file_exists($path)) {
            $data = file_get_contents($path);
            $data = '<style>'.str_replace('@charset "UTF-8";', '', $data).'</style>';

            if ($print) {
                echo $data;
                return true;
            } else {
                return $data;
            }
        }
        return false;
    }



    /**
     * Сохранение контента страницы
     * @param $page_link
     * @param $page_data
     * @return bool
     */
    public static function setPageData($page_link, $page_data) {
        CModule::IncludeModule('highloadblock');

        // Айди нашего хайлоад блока
        $hlblock_id = 5;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('UF_LINK' => $page_link)
        ))->Fetch();

        if ($rsData) {
            $entity_data_class::update($rsData["ID"], array(
                "UF_CONTENT" => $page_data,
                "UF_DATE_UPDATE" => date("d.m.Y H:i:s")
            ));
            return true;
        }

        return false;
    }


    /**
     * Получение токена
     * @param $token_id
     * @return mixed
     */
    public static function getPageData($page_link, $cache_time = 86400) {

        CModule::IncludeModule('highloadblock');

        // Айди нашего хайлоад блока
        $hlblock_id = 5;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array('UF_LINK' => $page_link, "<UF_DATE_UPDATE" => date("d.m.Y H:i:s", time()-$cache_time))
        ))->Fetch();

        return $rsData;

    }


    public static function exportYML($iblock_id) {

        // Получение категорий инфоблока
        $sectionRes = CIBlockSection::GetList(
            array("LEFT_MARGIN"=>"ASC"),
            array("IBLOCK_ID" => $iblock_id, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"),
            false,
            array("ID", "NAME", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "IBLOCK_SECTION_ID"),
            false
        );
        $sections = array();
        $sections_ids = array();
        while($section = $sectionRes->GetNext()) {
            $sections[] = $section;
            $sections_ids[] = $section["ID"];
        }

        // Получение товаров
        $res = CIBlockElement::GetList(
            array("SORT"=>"ASC"),
            array("IBLOCK_ID" => $iblock_id, "ACTIVE" => "Y", "SECTION_ID" => $sections_ids),
            false,
            false,
            array("ID", "NAME", "PREVIEW_TEXT", "DETAIL_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "SECTION_ID")
        );
        $items = array();

        while($item = $res->GetNext()) {
            $item["DETAIL_TEXT"] = preg_replace('/<div(\s*)class="block-head">(.+)<\/div>/Usi', '', $item["DETAIL_TEXT"]);
            $item["DETAIL_TEXT"] = strip_tags($item["DETAIL_TEXT"]);
            $item["DETAIL_TEXT"] = preg_replace('/\s+?/Usi', ' ', trim($item["DETAIL_TEXT"]));
            $item["DETAIL_TEXT"] = self::makeShortText($item["DETAIL_TEXT"], 175);

            unset($item["~DETAIL_TEXT"]);

            $image = $item["DETAIL_PICTURE"] ? $item["DETAIL_PICTURE"] : $item["PREVIEW_PICTURE"];
            if ($image) {
                $item["PICTURE"] = self::getResizeImage($image, 1500, 0);
            }

            $items[] = $item;
        }

        $yml = '<yml_catalog date="'.date('Y-m-d H:i').'">
    <shop>
        <name>Немецкие кухни LEICHT</name>
        <company>LEICHT</company>
        <url>https://www.leicht.ru/</url>
        <currencies>
            <currency id="RUR" rate="1" plus="0"/>
        </currencies>
        <categories>';
        foreach ($sections AS $section)
            $yml .= '
            <category id="'.$section["ID"].'"'.((int)$section["IBLOCK_SECTION_ID"] > 0 ? '  parentId="'.$section["IBLOCK_SECTION_ID"].'"' : '').'>'.$section["NAME"].'</category>';
        $yml .= '
        </categories>
        <offers>';
        foreach ($items AS $item)
            $yml .= '
            <offer id="'.$item["ID"].'" available="true">
                <url>https://www.leicht.ru'.$item["DETAIL_PAGE_URL"].'</url>
                
                <currencyId>RUR</currencyId>
                <categoryId type="Own">'.$item["IBLOCK_SECTION_ID"].'</categoryId>
                '.(isset($item["PICTURE"]) ? '<picture>https://www.leicht.ru'.$item["PICTURE"]["SRC"].'</picture>' : '').'
                <typePrefix>Кухня</typePrefix>
                <vendor>LEICHT</vendor>
                <name>'.$item["NAME"].'</name>
                <description>
                '.$item["DETAIL_TEXT"].'
                </description>
                <manufacturer_warranty>true</manufacturer_warranty>
                <country_of_origin>Германия</country_of_origin>
            </offer>';
        $yml .= '
        </offers>
    </shop>
</yml_catalog>';


        $xmlFile = $_SERVER['DOCUMENT_ROOT'].'/yml_catalog.xml';
        if (is_writeable($xmlFile)) {
            if (($fp = fopen($xmlFile, "w+")) !== false) {
                fwrite($fp, $yml);
                fclose($fp);
            }
        }

    }


    /**
     * Логирование операции
     * @param $hlblock_id
     * @param $data
     */
    public static function log($hlblock_id, $data) {

        CModule::IncludeModule('highloadblock');

        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $entity_data_class::add($data);

    }




    /* ----------------------------  PRIVATE FUNCTIONS ----------------------------------------*/


    /**
     * ОБработка ссылок в анонсе и тексте перед сохранением (для ссылок ведущих на внешние сайты добавляем nofollow и target="_blanc")
     * @param $text
     * @return string
     */
    private static function __linkTextHandler($text) {

        $text = str_replace('rel="nofollow"', '', $text);
        $text = str_replace('target="_blank"', '', $text);
        $text = preg_replace('/<\!--noindex--><a(.+)\/a><\!--\/noindex-->/Usi', '<a$1/a>', $text);
        $text = preg_replace('/<a(.*)href=("|\')http([^"\']+)("|\')([^>]*)>([^<]+)<\/a>/Usi', '<!--noindex--><a$1href="http$3"$5 rel="nofollow" target="_blank">$6</a><!--/noindex-->', $text);
        $text = preg_replace('/\s+/Usi', ' ', $text);

        return $text;
    }


    /**
     * ОБработка вставок видео в анонсе и тексте перед сохранением
     * @param $arFields
     */
    private static function __videoWrapHandler(&$arFields) {

//        $arFields["DETAIL_TEXT"] = preg_replace('/<span(.*?)src=("|\')([^"\']+)("|\')([^>]*)>(.*?)<\/span>/Usi', '<div class="video-wrap"><iframe$1src="$3"$5>$6</iframe></div>', $arFields["DETAIL_TEXT"]);
    }







    /* ---------------------------  BITRIX HANDLERS -------------------------------------*/


    /**
     * Обработчик события
     * @param $arFields
     */
    function OnBeforeIBlockElementAddHandler(&$arFields)
    {


        // Обработка ссылок
        $arFields["PREVIEW_TEXT"] = self::__linkTextHandler($arFields["PREVIEW_TEXT"]);
        $arFields["DETAIL_TEXT"] = self::__linkTextHandler($arFields["DETAIL_TEXT"]);

        // Обработка вставок видео
        self::__videoWrapHandler($arFields);

        if ($arFields["IBLOCK_ID"] == 9) {
            self::__companyIblockBeforeAdd($arFields);
        }
        
    }



    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        // Обработка ссылок
        $arFields["PREVIEW_TEXT"] = self::__linkTextHandler($arFields["PREVIEW_TEXT"]);
        $arFields["DETAIL_TEXT"] = self::__linkTextHandler($arFields["DETAIL_TEXT"]);

        // Обработка вставок видео
        self::__videoWrapHandler($arFields);


        if ($arFields["IBLOCK_ID"] == 9) {
            self::__companyIblockBeforeUpdate($arFields);
        }
    }


    // создаем обработчик события "OnBeforeIBlockElementDelete"
    function OnBeforeIBlockElementDeleteHandler($ID)
    {

    }




    /**
     * обработчик события "OnBeforeIBlockSectionUpdateHandler" до обновления категории
     * @param $arFields
     */
    function OnBeforeIBlockSectionUpdateHandler(&$arFields)
    {


    }

    /**
     * обработчик события "OnAfterIBlockSectionUpdate" после обновления категории
     * @param $arFields
     */
    function OnAfterIBlockSectionUpdateHandler(&$arFields)
    {

        
    }
    
    
    
}