<?php

if(isset($_POST['imgArray'])){
	$imageUrlArray = $_POST['imgArray'];
    $omsid = $_POST['omsid'];
	$bulletArray = $_POST['bulletArray'];
    $i = 1;
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
		$temp = tempnam(sys_get_temp_dir(), 'zip');
		$res = $zip->open($temp, ZipArchive::CREATE);
		$j = 1;
		if ($res === TRUE) {
			foreach($zipArray as $z){
				//you need to figure out what to do here when it fails and input the default noimage.gif instead on URL faiure
				$theURL = $z['url'];
				$imageSize = @getimagesize($theURL);
				if($imageSize === FALSE){
					$noImageURL = 'http://localhost/images/noimage.gif';
					$zip->addFromString($z['omsid'] . $z['size'] . '-' . $z['count'] . '.gif', file_get_contents($noImageURL));
				}else{
					$zip->addFromString($z['omsid'] . $z['size'] . '-' . $z['count'] . '.jpg', file_get_contents($theURL));
				}
			}//end addFromString foreach
			foreach($bulletArray as $b){
				$zip->addFromString($omsid . '-bullet-' .  $j++ . '.txt', $b);
			}
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

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<!-- page content -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div>
                <h1>Full Product Lookup</h1>
                <p>This form will grab all the relevant product information for one OMSID.</p>
            </div><!-- /.page-header -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row" id="errors" style="display: none;">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body" id="errorMsg">
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row" id="searchForm">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Enter Single OMSID</h3>
                </div>
                <div class="panel-body">
                    <div style="display: none;" id="errors">
                        <div id="errorMsg"></div>
                    </div>
                    <form action="fullLookup.php" role="form" method="POST" id="productLookupForm">
                        <div class="form-group">
                            <input type="tel" class="form-control" id="productLookupOMSID" name="productLookupOMSID" placeholder="OMSID" maxlength="9">
                        </div><!-- /.form-group -->
                        <button type="submit" class="btn btn-success" name="submit" id="submit">LET'S DO THIS</button>
                    </form>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row" id="searchAgain" style="display: none;">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="fullLookup.php" class="btn btn-success">Search New OMSID</a>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row" id="results" style="display: none;">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <!-- Error Alerts -->
                    <div class="alert alert-info" id ="alertCount" role="alert" style="display: none;"></div>
                    <!-- Page Header -->
										<div class="row">
												<div class="col-md-6">
                    			<div id="pageHeader"></div>
												</div><!-- /.col -->
										</div><!-- /.row -->
                    <div id="brandName"></div>
                    <div id="modelNumber"></div>
                    <div id="omsidNumber"></div>
                    <p>
                        <a class="btn btn-default" href="" role="button" target="_blank" id="webUrl">SEE ON SITE&emsp;<i class="fa fa-external-link"></i></a>
						<button class="btn btn-default" href="" role="button" target="_blank" onclick="downloadCSVFunction()" id="downloadCSVButton">DOWNLOAD CSV FILE&emsp;<i class="fa fa-download"></i></button>
                    </p>
                    <hr />
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
                        <li role="presentation"><a href="#text" aria-controls="text" role="tab" data-toggle="tab">Text</a></li>
                        <li role="presentation" style="display: none;" id="richContentTab"><a href="#rich_content" aria-controls="rich_content" role="tab" data-toggle="tab">Rich Content</a></li>
                        <li role="presentation"><a href="#specs" aria-controls="specs" role="tab" data-toggle="tab">Specs</a></li>
                        <li role="presentation" style="display: none;" id="manualsTab"><a href="#manuals" aria-controls="text" role="tab" data-toggle="tab">Manuals</a></li>
                        <li role="presentation" style="display: none;" id="videoTab"><a href="#videos" aria-controls="videos" role="tab" data-toggle="tab">Videos</a></li>
                        <li role="presentation" style="display: none;" id="promotionTab"><a href="#promotions" aria-controls="promotions" role="tab" data-toggle="tab">Promotions</a></li>
                        <li role="presentation"><a href="#data" aria-controls="data" role="tab" data-toggle="tab">Data</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="images">
                        <h3>Images</h3>
                        <button class="btn btn-default btn-sm" onclick="downloadFunction()" id="downloadImagesButton"><i class="fa fa-download"></i>&emsp;DOWNLOAD ALL IMAGES</button>
                        <hr />
						<div class="row" id="1000Images"></div>
						<div class="row" id="600Images"></div>
						<div class="row" id="400Images"></div>
						<div class="row" id="300Images"></div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="text">
                        <div id="salientBulletsText" style="display: none;">
							<h3>Salient Bullets</h3>
							<div>
								<ul id="salientBullets"></ul>
							</div>
						</div>
                        <h3>Product Overview</h3>
                        <div id="description"></div>
						<h3>Bullets</h3>
                        <div>
                            <ul id="mainBulletsText"></ul>
                        </div>
                    </div><!-- /.tabpanel -->
                    <div role="tabpanel" class="tab-pane" id="rich_content">
                        <h3>Rich Content Here</h3>
                        <hr />
                        <div id="richContentData"></div>
                    </div><!-- /.tabpanel -->
                    <div role="tabpanel" class="tab-pane" id="manuals">
                        <h3>Manuals, Guides, Warranty</h3>
                        <hr />
                        <div id="manualsGuides"></div>
                    </div><!-- /.tabpanel -->
                    <div role="tabpanel" class="tab-pane" id="specs">
                        <h3>Specifications</h3>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped table-condensed table-responsive">
                                            <tbody id="specsText"></tbody>
                                        </table>
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->
                            </div><!-- /.col -->
                            <div class="col-md-6" id="supDimensionsCol" style="display: none;">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Dimensions</h3>
									</div>
									<div class="panel-body">
										<table class="table table-striped table-condensed table-responsive">
											<tbody id="supDimensionsText"></tbody>
										</table>
									</div><!-- /.panel-body -->
								</div><!-- /.panel -->
                            </div><!-- /.col -->
                            <div class="col-md-6" id="baseDimensionsPanel" style="display: none;">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Waranty / Certifications</h3>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped table-condensed table-responsive">
                                            <tbody id="baseDimensionsText"></tbody>
                                        </table>
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.tabpanel -->
                    <div role="tabpanel" class="tab-pane" id="videos">
                        <h3>Videos</h3>
                        <hr />
                        <div class="row" id="videoData"></div>
                    </div><!-- /.tabpanel -->
                    <div role="tabpanel" class="tab-pane" id="promotions">
                        <h3>Promotions</h3>
                        <hr />
                        <div id="promotionData"></div>
                    </div><!-- /.tabpanel -->
                    <div role="tabpanel" class="tab-pane" id="data">
                        <h3>Data Object</h3>
						<div id="javascriptData">
                        <hr />
                    </div><!-- /.tabpanel -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container -->

<?php
require_once 'includes/footer-js.php';
?>
<script type="text/javascript" src="js/jszip.min.js"></script>
<script type="text/javascript" src="js/FileSaver.min.js"></script>
<script type="text/javascript" src="js/papaparse.js"></script>
<script type="text/javascript" src="js/encoding-indexes.js"></script>
<script type="text/javascript" src="js/encoding.js"></script>
<script type="text/javascript" src="js/fullLookup.js"></script>
<?php
require_once 'includes/footer.php';
?>
