function imgError(image) {
  image.onerror = "";
  image.src = "/images/noimage.gif";
  return true;
}

$(function () {
     $('#productLookupForm').on('submit', function (e) {
          e.preventDefault();
          var omsid = $("#productLookupOMSID").val();
          if ($.isEmptyObject(omsid) == false) {
               $('#errors').hide(500);
               $.post( "processProductLookup.php", { omsid: omsid })
               .done(function( data ) {
                    $('#searchForm').hide(500);
                    $('#searchAgain').toggle(500);
                    $('#results').toggle(500);
                    var productObject = jQuery.parseJSON(data);
                    console.log(productObject);
                    var pageHeader = productObject.products.product.skus.sku.info.productLabel;
                    var brandName = productObject.products.product.skus.sku.info.brandName;
                    var modelNumber = productObject.products.product.skus.sku.info.modelNumber;
                    var omsidNumber = productObject.products.product.skus.sku.itemId;
                    var description = productObject.products.product.skus.sku.info.description;
                    var webUrl = productObject.products.product.skus.sku.webUrl;
                    var promotion = productObject.products.product.skus.sku.promotions.promotionEntry;
                    $('#pageHeader').append('<h1>' + pageHeader + '</h1>');
                    if(brandName === undefined){
                      $('#brandName').append('<h2 class="text-muted"></h2>');
                    }else{
                      $('#brandName').append('<h2 class="text-muted">' + brandName + '</h2>');
                    }
                    $('#modelNumber').append('<h4 class="text-muted">Model# ' + modelNumber + '</h4>');
                    $('#omsidNumber').append('<h4 class="text-muted">OMSID# ' + omsidNumber + '</h4>');
                    $('#description').append('<p>' + description + '</p>');
                    $('#webUrl').attr("href", webUrl);
                    var promotionEmpty = $.isEmptyObject(promotion);
                    if (promotionEmpty == false) {
                        $('#promotionTab').toggle(500);
                        $('#promotionData').append('<h4>' + promotion.promoType + '</h4><h4>Promotion Code: ' + promotion.promoCode + '</h4><p>Promo Start Date: ' + promotion.discountStartDate + '</p><p>Promo End Date: ' + promotion.discountEndDate + '</p>');
                    }
                    var imageUrlArray = [];
                    productObject.products.product.skus.sku.media.mediaEntry.forEach(function(media) {
                         if (media.height == '1000') {
                              var image = media.location;
                              imageUrlArray.push(media.location);
                              $('#1000Images').append('<div class="col-xs-12 col-sm-4 col-md-3"><div class="thumbnail" id="thumbnail"><a href="' + image + '" target="_blank"><img src="' + image +'" class="imgLoading" onerror="imgError(this);"></a><div class="caption"><h4 class="text-muted">1000px</h4></div></div></div>').show(500);
                         }
                         if (media.height == '600') {
                              var image = media.location;
                              imageUrlArray.push(media.location);
                              $('#600Images').append('<div class="col-xs-12 col-sm-4 col-md-3"><div class="thumbnail" id="thumbnail"><a href="' + image + '" target="_blank"><img src="' + image +'" class="imgLoading" onerror="imgError(this);"></a><div class="caption"><h4 class="text-muted">600px</h4></div></div></div>').show(500);
                         }
                         if (media.height == '400') {
                              var image = media.location;
                              imageUrlArray.push(media.location);
                              $('#400Images').append('<div class="col-xs-12 col-sm-4 col-md-3"><div class="thumbnail"><a href="' + image + '" target="_blank"><img src="' + image +'" class="imgLoading"></a><div class="caption" onerror="imgError(this);"><h4 class="text-muted">400px</h4></div></div></div>').show(500);
                         }
                         if (media.height == '300') {
                              var image = media.location;
                              imageUrlArray.push(media.location);
                              $('#300Images').append('<div class="col-xs-12 col-sm-4 col-md-3"><div class="thumbnail"><a href="' + image + '" target="_blank"><img src="' + image +'" class="imgLoading"></a><div class="caption" onerror="imgError(this);"><h4 class="text-muted">300px</h4></div></div></div>').show(500);
                         }
                         if(media.mediaType == 'RICH CONTENT'){
                              $('#richContentTab').toggle(500);
                              var richContent = media.location;
                              $('#richContentData').append('<pre>' + richContent + '</pre>');
                              $.post("processRichContent.php", { richContent: richContent })
                              .done(function(data) {
                                var richContentObject = jQuery.parseJSON(data);
                                //var richContentObject = data;
                                console.log(richContentObject);
                                $('#richContentData').append('<pre>' + JSON.stringify(richContentObject, null, 4) + '</pre>');
                              })
                              .fail(function(){
                                   $('#errors').show(500);
                                   $('#errorMsg').append('<div class="alert alert-danger" role="alert">Something went wrong, try again.</div>');
                              });
                         }
                         if (media.mediaType == 'BRIGHTCOVE VIDEO') {
                              $('#videoTab').toggle(500);
                              var videoUrl = media.video;
                              var videoThumbnail = media.thumbnail;
                              var videoTitle = media.title;
                              var videoDescription = media.shortDescription;
                              $('#videoData').append('<div class="col-sm-6 col-md-4"><div class="thumbnail"><img src="' + videoThumbnail + '"><div class="caption"><h3>' + videoTitle + '</h3><p>' + videoDescription + '</p><p><a class="btn btn-default btn-block" href="' + videoUrl + '" target="_blank" role="button">VIEW&emsp;<i class="fa fa-external-link"></i></a></p></div></div></div>');
                         }
               });
                    productObject.products.product.skus.sku.attributeGroups.group.forEach(function(group) {
                         if (group.groupType == 'product highlights') {
                              $('#salientBulletsText').show(500);
                              group.entries.attribute.forEach(function(highlight){
                                   $('#salientBullets').append('<li>' + highlight.value + '</li>');
                              });
                         }
                         if (group.groupType == 'functional details') {
                              group.entries.attribute.forEach(function(specs){
                                   var specsName = specs.name;
                                   var specsValue = specs.value;
                                   $('#specsText').append('<tr><th>' + specsName + '</th><td>' + specsValue + '</td></tr>');
                              });
                         }
                         if (group.groupType == 'supplemental dimensions') {
                              var supDimensionsEmpty = $.isEmptyObject(group.entries.attribute);
                              if (supDimensionsEmpty == false){
                                   $('#supDimensionsCol').toggle(500);
                                   group.entries.attribute.forEach(function(supDimensions){
                                        var supDimensionsName = supDimensions.name;
                                        var supDimensionsValue = supDimensions.value;
                                        $('#supDimensionsText').append('<tr><th>' + supDimensionsName + '</th><td>' + supDimensionsValue + '</td></tr>');
                                   });
                              }
                         }
                         if (group.groupType == 'pdf documents') {
                              var manualsArray = $.isArray(group.entries.attribute);
                              if (manualsArray == true) {
                                   $('#manualsTab').toggle(500);
                                   group.entries.attribute.forEach(function(manuals){
                                        var manualName = manuals.name;
                                        var manualUrl = manuals.url;
                                        $('#manualsGuides').append('<p><a class="btn btn-default btn-xs" href="' + manualUrl + '" target="_blank">' + manualName + '&emsp;<i class="fa fa-external-link"></i></a></p>');
                                   });
                              }else{
                                   $('#manualsTab').toggle(500);
                                   var manualName = group.entries.attribute.name;
                                   var manualUrl = group.entries.attribute.url;
                                   $('#manualsGuides').append('<p><a class="btn btn-default btn-xs" href="' + manualUrl + '" target="_blank">' + manualName + '&emsp;<i class="fa fa-external-link"></i></a></p>');
                              }
                         }
                         if (group.groupType == 'descriptive') {
                              group.entries.attribute.forEach(function(mainBullets){
                                   var mainBulletsText = mainBullets.value;
                                   $('#mainBulletsText').append('<li>' + mainBulletsText + '</li>');
                              });
                         }
                         if (group.groupType == 'base dimensions') {
                              var baseDimensionsArray = $.isArray(group.entries.attribute);
                              if (baseDimensionsArray == true) { //checks to see if the attribute is an array, if so, it loops through, else it shows the single value
                                   $('#baseDimensionsPanel').toggle(500);
                                   group.entries.attribute.forEach(function(baseDimensions){
                                        var baseDimensionsName = baseDimensions.name;
                                        var baseDimensionsValue = baseDimensions.value;
                                        $('#baseDimensionsText').append('<tr><th>' + baseDimensionsName + '</th><td>' + baseDimensionsValue + '</td></tr>');
                                   });
                              }else{
                                   var baseDimentionsEmpty = $.isEmptyObject(group.entries.attribute);
                                   if (baseDimentionsEmpty == false){
                                        $('#baseDimensionsPanel').toggle(500);
                                        var baseDimensionsName = group.entries.attribute.name;
                                        var baseDimensionsValue = group.entries.attribute.value;
                                        $('#baseDimensionsText').append('<tr><th>' + baseDimensionsName + '</th><td>' + baseDimensionsValue + '</td></tr>');
                                   }
                              }
                         }
                    });
                    //=== javascript object data
                    $('#javascriptData').append('<pre>' + JSON.stringify(productObject, null, 4) + '</pre>');
               })
               .fail(function(){
                    $('#errors').show(500);
                    $('#errorMsg').append('<div class="alert alert-danger" role="alert">Something went wrong, try again.</div>');
               });
          }else{
               $('#errors').show(500);
               $('#errorMsg').append('<div class="alert alert-danger" role="alert">Please Enter An OMSID Number.</div>');
          }
     });
});

