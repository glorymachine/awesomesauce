<?php
if(isset($_POST['richContent'])){
    $rc = $_POST['richContent'];
    $url = 'http://origin.api.homedepot.com/SearchNav/v2/content?templatePath=' . $rc . '&key=tRXWvUBGuAwEzFHScjLw9ktZ0Bw7a335';
    $json = file_get_contents($url);
    echo $json;
}else{
  echo "Something went wrong or there is no rich content.";
}
?>
