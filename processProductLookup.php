<?php
if(isset($_POST['omsid'])){
    $omsid = $_POST['omsid'];
    //get home depot api url data:
    $url = 'http://origin.api.homedepot.com/ProductInfo/v2/products/sku?itemId=' . $omsid . '&key=tRXWvUBGuAwEzFHScjLw9ktZ0Bw7a335&type=json';
    $json = file_get_contents($url);
    echo $json;
}
?>
