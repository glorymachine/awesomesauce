//START GLOBAL FUNCTIONS
//begin download images from url array
function zipURL(imageUrlArray, omsid){
  //download images and create a zip file that automatically downloads
  $.ajax({
    url: 'process-image-downloads.php',
    type: 'POST',
    data: {
      imgArray: imageUrlArray,
      omsid: omsid
    },
    context: document.body,
    dataType: 'text',
    mimeType: 'text/plain; charset=x-user-defined'})
    .done( function(data) {
      var zip = new JSZip();
      var newContent = "";
      for (var i = 0; i < data.length; i++) {
        newContent += String.fromCharCode(data.charCodeAt(i) & 0xFF);
      }
      var bytes = new Uint8Array(newContent.length);
      for (var a = 0; a < newContent.length; a++) {
        bytes[a] = newContent.charCodeAt(a);
      }
      var blob = new Blob([bytes], {
        type: "application/zip"
      });
      saveAs(blob, omsid + ".zip");
      zip.load(newContent);
      $('#omsidsSearched').append('<a href="" class="list-group-item"><strong>' + omsid + '</strong> Zipped and downloaded.</a>');
      console.log("OMSID: " + omsid + " Zipped.");
    })
    .fail( function() {
      $('#errors').show(500);
      $('#errorMsg').append('<div class="alert alert-danger" role="alert">This OMSID does not have data: ' + omsid + '</div>');
    });
}
//end download images from url array
//begin get image url array function
function downloadImages(omsid) {
  $.post("process-download-all-images.php", {
      omsid: omsid
    })
    .done(function(data) {
      var productObject = jQuery.parseJSON(data);
      var imageUrlArray = [];
      var a = 1; //starts count at number one instead of zero
      var b = 1; //starts count at number one instead of zero
      var c = 1; //starts count at number one instead of zero
      var d = 1; //starts count at number one instead of zero
      console.log(omsid + " sent to process page to get images to build image array.");
      productObject.products.product.skus.sku.media.mediaEntry.forEach(function(media) {
        var image;
        var imageSize;
        if (media.height == '1000') {
          image = media.location;
          imageSize = "1000px";
          imageUrlArray.push({
            'url': image,
            'size': imageSize,
            'count': a++
          });
        }
        if (media.height == '600') {
          image = media.location;
          imageSize = "600px";
          imageUrlArray.push({
            'url': image,
            'size': imageSize,
            'count': b++
          });
        }
        if (media.height == '400') {
          image = media.location;
          imageSize = "400px";
          imageUrlArray.push({
            'url': image,
            'size': imageSize,
            'count': c++
          });
        }
        if (media.height == '300') {
          image = media.location;
          imageSize = "300px";
          imageUrlArray.push({
            'url': image,
            'size': imageSize,
            'count': d++
          });
        }
      });//end foreach loop
      //call function to download zip files of the url array
      zipURL(imageUrlArray, omsid);
    })
    .fail(function() {
      $('#errors').show(500);
      $('#errorMsg').append('<div class="alert alert-danger" role="alert">This OMSID does not have data: ' + omsid + '</div>');
    });//end done and fail
} //End get image url array function

function validateInput() {
  console.log("Validation Started");
  var omsidList = $("#productLookupOMSID").val();
  if (omsidList === null || omsidList === "" || omsidList === " ") {
    $('#errors').show(500);
    $('#errorMsg').append('<div class="alert alert-danger" role="alert">Please enter at least one OMSID number.</div>');
    console.log("Validation False");
    return false;
  } else {
    $('#errors').hide(500);
    $('#omsidSearchResults').show(500);
    console.log("Validation True: Form input not empty.");
    return true;
  }
}

function validateOMSID(omsid){
  console.log("Validate OMSID to be 9 digits and only numbers.");
  if(/^\d+$/.test(omsid)){
    if(omsid.length !== 9){
      $('#errors').show(500);
      $('#errorMsg').append('<div class="alert alert-danger" role="alert">This OMSID is not 9-digits: ' + omsid + '</div>');
      console.log("Validate OMSID Failed");
      return false;
    }else{
      console.log(omsid + " is a valid OMSID format.");
      return true;
    }
  }else{
    $('#errors').show(500);
    $('#errorMsg').append('<div class="alert alert-danger" role="alert">This OMSID contains non-numeric characters: ' + omsid + '</div>');
    console.log("Validate OMSID Failed");
    return false;
  }
}
//END GLOBAL FUNCTIONS

//Form Submission
$(function() {
  $('#productLookupForm').on('submit', function(e) {
    //prevent submit on pressing Enter on keyboard
    e.preventDefault();
    console.log("Form Submitted");

    var validOMSID = validateInput();

    if (validOMSID === true) { //if the input is not blank
      var omsid = $("#productLookupOMSID").val().split("\n");
      $('#omsidCount').append(omsid.length);
      for (var i = 0; i < omsid.length; i++) {
        console.log("Looping OMSID: " + omsid[i]);
        var checkOMSID = validateOMSID(omsid[i]);
        if(checkOMSID === true){
          console.log("Getting image array for OMSID: " + omsid[i]);
          downloadImages(omsid[i]);
        }
      }
    }

  });
});
