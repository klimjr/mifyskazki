<?

define("CATALOG_IBLOCK", 2);
define("SERVICE_IBLOCK", 5);
define("PO_IBLOCK", 30);
define("VENDORS_IBLOCK", 6);

define("PREFIX_PATH_404", "/404.php");

AddEventHandler("main", "OnAfterEpilog", "Prefix_FunctionName");
function Prefix_FunctionName() {
	global $APPLICATION;
	
	// Check if we need to show the content of the 404 page
	if (!defined('ERROR_404') || ERROR_404 != 'Y') {
		return;
	}

	// Display the 404 page unless it is already being displayed
	if ($APPLICATION->GetCurPage() != PREFIX_PATH_404) {
		header('X-Accel-Redirect: '.PREFIX_PATH_404);
		exit();
	}
}



CModule::AddAutoloadClasses(
	'',
	array(
		'CFileWater' => '/local/classes/CFileWater.class.php',
		'SelfFunctions' => '/local/classes/selffunctions.class.php',
		'Schedule' => '/local/classes/schedule.class.php',
		'Curl' => '/local/classes/class.curl.php',
		'PHPExcel' => '/local/classes/PHPExcel.php',
		'PHPExcel_IOFactory' => '/local/classes/PHPExcel/IOFactory.php',

        'SiteParseApi' => '/local/classes/SiteParseApi.class.php',
        'HoboboParseApi' => '/local/classes/HoboboParseApi.class.php',
        'DenvistoriiParseApi' => '/local/classes/DenvistoriiParseApi.class.php',
	)
);


AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("SelfFunctions", "OnBeforeIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("SelfFunctions", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", Array("SelfFunctions", "OnBeforeIBlockElementDeleteHandler"));

AddEventHandler("iblock", "OnAfterIBlockSectionUpdate", Array("SelfFunctions", "OnBeforeIBlockSectionUpdateHandler"));


/**
 * Агент - Загрузка информации о записях
 * @return string
 */
function hoboboloadItemsInfo()
{
    $data = new HoboboParseApi('http://www.hobobo.ru', 37);
    $data->loadItemsInfo();

    return "hoboboloadItemsInfo();";
}


/**
 * Агент - Загрузка информации о записях с сайта denvistorii.ru
 * @return string
 */
function denvistoriiloadItemsInfo()
{
    $data = new denvistoriiParseApi('https://denvistorii.ru', 19);
    $data->loadItemsInfo();

    return "denvistoriiloadItemsInfo();";
}






?>