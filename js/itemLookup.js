(function() {

	var template = '\
	<div class="container">\
        <div class="row">\
            <div class="col-md-12">\
                <div class="panel panel-primary">\
                    <div class="panel-heading">\
                        <h3 class="panel-title">Item Lookup - Enter One OMSID</h3>\
                    </div>\
                    <div class="panel-body">\
                        <div id="idmSearch">\
                            <div class="form-group">\
                                <div class="row">\
                                    <div class="col-md-6">\
                                        <input type="text" placeholder="OMSID" id="omsid" value="205178498" class="form-control" />\
                                    </div>\
                                    <div class="col-md-6">\
                                        <button class="btn btn-success" id="submit">Search</button>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                        <div id="idmResultsContainer">\
                            <ul id="resultsNav" class="nav nav-pills">\
                                <li data-block="info" role="presentation">Info</li>\
                                <li data-block="images" role="presentation" class="active">Images</li>\
                                <li data-block="videos" role="presentation">Videos</li>\
                            </ul>\
                            <div id="productResults">\
                                <div id="data">\
                                    <div id="info" class="data_block">\
                                        <h2>Brand</h2>\
                                        <div id="productBrand"></div>\
                                        <h2>Title</h2>\
                                        <div id="productTitle"></div>\
                                        <h2>Description</h2>\
                                        <div id="productDesc"></div>\
                                        <h2 id="pdfHeader">PDF</h2>\
                                        <div id="pdfs"></div>\
                                        <h2 id="sPointsHeader">Salient Points</h2>\
                                        <div id="salient_points"></div>\
                                        <h2 id="bulletPointsHeader">Bullet Points</h2>\
                                        <div id="bullet_points"></div>\
                                    </div>\
                                    <div id="images" class="data_block">\
                                        <div id="productImages"></div>\
                                    </div>\
                                    <div id="videos" class="data_block">\
                                        <div id="productVideos"></div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>';

    var injectTemplate = function injectTemplate(template) {
    	document.body.insertAdjacentHTML('beforeend', template);
    };

	var injectScript = function injectScript(src) {
		var _script = document.createElement('script');
		_script.setAttribute('type', 'text/javascript');
		_script.setAttribute('src', src);
		document.body.appendChild(_script);
	};

	(function listen() {
		var videoIsPlaying = {};
		document.addEventListener('click', function(e) {
			if (e.target.parentNode && e.target.parentNode.className && e.target.parentNode.className.indexOf('pod') !== -1) {
				e.target.parentNode.querySelector('.data').style.display = (e.target.parentNode.querySelector('.data').style.display === 'block') ? 'none' : 'block';
			}

			if (e.target.id ==='submit') {
				injectScript('http://origin.api.homedepot.com/ProductInfo/v2/products/sku?itemId=' + document.getElementById('omsid').value + '&key=tRXWvUBGuAwEzFHScjLw9ktZ0Bw7a335&type=json&callback=build');
			}

			if (e.target.id === 'close') {
				var idmWrapper = document.getElementById('idmWrapper');
				idmWrapper.parentNode.removeChild(idmWrapper);
			}

			if (e.target.nodeName === 'VIDEO') {
				if (!videoIsPlaying[e.target.id]) {
					e.target.play();
					videoIsPlaying[e.target.id] = true;
				} else {
					e.target.pause();
					videoIsPlaying[e.target.id] = false;
				}
			}

			if (e.target.innerHTML.indexOf('://') !== -1 && e.target.nodeName === 'SPAN') {
				window.open('http://' + e.target.innerHTML.split('://')[1], '_blank');
			}

			if (e.target.nodeName === 'IMG') {
				window.open(e.target.id || e.target.src, '_blank');
			}

			if (e.target.nodeName === 'LI' && e.target.dataset.block) {
				document.querySelector('.active').className = '';
				e.target.className = 'active';
				[].forEach.call(document.querySelectorAll('.data_block'), function(dataBlock) {
					dataBlock.style.display = 'none';
				});
				document.getElementById(e.target.dataset.block).style.display = 'block';
			}
		});

		document.addEventListener('keydown', function(e) {
			if (e.which == 13 && e.target.id == 'omsid') {
				injectScript('http://origin.api.homedepot.com/ProductInfo/v2/products/sku?itemId=' + document.getElementById('omsid').value + '&key=tRXWvUBGuAwEzFHScjLw9ktZ0Bw7a335&type=json&callback=build');
			}
		});
	}());

	(function init() {
		injectTemplate(template);
	}());

}())

