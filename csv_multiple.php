<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<!-- page content -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div>
                <h1>Create CSV - Multiple OMSIDs</h1>
                <p>This form will grab all the relevant product information for multiple OMSIDs and create a CSV file.</p>
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
                    <h3 class="panel-title">Enter one or more OMSIDs</h3>
                </div>
                <div class="panel-body">
                    <div style="display: none;" id="errors">
                        <div id="errorMsg"></div>
                    </div>
                    <form action="csv_multiple.php" role="form" method="POST" id="createCSVForm">
                        <div class="form-group">
                            <textarea class="form-control" id="createCSVids" name="createCSVids" placeholder="Enter one or more OMSIDs." rows="10"></textarea>
                        </div><!-- /.form-group -->
                        <button type="submit" class="btn btn-success" name="submit" id="submit">LET'S DO THIS</button>
                    </form>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container -->

<?php
require_once 'includes/footer-js.php';
?>
<script type="text/javascript" src="js/papaparse.js"></script>
<script type="text/javascript" src="js/encoding-indexes.js"></script>
<script type="text/javascript" src="js/encoding.js"></script>
<script type="text/javascript" src="js/csv_multiple.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#createCSVids').focus();
		//=== shadow on hover
	});
</script>
<?php
require_once 'includes/footer.php';
?>