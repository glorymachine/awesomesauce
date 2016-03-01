<?php
	
if(isset($_POST['imgArray'])){
	$imageUrlArray = $_POST['imgArray'];
    $omsid = $_POST['omsid'];
    $i = 1;
	foreach($imageUrlArray as $imageUrl){
            $zipArrayItem = array(
			'omsid' => $omsid . '-' . $i++,
			'url' => $imageUrl
            );
            $zipArray[] = $zipArrayItem;
	}//end foreach
    
    print_r($zipArray);
    
	if(!empty($zipArray)){
		$zip = new ZipArchive;
		$temp = tempnam(sys_get_temp_dir(), 'zip');
		$res = $zip->open($temp, ZipArchive::CREATE);
		if ($res === TRUE) {                      
			foreach($zipArray as $z){
				$zip->addFromString($z['omsid'] . '.jpg', file_get_contents($z['url']));
			}//end addFromString foreach
			$zip->close();
			header('HTTP/1.0 200 OK');
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=TheHomeDepot-' . time() . '.zip');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp));
			ob_clean();
			flush();
			readfile($temp);            
			unlink($temp);
			exit();
		}//end if
	}//end if
}// end if

?>