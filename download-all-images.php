<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<!-- page content -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div>
                <h1>Download All Images</h1>
                <p>This form will grab all images for a list of OMSIDs and ZIP them up for download.</p>
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
                    <h3 class="panel-title">Enter OMSIDs</h3>
                </div>
                <div class="panel-body">
                    <div style="display: none;" id="errors">
                        <div id="errorMsg"></div>
                    </div>
                    <form action="download-all-images.php" role="form" method="POST" id="productLookupForm">
                        <div class="form-group">
                            <textarea class="form-control" id="productLookupOMSID" name="productLookupOMSID" rows="10"></textarea>
                        </div><!-- /.form-group -->
                        <button type="submit" class="btn btn-success" name="submit" id="submit">LET'S DO THIS</button>
                    </form>
					<div id="doitagain" style="display: none;"><a class="btn btn-success pull-right" href="download-all-images.php">SEARCH NEW LIST</a></div>
				    <div class="clearfix"></div>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
		<div class="row" style="display: none;" id="omsidSearchResults">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<h3><span id="omsidCount" class="text-primary"></span> OMSIDs Searched</h3>
						<div id="omsidsSearched" class="list-group">
						</div><!-- /.omsidsSearched -->
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
<script type="text/javascript" src="js/download-all-images.js"></script>
<?php
require_once 'includes/footer.php';
?>
