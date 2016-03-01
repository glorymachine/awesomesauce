<?php
require_once 'core/OpenGraph.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
// handle the form
if($_POST['submitOSMIDs'] == "true" && !empty($_POST['omsidNum'])){
    $tier = $_POST['tierLevel'];
    $theIDs = str_replace("\r\n", ', ', $_POST['omsidNum']); //remove new lines and carriage returns, replace with comma
    $idArray = explode(', ', $theIDs); //split string into array seperated by ,
    foreach($idArray as $id){
        echo '<script>';
        echo 'window.open("http://www.homedepot.com/p/' . $id . '?keyword=' . $id . '&qa=tier' . $tier . '", "_blank");';
        echo '</script>';
        }//end creating array of links to launch in new tabs
}//end check if submit is pressed and OMSIDs entered
if($_POST['submitOSMIDs'] == "true" && empty($_POST['omsidNum'])){
    $error[] = 'You can\'t do it yet, please enter some OMSIDs.';
}

// <body>
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div>
                <h1>Launch in New Tabs</h1><h1>Check tri-tier A/B Test</h1>
                <p>This form will accept a list of OMSIDs and open a new window/tab for each and create a list of the items below. For this to work, you have to accept pop-ups for this site.</p>
            </div>
        </div>
    </div>
    <div class="row">
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
                    <form action="newtabs-ab-test.php" method="post" name="getOSMIDs" accept-charset="UTF-8"
enctype="application/x-www-form-urlencoded" autocomplete="off">
                        <div class="form-group">
                            <label for="tierLevel">Tier Level (1,2,3)</label>
                            <input type="text" class="form-control" name="tierLevel" placeholder="Enter Tier Level" maxlength="1" value="<?php if(isset($tier)){echo $_POST['tierLevel'];}?>" required>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Enter one or more OMSIDs.</label>
                          <textarea class="form-control" rows="10" id="omsidNum" name="omsidNum" placeholder="Enter OMSIDs with each one on a separate line, no commas." required><?php if(isset($theIDs)){echo $_POST['omsidNum'];}?></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" name="submitOSMIDs" value="true">LET'S DO THIS</button>
                    </form>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container -->

<?php
// </body>
require_once 'includes/footer-js.php';
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#omsidNum').focus();
	});
</script>
<?php
require_once 'includes/footer.php';
?>