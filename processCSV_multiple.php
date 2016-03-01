<?php
if(isset($_POST['omsid'])){
    $omsid = $_POST['omsid'];
    $jsonArray = array();
    foreach($omsid as $o){
        //get home depot api url data:
        $url = 'http://origin.api.homedepot.com/ProductInfo/v2/products/sku?itemId=' . $o. '&key=tRXWvUBGuAwEzFHScjLw9ktZ0Bw7a335&type=json';
        $urlDecode = json_decode(file_get_contents($url));
        $jsonArray[] = array('omsid'=>$o,'data'=>$urlDecode);
    }
    echo json_encode($jsonArray);
}   
?>