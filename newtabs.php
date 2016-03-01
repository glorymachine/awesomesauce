<?php
require_once 'core/OpenGraph.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
// handle the form
if($_POST['submitOSMIDs'] == "true" && !empty($_POST['omsidNum'])){
    $theIDs = str_replace("\r\n", ', ', $_POST['omsidNum']); //remove new lines and carriage returns, replace with comma
    $idArray = explode(', ', $theIDs); //split string into array seperated by ,
    foreach($idArray as $id){
        echo '<script>';
        echo 'window.open("http://www.homedepot.com/p/' . $id . '", "_blank");';
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
                <h1>Launch in New Tabs</h1>
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
                    <form action="newtabs.php" method="post" name="getOSMIDs" accept-charset="UTF-8"
enctype="application/x-www-form-urlencoded" autocomplete="off">
                        <div class="form-group">
                          <textarea class="form-control" rows="3" id="omsidNum" name="omsidNum" placeholder="Enter OMSIDs with each one on a separate line, no commas."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" name="submitOSMIDs" value="true">LET'S DO THIS</button>
                    </form>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?php
        if($_POST['submitOSMIDs'] == "true" && !empty($_POST['omsidNum'])){
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">You searched <?php echo count($idArray); ?> OMSIDs:</h3>
                </div>
                <div class="panel-body">
                <?php
                    foreach($idArray as $id){
                        echo '<a href="http://www.homedepot.com/p/' . $id . '" class="btn btn-info btn-sm" role="button" target="_blank">' . $id . '</a> ';
                    }
                ?>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">OMSIDs URLs:</h3>
                </div>
                <div class="panel-body">
                <?php
                    foreach($idArray as $id){
                        $url = 'http://www.homedepot.com/p/' . $id;
                        $headers = get_headers($url);
                        $headers = array_reverse($headers);
                        foreach($headers as $header) {
                            if (strpos($header, 'Location: ') === 0) {
                            $url = str_replace('Location: ', '', $header);
                            break;
                        }
                    }
                    $gURL = substr_replace($url, '', -44);
                    $graph = OpenGraph::fetch($gURL);
                    $title = $graph->title;
                    $image =  $graph->image;
                    $description = $graph->description;
                    ?>
                    <div class="media">
                        <div class="media-left">
                            <a href="<?php if(!empty($gURL)){echo $gURL;}else{echo $url;} ?>" target="_blank">
                                <img class="media-object" src="<?php if(!empty($image)){echo $image;}else{echo 'images/noimage.gif';} ?>" height="40%">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading"><a href="<?php if(!empty($gURL)){echo $gURL;}else{echo $url;} ?>" target="_blank"><?php if(!empty($gURL)){echo $id;}else{echo '<span class="text-danger">' . $id . '</span>';} ?></a></h4>
                                <?php if(!empty($gURL)){echo $description;}else{echo '<span class="text-danger">ITEM NOT FOUND</span>';} ?>
                        </div>
                    </div>
                    <hr />
                    <?php } ?>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?php } ?>
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