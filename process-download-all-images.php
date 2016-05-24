<?php
error_reporting(0);
if(isset($_POST['omsid'])){
    $omsid = trim($_POST['omsid']);
    //get home depot api url data:
    $url = 'http://origin.api.homedepot.com/ProductInfo/v2/products/sku?itemId=' . $omsid . '&key=tRXWvUBGuAwEzFHScjLw9ktZ0Bw7a335&type=json';
    $json = file_get_contents($url);

    if($json === false){
        header('HTTP/1.0 404 Not found');
        exit;
    }else{
        echo $json;
    }
}
?>
