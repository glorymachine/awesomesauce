<?php
require_once 'core/OpenGraph.php';
if($_POST['submitOSMIDs'] == "true" && !empty($_POST['omsidNum'])){
	$theIDs = str_replace("\r\n", ', ', $_POST['omsidNum']);
	$idArray = explode(', ', $theIDs); //split string into array that are separated by ,
	
	foreach($idArray as $id){
		$url = 'http://www.homedepot.com/p/' . $id;
		$headers = get_headers($url);
		$headers = array_reverse($headers);
		foreach($headers as $header) {
			if (strpos($header, 'Location: ') === 0) {
				$url = str_replace('Location: ', '', $header);
				break;
			}//end if
		}//end foreach $idArray
		$gURL = substr_replace($url, '', -44);
		$graph = OpenGraph::fetch($gURL);
		$graphArray[] = OpenGraph::fetch($gURL);
		$image =  $graph->image;
		//--- get 1000px version of image to zip
		$searchArray = array('/300/','300.jpg');//items to search
		$replaceArray = array('/1000/', '1000.jpg');//items to replace search with
		if(!empty($image)){
            $hugeUrl = str_replace($searchArray, $replaceArray, $image);//if image is found, creates URL with 1000 instead of 300
            $zipArrayItem = array(
			'omsid' => $id,
			'url' => $hugeUrl
            );
            $zipArray[] = $zipArrayItem;
        }else if(empty($image)){
            $noImage[] = $id;
            $noImageUrl = 'http://localhost/images/noimage.gif';
            $zipArrayItem = array(
			'omsid' => $id . '-url-unresolved',
			'url' => $noImageUrl
            );
           $zipArray[] = $zipArrayItem;
        }
		
	}//end $idArray foreach
	
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
			exit;
		}//end if
	}//end foreach
}else if($_POST['submitOSMIDs'] == "true" && empty($_POST['omsidNum'])){
    $error[] = 'You can\'t do it yet, please enter some OMSIDs.';
}//end if
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div>
                <h1>Download Images</h1>
				<p>This form will download an array of OMSIDs and grab the main image (1000px) for each OMSID provided. It will then put all the images into a zip file.</p>
            </div>
        </div>
    </div>
    <div class="row" id="theForm">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Enter OMSIDs</h3>
                </div>
                <div class="panel-body">
                    <?php
                        if(isset($error)){
                            foreach($error as $e){
                                echo '<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-exclamation-circle fa-fw"></i> ' . $e . '</div>';
                            }
                        }
                    ?>
                    <form action="downloadimages.php" method="post" name="getOSMIDs" accept-charset="UTF-8"
enctype="application/x-www-form-urlencoded" autocomplete="off">
                        <div class="form-group">
                          <textarea class="form-control" rows="3" id="omsidNum" name="omsidNum" placeholder="Enter OMSIDs with each one on a separate line, no commas."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" name="submitOSMIDs" value="true" id="submitButton">LET'S DO THIS</button>
                    </form>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
	<div class="row" style="display: none;" id="theConfirmation">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="alert alert-success" role="alert">Your files are downloading. Zip file will automatically download.</div>
					<p><a class="btn btn-default btn-lg" href="downloadimages.php" role="button">Download More</a></p>
                    <p>
                        <?php
                            if($_POST['submitOSMIDs'] == "true" && !empty($_POST['omsidNum'])){
                                if(!empty($noImage)){
                                    foreach($noImage as $nI){
                                        echo '<div class="alert alert-danger" role="alert"> OMSID <strong>' . $nI . ' </strong>DID NOT LOAD.</div>';
                                    }
                                }
                            }
                        ?>
                    </p>
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</div><!-- /.container -->
<?php
require_once 'includes/footer-js.php';
?>
<script language="javascript">
	$("#submitButton").click(function(){
		$("#theForm").hide(1000);
		$("#theConfirmation").show(1000);
	});
	$(document).ready(function() {
		$('#omsidNum').focus();
	});
</script>
<?php
require_once 'includes/footer.php';
?>