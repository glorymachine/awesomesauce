<?php
if(isset($_POST['imgArray'])){
	//set the session variables
	$imageUrlArray = $_POST['imgArray'];
  $omsid = $_POST['omsid'];

	foreach($imageUrlArray as $imageUrl){
    $zipArrayItem = array(
			'omsid' => $omsid . '-',
			'url' => $imageUrl['url'],
			'size' => $imageUrl['size'],
			'count' => $imageUrl['count']
    );
  	$zipArray[] = $zipArrayItem;
	}//end foreach

	if(!empty($zipArray)){
		$zip = new ZipArchive;
		$temp = tempnam(sys_get_temp_dir(), $omsid);
		$res = $zip->open($temp, ZipArchive::CREATE);
		if ($res === TRUE) {
			foreach($zipArray as $z){
				$theURL = $z['url'];
				$imageSize = @getimagesize($theURL);
				if($imageSize === FALSE){
					$noImageURL = 'http://localhost/images/noimage.gif';
					$zip->addFromString($z['omsid'] . $z['size'] . '-' . $z['count'] . '.gif', file_get_contents($noImageURL));
				}else{
					$zip->addFromString($z['omsid'] . $z['size'] . '-' . $z['count'] . '.jpg', file_get_contents($theURL));
				}
			}//end addFromString foreach
			$zip->close();
			header('HTTP/1.0 200 OK');
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp));
			ob_clean();
			flush();
			readfile($temp);
			unlink($temp);
		}//end if
	}//end if
}// end if
?>