function downloadFunction() {
     $('#downloadImagesButton').html('<i class="fa fa-spinner fa-spin"></i>&emsp;Loading').prop("disabled", true);
     var omsid = $("#productLookupOMSID").val();
     if ($.isEmptyObject(omsid) == false) {
          $('#errors').hide(500);
          $.post( "processProductLookup.php", { omsid: omsid })
          .done(function( data ) {
               var productObject = jQuery.parseJSON(data);
               var imageUrlArray = [];
               var a = 1;
               var b = 1;
               var c = 1;
               var d = 1;
               productObject.products.product.skus.sku.media.mediaEntry.forEach(function(media) {
                    if (media.height == '1000') {
                         var image = media.location;
                         var imageSize = "1000px";
                         imageUrlArray.push({'url':image,'size':imageSize, 'count':a++});
                    }
                    if (media.height == '600') {
                         var image = media.location;
                         var imageSize = "600px";
                         imageUrlArray.push({'url':image,'size':imageSize, 'count':b++});
                    }
                    if (media.height == '400') {
                         var image = media.location;
                         var imageSize = "400px";
                         imageUrlArray.push({'url':image,'size':imageSize, 'count':c++});
                    }
                    if (media.height == '300') {
                         var image = media.location;
                         var imageSize = "300px";
                         imageUrlArray.push({'url':image,'size':imageSize, 'count':d++});
                    }
               });
               var bulletArray = [];
               productObject.products.product.skus.sku.attributeGroups.group.forEach(function(group) {
                    if (group.groupType == 'descriptive') {
                         group.entries.attribute.forEach(function(mainBullets){
                              var bulletAttr = mainBullets.bulletedAttr;
                              if (bulletAttr) {
                                   bulletArray.push(mainBullets.value);
                              }
                         });
                    }
               });
               //download images and create a zip file that automatically downloads
               $.ajax({
                    url: 'fullLookup.php',
                    type: 'POST',
                    data: { imgArray : imageUrlArray, omsid : omsid, bulletArray : bulletArray },
                    context: document.body,
                    cache: false,
                    dataType: 'text',
                    mimeType: 'text/plain; charset=x-user-defined',
                    success: function(data) {
                         var zip = new JSZip();
                         newContent = "";
                         for (var i = 0; i < data.length; i++) {
                              newContent += String.fromCharCode(data.charCodeAt(i) & 0xFF);
                         }
                         var bytes = new Uint8Array(newContent.length);
                         for (var i=0; i<newContent.length; i++) {
                              bytes[i] = newContent.charCodeAt(i);
                         }
                         var blob = new Blob([bytes], {type: "application/zip"})
                         saveAs(blob, omsid + ".zip");
                         zip.load(newContent);
                         $('#downloadImagesButton').html('<i class="fa fa-check-square-o"></i>&emsp;Dowload Complete').prop("disabled", true);
                    },
                    error: function() {
                         $('#errors').show(500);
                         $('#errorMsg').append('<div class="alert alert-danger" role="alert">Something went wrong, try again.</div>');
                    }
               });
          })
          .fail(function(){
               $('#errors').show(500);
               $('#errorMsg').append('<div class="alert alert-danger" role="alert">Something went wrong, try again.</div>');
          });
     }
}
//============================ DOWNLOAD CSV FILE =============================
function downloadCSVFunction() {
     $('#downloadCSVButton').html('<i class="fa fa-spinner fa-spin"></i>&emsp;Loading').prop("disabled", true);
     var omsid = $("#productLookupOMSID").val();
     if ($.isEmptyObject(omsid) == false) {
          $('#errors').hide(500);
          $.post( "processProductLookup.php", { omsid: omsid })
          .done(function( data ) {
               var productObject = jQuery.parseJSON(data);
               var csvFields = ['omsid'];
               var csvData = [omsid];
               productObject.products.product.skus.sku.attributeGroups.group.forEach(function(group) {
                    if (group.groupType == 'product highlights') {
                         group.entries.attribute.forEach(function(highlight){
                              var highlightVal = highlight.value;
                              csvFields.push('product highlight name');
                              csvData.push(highlightVal);
                         });
                    }
                    if (group.groupType == 'functional details') {
                         group.entries.attribute.forEach(function(specs){
                              var specsName = specs.name;
                              var specsValue = specs.value;
                              csvFields.push(specsName);
                              csvData.push(specsValue);
                         });
                    }
                    if (group.groupType == 'supplemental dimensions') {
                         var supDimensionsEmpty = $.isEmptyObject(group.entries.attribute);
                         if (supDimensionsEmpty == false){
                              group.entries.attribute.forEach(function(supDimensions){
                                   var supDimensionsName = supDimensions.name;
                                   var supDimensionsValue = supDimensions.value;
                                   csvFields.push(supDimensionsName);
                                   csvData.push(supDimensionsValue);
                              });
                         }
                    }
                    if (group.groupType == 'descriptive') {
                         group.entries.attribute.forEach(function(mainBullets){
                              var mainBulletsName = mainBullets.name;
                              var mainBulletsText = mainBullets.value;
                              csvFields.push(mainBulletsName);
                              csvData.push(mainBulletsText);
                         });
                    }
                    if (group.groupType == 'base dimensions') {
                         var baseDimensionsArray = $.isArray(group.entries.attribute);
                              if (baseDimensionsArray == true) { //checks to see if the attribute is an array, if so, it loops through, else it shows the single value
                                   group.entries.attribute.forEach(function(baseDimensions){
                                   var baseDimensionsName = baseDimensions.name;
                                   var baseDimensionsValue = baseDimensions.value;
                                   csvFields.push(baseDimensionsName);
                                   csvData.push(baseDimensionsValue);
                              });
                    }else{
                         var baseDimentionsEmpty = $.isEmptyObject(group.entries.attribute);
                         if (baseDimentionsEmpty == false){
                              var baseDimensionsName = group.entries.attribute.name;
                              var baseDimensionsValue = group.entries.attribute.value;
                              csvFields.push(baseDimensionsName);
                              csvData.push(baseDimensionsValue);
                         }
                    }
     }
});
               //==== create file to download
               function downloadCSVFile(csv){
                    var textEncoder = new CustomTextEncoder('windows-1252', {NONSTANDARD_allowLegacyEncoding: true});
                    var csvContentEncoded = textEncoder.encode([csv]);
                    var blob = new Blob([csvContentEncoded]);
                    var a = window.document.createElement("a");
                    a.href = window.URL.createObjectURL(blob, {type: 'text/csv;charset=windows-1252;'});
                    a.download = omsid + ".csv";
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    $('#downloadCSVButton').html('<i class="fa fa-check-square-o"></i>&emsp;Dowload Complete').prop("disabled", true);
               }
               //==== create data and parse with papa parse
               var csvDataArray = {'fields':csvFields,'datafields':csvData};
               //var jsonCSV = JSON.stringify(csvDataArray);
               var csv = Papa.unparse({fields:csvFields,data:csvData});
               downloadCSVFile(csv);
          })
          .fail(function(){
               $('#errors').show(500);
               $('#errorMsg').append('<div class="alert alert-danger" role="alert">Something went wrong, try again.</div>');
          });

     }
}
