<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мифы и Сказки");

$data = new HoboboParseApi('http://www.hobobo.ru', 37);

// Добавление списка категорий
//$data->getListSections('/pesni/');

// Добавление информации о категориях и списках элементов
//$data->addItemsListAndSectionData();

// Добавление списка записей со страницы категории
//$data->getSectionList('/mify/mify-drevnej-rusi/', 6);

// Загрузка информации о записи
$data->loadItemsInfo();


?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>