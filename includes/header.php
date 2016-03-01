<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>OMSID AWESOME SAUCE</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/awesome_sauce.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php
      function echoActiveClassIfRequestMatches($requestUri){
        $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

        if ($current_file_name == $requestUri)
          echo 'class="active"';
      }
    ?>

  </head>
  <body>
<!--/
888888 88  88 888888   88  88  dP"Yb  8b    d8 888888   8888b.  888888 88""Yb  dP"Yb  888888
  88   88  88 88__     88  88 dP   Yb 88b  d88 88__      8I  Yb 88__   88__dP dP   Yb   88
  88   888888 88""     888888 Yb   dP 88YbdP88 88""      8I  dY 88""   88"""  Yb   dP   88
  88   88  88 888888   88  88  YbodP  88 YY 88 888888   8888Y"  888888 88      YbodP    88
           _
         ./ |    _________________
         /  /   /  __________    //\_
       /'  /   |  (__________)  ||.' `-.________________________
      /   /    |    __________  ||`._.-'~~~~~~~~~~~~~~~~~~~~~~~~`
     /    \     \__(__________)__\\/
    |      `\
    |        |                                ___________________
    |        |___________________...-------'''- - -  =- - =  - = `.
   /|        |                   \-  =  = -  -= - =  - =-   =  - =|
  ( |        |                    |= -= - = - = - = - =--= = - = =|
   \|        |___________________/- = - -= =_- =_-=_- -=_=-=_=_= -|
    |        |                   ```-------...___________________.'
    |________|
      \    /                                       _
      |    |                              ,,,,,,, /=\
    ,-'    `-,       /\___________       (\\\\\\\||=|
    |        |       \/~~~~~~~~~~~`       ^^^^^^^ \=/
    `--------'                                     `
                                    ____________________________
   _____                          ,\\    ___________________    \
  |     `------------------------'  ||  (___________________)   `|
  |_____.------------------------._ ||  ____________________     |
                                  `//__(____________________)___/

   ______________________
 .'   __                 `.
 |  .'__`.    = = = =     |_.-----._                          .---.
 |  `.__.'    = = = =     | |     | \ _______________        / .-. \
 |`.                      | |     |  |  ````````````,)       \ `-' /
 |  `.                    |_|     |_/~~~~~~~~~~~~~~~'         `---'
 |    `-;___              | `-----'                   ___
 |        /\``---..._____.'               _  _...--'''   ``-._
 |       |  \                            /\\`                 `._
 |       |   )              __..--''\___/  \\     _.-'```''-._   `;
 |       |  /              /       .'       \\_.-'            ````
 |       | /              |_.-.___|  .-.   .'~
 |       `(               | `-'      `-'  ;`._
 |         `.              \__       ___.'  //`-._          _,,,
 |           )                ``--../   \  //     `-.,,,..-'    `;
 `----------'                            \//,_               _.-'
                                          ^   ```--...___..-'

Made with care to help those in need. -Matthew Daniel: matthew_daniel@homedepot.com

/-->
