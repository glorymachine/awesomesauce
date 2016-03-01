$(function () {
    $('#createCSVForm').on('submit', function (e) {
        e.preventDefault();
        var omsid = $("#createCSVids").val().split("\n");
        if ($.isEmptyObject(omsid) == false) {
            $.post( "processCSV_multiple.php", { omsid: omsid })
               .done(function(omsidArray) {
                    //do something with returned data
                    //console.log(data);
                    var productObject = jQuery.parseJSON(omsidArray);
                    console.log(productObject);
                    //create csv headers
                    var csvFields = [];
                    csvFields.push('itemId', 'superSku', 'brandName', 'description', 'modelNumber', 'productLabel');
                    //create csv rows
                    var csvData = [];
                    var highlightArray = [];
                    //============= Start Create Field Names with Loop ==========//
                    var z = 1;
                    for (a = 0; a < productObject.length; a++) {
                        var itemId = productObject[a].data.products.product.skus.sku.itemId.toString();
                        var superSku = productObject[a].data.products.product.superSku;
                        var brandName = productObject[a].data.products.product.skus.sku.info.brandName;
                        var description = productObject[a].data.products.product.skus.sku.info.description;
                        var modelNumber = productObject[a].data.products.product.skus.sku.info.modelNumber;
                        var productLabel = productObject[a].data.products.product.skus.sku.info.productLabel;
                        csvFields.push('highlightName' + z++);
                        csvData.push({ itemId, superSku, brandName, description, modelNumber, productLabel });
                        var group = productObject[a].data.products.product.skus.sku.attributeGroups.group;
                        for(b = 0; b < group.length; b++){
                            var groupType = group[b].groupType;
                            if (groupType === 'product highlights') {
                                var highlightEntries = group[b].entries.attribute;
                                for(c = 0; c < highlightEntries.length; c++){
                                    var highlightName = highlightEntries[c].name;
                                    var highlightValue = highlightEntries[c].value;
                                    highlightArray.push({highlightValue});
                                }
                            }
                        }
                        csvFields.push('highlightName'+ z++);
                    }
                    
                    console.log(csvData.length);
                    
                    for(y = 0; y < csvData.length; y++){
                        console.log(csvData[y]);
                    }
                    
                    //============== End Create Field Names with Loop ==========//
                    //==== create file to download
                    function downloadCSVFile(csv){
                         var textEncoder = new CustomTextEncoder('windows-1252', {NONSTANDARD_allowLegacyEncoding: true});
                         var csvContentEncoded = textEncoder.encode([csv]);
                         var blob = new Blob([csvContentEncoded]);
                         var a = window.document.createElement("a");
                         a.href = window.URL.createObjectURL(blob, {type: 'text/csv;charset=windows-1252;'});
                         a.download = "multiple.csv";
                         document.body.appendChild(a);
                         a.click();
                         document.body.removeChild(a);
                    }
                    
                    //==== create data and parse with papa parse
                    var csvDataArray = {'fields':csvFields,'data':csvData};
                    var jsonCSV = JSON.stringify(csvDataArray);
                    //console.log('jsonCSV: ' + jsonCSV);
                    var csv = Papa.unparse(jsonCSV);
                    //downloadCSVFile(csv);
                    //console.log(csv);
               })
               .fail(function(){
                    $('#errors').show(500);
                    $('#errorMsg').append('<div class="alert alert-danger" role="alert">Something went wrong, try again.</div>');
               });
        }else{
            $('#errors').show(500);
            $('#errorMsg').append('<div class="alert alert-danger" role="alert">Please enter one or more OMSIDs.</div>');
        }
    });
});