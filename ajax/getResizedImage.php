<? include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if (isset($_REQUEST["id"]) && (int)$_REQUEST["id"] > 0) {

    $file = CFile::GetFileArray((int)$_REQUEST["id"]);


    if ($file["WIDTH"] < $_REQUEST["width"] || $file["HEIGHT"] < $_REQUEST["height"]) {
        if ($file["WIDTH"] < $_REQUEST["width"] && $file["HEIGHT"] < $_REQUEST["height"]) {

            if ($file["WIDTH"]/$file["HEIGHT"] <= $_REQUEST["width"]/$_REQUEST["height"]) {
                $k = $_REQUEST["width"]/$_REQUEST["height"];
                $w = $file["WIDTH"];
                $h = round($file["WIDTH"]/$k);
            } else {
                $k = $_REQUEST["height"]/$_REQUEST["width"];
                $w = round($file["HEIGHT"]/$k);
                $h = $file["HEIGHT"];
            }

        } elseif ($file["WIDTH"] < $_REQUEST["width"]) {
            $w = $file["WIDTH"];
            $h = round($file["WIDTH"]*$file["HEIGHT"]/(int)$_REQUEST["width"]);
        } else {
            $w = $_REQUEST["height"] > 0 ? round($_REQUEST["width"]*$file["HEIGHT"]/$_REQUEST["height"]) : $file["WIDTH"];
            $h = $file["HEIGHT"];
        }
    } else {
        $w = (int)$_REQUEST["width"];
        $h = (int)$_REQUEST["height"] > 0 ? (int)$_REQUEST["height"] : round((int)$_REQUEST["width"]*$file["HEIGHT"]/$file["WIDTH"]);
    }




    // Ресайз изображения
    if ($resizeFile = CFile::ResizeImageGet($file["ID"], array("width" => $w, "height" => $h), BX_RESIZE_IMAGE_EXACT, true, false, false, 60)) {
        echo json_encode(array(
            'SRC' => $resizeFile['src'],
            'WIDTH' => $w,
            'HEIGHT' => $h,
        ));
    }
    
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); ?>