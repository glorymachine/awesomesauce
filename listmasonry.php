<?php
require_once 'core/OpenGraph.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
// handle the form
if($_POST['submitOSMIDs'] == "true" && !empty($_POST['omsidNum'])){
    $theIDs = str_replace("\r\n", ', ', $_POST['omsidNum']); //remove new lines and carriage returns, replace with comma
    $idArray = explode(', ', $theIDs); //split string into array seperated by ,
}//end check if submit is pressed and OMSIDs entered
if($_POST['submitOSMIDs'] == "true" && empty($_POST['omsidNum'])){
    $error[] = 'You can\'t do it yet, please enter some OMSIDs.';
}//end chekc if submit and show error message if there are no OSMIDs

// <body>
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div>
                <h1>List Only</h1>
                <p>This form will create a list below of all the OMSIDs entered in the list and will not open a new window for each OMSID listed.</p>
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
                            }//end foreach
                        }//end if
                    ?>
                    <form action="listonly.php" method="post" name="getOSMIDs" accept-charset="UTF-8"
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
                    <h3 class="panel-title">OMSIDs URLs:</h3>
                </div>
                <div class="panel-body">
                  <div class="row masonryHouse">
                    <div class="col-md-4 masonryBrick">
                <div class="progress" id="progressBar">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" id="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <?php
                    $total = count($idArray);
                    $i = 0;
                    foreach($idArray as $id){
                        $i++;
                        // Calculate the percentation
                        $widthPercent = intval($i/$total * 100)."%";
                        // Javascript for updating the progress bar and information
                        echo '<script language="javascript">
                        document.getElementById("progress-bar").style.width="' . $widthPercent . '";</script>';
                        // This is for the buffer achieve the minimum size in order to flush data
                        echo str_repeat(' ',1024*64);
                        // Send output to browser immediately
                        flush();
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
                    $title = $graph->title;
                    $image =  $graph->image;
                    $description = $graph->description;
                    if(empty($gURL)){$notLoaded[] = $id;}
                    ?>
                    <div class="panel panel-default">
                      <div class="panel-body">
                            <a href="<?php if(!empty($gURL)){echo $gURL;}else{echo $url;} ?>" target="_blank">
                                <img class="img-responsive" src="<?php if(!empty($image)){echo $image;}else{echo 'images/noimage.gif';} ?>" height="40%" id="mediaElement">
                            </a>
                            <h4><a href="<?php if(!empty($gURL)){echo $gURL;}else{echo $url;} ?>" target="_blank"><?php if(!empty($gURL)){echo $id;}else{echo '<span class="text-danger">' . $id . '</span>';} ?></a></h4>
                                <?php
                                    if(!empty($gURL)){
                                        echo '<p><strong>' . $title . '</strong></p>';
                                        echo '<p>' . $description . '</p>';
                                    }else{
                                        echo '<p class="text-danger"><strong>ITEM NOT FOUND</strong></p>';
                                        echo '<p class="text-danger">When searching for this OMSID, the URL did not return a result.</p>';
                                    }//end if
                                ?>
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel -->
                    <?php } ?>
                  </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?php
    if($i === $total){
        echo '<script language="javascript">
        var element=document.getElementById("mediaElement");
        var elementCount=element.length;
        if(elementCount = ' . $total . '){
        document.getElementById("progressBar").style.display="none";
        }
        </script>';
    }//end if
    if(!empty($notLoaded)){
        foreach($notLoaded as $m){
            echo '<div class="alert alert-danger" role="alert"> OMSID <strong>' . $m . ' </strong>DID NOT LOAD.</div>';
        }
    }
    ?>
    <?php } ?><!-- end check if submit and there are OMSIDs present in the textarea -->
</div><!-- /.container -->

<?php
// </body>
require_once 'includes/footer-js.php';
?>
<script>
$('.masonryHouse').masonry({
  itemSelector: '.masonryBrick', // use a separate class for itemSelector, other than .col-
  columnWidth: '.masonryMortar',
  percentPosition: true
});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#omsidNum').focus();
	});
</script>
<?php
require_once 'includes/footer.php';
?>