window.build = (function() {

	var buildMedia = {
		image: function(data) {
			var container = document.createElement('div');
			var imgContainer = document.createElement('div');
			var img = document.createElement('img');
			var stats = document.createElement('p');
			var imgDimensions = document.createElement('span');
			var imgUrl = document.createElement('span');

			imgDimensions.innerHTML = '<em>Dimensions: </em>' + data.width + ' X ' + data.height;
			imgUrl.innerHTML = '<em>URL: </em>' + data.location;
			imgUrl.className = 'link';

			imgContainer.className = 'imgContainer';
			container.className = 'imgContainerWrapper';

			stats.appendChild(imgDimensions);
			stats.appendChild(imgUrl);

			img.setAttribute('src', data.location);
			img.setAttribute('id', data.location);

			imgContainer.appendChild(img);
			container.appendChild(imgContainer);
			container.appendChild(stats);

			return container;
		},

		video: function(data) {
			var container = document.createElement('div');
			var video = document.createElement('video');
			var stats = document.createElement('p');
			var bcId = document.createElement('span');
			var bcUrl = document.createElement('span');

			bcId.innerHTML = '<em>Brightcove ID: </em>' + data.videoId;
			bcUrl.innerHTML = '<em>Brightcove URL: </em>' + data.video;

			stats.appendChild(bcId);
			stats.appendChild(bcUrl);

			video.setAttribute('id', data.videoId);
			video.setAttribute('src', data.video);
			video.setAttribute('poster', data.videoStill);
			video.innerHTML = 'Your browser does no support the video element';

			container.appendChild(video);
			container.appendChild(stats);

			return container;
		}
	}
	
	return function(data) {
		var frags = {
			image: document.createDocumentFragment(),
			video: document.createDocumentFragment()
		}
		var sku = data.products.product.skus.sku;
		var hasVideo = false;

		sku.media.mediaEntry.forEach(function(item) {
			var mediaType = item.mediaType.indexOf('IMAGE') !== -1 ? 'image' : 'video';
			var mediaNode = buildMedia[mediaType](item);
			if (mediaNode) frags[mediaType].appendChild(mediaNode);
			if (mediaType === 'video') hasVideo = true;
		});

		if (!hasVideo) {
			document.getElementById('resultsNav').className = 'active';
			document.querySelector('li[data-block="videos"]').style.display = 'none';
		} else {
			document.getElementById('resultsNav').className = '';
			document.querySelector('li[data-block="videos"]').style.display = 'block';
		}

		var productHighlights = document.createElement('ul');
		var pdfs = document.createElement('ul');
		var bullets = document.createElement('ul');

		var hasPDF = false;
		var hasSPoints = false;
		var hasBulletPoints = false;

		sku.attributeGroups.group.forEach(function(group) {
			if (group.groupType === 'product highlights') {
				hasSPoints = true;
				group.entries.attribute.reverse().forEach(function(highlight) {
					var highlightEl = document.createElement('li');
					highlightEl.innerHTML = highlight.value;
					productHighlights.appendChild(highlightEl);
				});
			}
			if (group.groupType === 'pdf documents') {
				hasPDF = true;
				if (group.entries.attribute.length) {
					group.entries.attribute.forEach(function(pdf) {
						var pdfEl = document.createElement('li');
						var pdfLink = document.createElement('a');
						pdfLink.innerHTML = pdf.name;
						pdfLink.href = pdf.url;
						pdfLink.target = '_blank';
						pdfEl.appendChild(pdfLink);
						pdfs.appendChild(pdfEl);
					});
				}
			}
			if (group.groupType === 'descriptive') {
				hasBulletPoints = true;
				var _descriptive = [];
				group.entries.attribute.sort(function(a, b) {
					return (a.name < b.name) ? -1 : (a.name > b.name) ? 1 : 0;
				});
				group.entries.attribute.forEach(function(bullet) {
					if (bullet.bulletedAttr) {
						var bulletEl = document.createElement('li');
						bulletEl.innerHTML = bullet.value;
						bullets.appendChild(bulletEl);
					}
				});
			}
		});

		document.getElementById('salient_points').innerHTML = '';
		if (!hasSPoints) {
			document.getElementById('sPointsHeader').style.display = 'none';
		} else {
			document.getElementById('salient_points').appendChild(productHighlights);
			document.getElementById('sPointsHeader').style.display = 'block';
		}

		document.getElementById('pdfs').innerHTML = '';
		if (!hasPDF) {
			document.getElementById('pdfHeader').style.display = 'none';
		} else {
			document.getElementById('pdfs').appendChild(pdfs);
			document.getElementById('pdfHeader').style.display = 'block';
		}

		document.getElementById('bullet_points').innerHTML = '';
		if (!hasBulletPoints) {
			document.getElementById('bulletPointsHeader').style.display = 'none';
		} else {
			document.getElementById('bullet_points').appendChild(bullets);
			document.getElementById('bulletPointsHeader').style.display = 'block';
		}

		document.getElementById('productBrand').innerHTML = sku.info.brandName;
		document.getElementById('productTitle').innerHTML = sku.info.productLabel;
		document.getElementById('productDesc').innerHTML = sku.info.description;
		document.getElementById('productImages').innerHTML = '';
		document.getElementById('productImages').appendChild(frags.image);
		document.getElementById('productVideos').innerHTML = '';
		document.getElementById('productVideos').appendChild(frags.video);
		document.getElementById('idmResultsContainer').style.display = 'block';
	}
}());