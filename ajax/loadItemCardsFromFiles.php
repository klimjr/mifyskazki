<?
set_time_limit (1000);
ini_set('memory_limit', '256M');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


$dirPath = SelfFunctions::getDirInfo($_SERVER["DOCUMENT_ROOT"].'/upload/cards_upload/');

if (count($dirPath) > 0) {

    foreach ($dirPath AS $file_name) {
        $filepath = $_SERVER["DOCUMENT_ROOT"].'/upload/cards_upload/'.$file_name;
        $tmp = file_get_contents($filepath);
        $data = json_decode($tmp);

        $item = SelfFunctions::importItem($data);

        unlink($_SERVER["DOCUMENT_ROOT"].'/upload/cards_upload/'.$file_name);
        $count_files = count($dirPath) - 1;

        $res = array(
            "IMPORT" => true,
            "IMPORT_NAME" => $item,
            "COUNT_FILES" => $count_files,
        );

        echo json_encode($res);
        exit;
    }

} else {

    $res = array(
        "IMPORT" => false,
        "IMPORT_NAME" => "",
        "COUNT_FILES" => 0
    );

    echo json_encode($res);
    exit;

}

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>