<!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><img src="images/hd-logo.png" height="100%"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li <?=echoActiveClassIfRequestMatches("fullLookup")?>><a href="fullLookup.php">Product Lookup</a></li>
            <li <?=echoActiveClassIfRequestMatches("newtabs")?>><a href="newtabs.php">Launch New Tabs</a></li>
            <li <?=echoActiveClassIfRequestMatches("listonly")?>><a href="listonly.php">List Only</a></li>
            <li <?=echoActiveClassIfRequestMatches("downloadimages")?>><a href="downloadimages.php">Download Main Images</a></li>
            <li <?=echoActiveClassIfRequestMatches("download-all-images")?>><a href="download-all-images.php">Download All Images</a></li>
            <li><a href="/indesign/index.html" target="_blank">InDesign</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
