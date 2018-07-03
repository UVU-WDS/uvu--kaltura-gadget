<!DOCTYPE html>
<html>
	<head>
		<title>Kaltura Videos</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="/_resources/gadgets/kaltura-videos-2.0/kaltura.css?v=2.4.0">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="/_resources/js/handlebars.min.js"></script>
		<script type="text/javascript" src="js/webtoolkit.md5.js"></script>
		<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="js/jquery.iframe-transport.js"></script>
		<script type="text/javascript" src="js/jquery.fileupload.js"></script>
		<script type="text/javascript" src="js/jquery.fileupload-process.js"></script>
		<script type="text/javascript" src="js/jquery.fileupload-validate.js"></script>
		<script type="text/javascript" src="js/jquery.fileupload-kaltura.js"></script>
		<script type="text/javascript" src="js/jquery.fileupload-kaltura-base.js"></script>
		<script type="text/javascript" src="js/gadgetlib.js"></script>
	</head>
	<body>
		<?php
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		?>
		<header id="main">
			<div id="main-top">
				<div class="search-wrapper">
					<form id="searchForm">
						<input type="text" id="search" name="search" value="" placeholder="Search videos..." />
					</form>
					<button id="clearSearch">x</button>
				</div>
				<button id="filterButton" onclick="toggleFilters()"></button>
				<button id="uploadButton" onclick="beginUploadForm()"></button>
			</div>
			<div id="main-filters">
				<div class="select-wrapper">
					<span>Filter:&nbsp;</span>
					<select name="categories" id="categories" class="browse-categoriesDropdown">
						<option value="none">-Category-</option>
						<option value="59117542">VP Academic Affairs</option>
						<option value="59117552">-- Academic Administration</option>
						<option value="59117572">-- Academic Programs</option>
						<option value="59117582">-- College of Health &amp; Public Service</option>
						<option value="59117602">-- College of HASS</option>
						<option value="59117612">-- College of Science and Health</option>
						<option value="59117652">-- College of E&amp;T</option>
						<option value="59117662">-- Academic Outreach</option>
						<option value="59117672">-- Economic Development</option>
						<option value="59117682">-- Engaged Learning</option>
						<option value="59117692">-- School of the Arts</option>
						<option value="59117702">-- School of Business</option>
						<option value="59117712">-- School of Education</option>
						<option value="59117742">-- University College</option>
						<option value="59117752">VP Student Affairs</option>
						<option value="59117812">-- Enrollment Management</option>
						<option value="59117852">-- Grants and Outreach</option>
						<option value="59117862">-- Student Life</option>
						<option value="59117872">-- Student Success and Retention</option>
						<option value="59117892">VP Finance and Administration</option>
						<option value="59117932">-- Facilities and Planning</option>
						<option value="59117942">-- Finance and Business Services</option>
						<option value="59117962">-- Office of IT</option>
						<option value="59117982">VP University Relations</option>
						<option value="59118002">-- Marketing and Communications</option>
						<option value="59118032">VP Planning Budget and HR</option>
						<option value="59118042">-- Human Resources</option>
						<option value="59118072">-- Institutional Research &amp; Information</option>
						<option value="59118082">VP Development and Alumni</option>
						<option value="59118102">-- Presidents Office</option>
					</select>
				</div>
			</div>
		</header>
		<section id="results">
		</section>
		<section id="uploadForm" style="display: none;">
			<section id="user-agreement">
				<p>By continuing and uploading a video, you confirm that this service is only to be used by videos that will have a presence on the UVU public website (www.uvu.edu) or on the internal site (my.uvu.edu).</p>
				<p>Best practices are to not display a video on a web page that is longer than around 60 minutes.</p>
				<button id="user-agreement-confirm" onclick="hideUserAgreement()">I agree, continue.</button>
			</section>
			<button onclick="closeUploadForm()" id="closeFormButton">&times;</button>
			<h2>Upload Video</h2>
			<?php
			$formId = 'uploadVideo';
			$sender = 'ADMIN_EMAIL_HERE';
			$receiver = 'ADMIN_EMAIL_HERE';
			$subject = 'New video submitted';		

			$csv_log = $_SERVER['DOCUMENT_ROOT'].'/_formdata/kaltura_video_submissions.csv';
			$csv_log_time = true;
			$csv_log_time_format = 'Y/m/d'; 

			include($_SERVER['DOCUMENT_ROOT'].'/_resources/php/formmail/formmail.php');
			?>
			
			<form name="fileUploadform" id="fileUploadform" class="upload-fileChooser">
				<div id="uploadHook"></div>
				<label for="fileData">
					<p>Video File (.mp4, .mov)</p>
					<input name="fileData" id="fileData" accept=".mp4,.mov" type="file" onchange="checkDuration(this.files)">
				</label>
				<div class="upload progress" style="display: none;">
					<div class="bar determinate" style="width: 0%;"></div>
				</div>
			</form>
			
			<form name="uploadVideo" id="uploadVideo" class="upload-formInputs" method="POST" enctype="multipart/form-data" class="form">
				<input type="hidden" id="user" name="user" title="UVID" value="0000000" />
				<label for="title"><p>Video Title</p>
					<input name="title" id="title" placeholder="Title" title="Video Title" type="text">
				</label>
				<label for="category"><p>Video Category/Area</p>
					<div class="category-wrapper">
						<select name="category" id="category" title="Area/Category">
							<option value="none">---Select Category---</option>
							<option value="59117542">VP Academic Affairs</option>
							<option value="59117542, 59117552">-- Academic Administration</option>
							<option value="59117542, 59117572">-- Academic Programs</option>
							<option value="59117542, 59117582">-- College of Health &amp; Public Service</option>
							<option value="59117542, 59117602">-- College of HASS</option>
							<option value="59117542, 59117612">-- College of Science and Health</option>
							<option value="59117542, 59117652">-- College of E&amp;T</option>
							<option value="59117542, 59117662">-- Academic Outreach</option>
							<option value="59117542, 59117672">-- Economic Development</option>
							<option value="59117542, 59117682">-- Engaged Learning</option>
							<option value="59117542, 59117692">-- School of the Arts</option>
							<option value="59117542, 59117702">-- School of Business</option>
							<option value="59117542, 59117712">-- School of Education</option>
							<option value="59117542, 59117742">-- University College</option>
							<option value="59117752">VP Student Affairs</option>
							<option value="59117752, 59117812">-- Enrollment Management</option>
							<option value="59117752, 59117852">-- Grants and Outreach</option>
							<option value="59117752, 59117862">-- Student Life</option>
							<option value="59117752, 59117872">-- Student Success and Retention</option>
							<option value="59117892">VP Finance and Administration</option>
							<option value="59117892, 59117932">-- Facilities and Planning</option>
							<option value="59117892, 59117942">-- Finance and Business Services</option>
							<option value="59117892, 59117962">-- Office of IT</option>
							<option value="59117982">VP University Relations</option>
							<option value="59117982, 59118002">-- Marketing and Communications</option>
							<option value="59118032">VP Planning Budget and HR</option>
							<option value="59118032, 59118042">-- Human Resources</option>
							<option value="59118032, 59118072">-- Institutional Research &amp; Information</option>
							<option value="59118082">VP Development and Alumni</option>
							<option value="59118082, 59118102">-- Presidents Office</option>
						</select>
					</div>
				</label>
				<input type="hidden" id="uploadToken" name="uploadToken" title="Upload Token" value="null" />
				<input type="hidden" name="formId" value="uploadVideo" />
				<input id="searchDuplicates" value="Next" type="button" onclick="nextUploadPage()" class="off" />
			</form>
			
			<section id="possibleDuplicates">
				<p>Is your video already uploaded? If so, please use the version already here.</p>
				<div id="duplicateResults"></div>
				<input type="checkbox" id="duplicateConfirm" name="duplicateConfirm" value="duplicateConfirm" onchange="checkForCheck()" />
				<label for="duplicateConfirm"><p>My video is not on the above list.</p></label>
				<input id="submitVideo" value="Submit" type="button" class="off" />
			</section>
			
			<form id="success" class="upload-success" style="display: none;">
				<p>Thank you for submitting your video. It will be available as soon as it is done processing. You may need to reload this gadget to see a new video listed. If you have any problems, please contact <a href="mailto:websupport@uvu.edu">websupport@uvu.edu</a></p>
				<button id="doneWithUpload" onclick="window.location.reload(true)">Close</button>
			</form>
			<div class="cover"></div>
		</section>
		
		<script type="text/x-handlebars-template" id="results-template">
			<ul>
				{{#each entries.objects}}
				<li class="card entry" id="{{id}}">
					<header>
						<span class="title">{{name}}</span>
						<span class="open-button">&#9660;</span>
					</header>
					<div class="collapse">
						<div class="preview">
							<img src="{{thumbnailUrl}}" alt="{{name}}" />
							<div class="img-cover">&#9654;</div>
						</div>
						<footer>
							<label for="embed-{{id}}">
								<span>&lt;/&gt;</span>
								<input readonly type="text" name="embed-{{id}}" id="embed-{{id}}" value="&lt;iframe src=&quot;https://cdnapisec.kaltura.com/p/1908451/sp/190845100/embedIframeJs/uiconf_id/32613972/partner_id/1908451?iframeembed=true&amp;playerId=kaltura_player_1506442045&amp;entry_id={{id}}&quot; width=&quot;640&quot; height=&quot;360&quot; frameborder=&quot;0&quot; allowfullscreen=&quot;allowfullscreen&quot; title=&quot;{{name}}&quot;&gt;&lt;/iframe&gt;" onfocus="$(this).select()" />
							</label>
							<div class="btn-group">
								<a href="{{downloadUrl}}" target="_blank" title="Download - {{name}}" id="download-video">Download MP4</a>
								<a data-srturl="{{srtURL}}" href="{{srtURL}}" target="_blank" title="Captions - {{name}}" id="download-captions">Download Captions</a>
							</div>
						</footer>
					</div>
				</li>
				{{/each}}
			</ul>
			<footer class="pagination">
				<div class="previous" title="Previous page">&#10132;</div>
				<div class="pages"><span id="currentPage">0</span>&nbsp;of&nbsp;<span id="totalPages">0</span></div>
				<div class="next" title="Next page">&#10132;</div>
				<div class="top">Top</div>
			</footer>
		</script>
		<script type="text/x-handlebars-template" id="duplicates-template">
			<ul>
				{{#each entries.objects}}
				<li class="card entry" id="{{id}}">
					<header>
						<span class="title">{{name}}</span>
						<span class="open-button">&#9660;</span>
					</header>
					<div class="collapse">
						<div class="preview">
							<img src="{{thumbnailUrl}}" alt="{{name}}" />
							<div class="img-cover">&#9654;</div>
						</div>
						<footer>
							<label for="embed-{{id}}">
								<span>&lt;/&gt;</span>
								<input readonly type="text" name="embed-{{id}}" id="embed-{{id}}" value="&lt;iframe src=&quot;https://cdnapisec.kaltura.com/p/1908451/sp/190845100/embedIframeJs/uiconf_id/32613972/partner_id/1908451?iframeembed=true&amp;playerId=kaltura_player_1493739881&amp;entry_id={{id}}&quot; width=&quot;640&quot; height=&quot;360&quot; frameborder=&quot;0&quot; allowfullscreen=&quot;allowfullscreen&quot; title=&quot;{{name}}&quot;&gt;&lt;/iframe&gt;" onfocus="$(this).select()" />
							</label>
						</footer>
					</div>
				</li>
				{{/each}}
			</ul>
		</script>

		<script type="text/javascript">
			// Variables
			var currentPage = 1;
			var filters = false;
			var videoFile = [];
			window.URL = window.URL || window.webkitURL;

			// Ajax call
			function callMedia(url, tag, page, search, target){
				if (!tag) { tag = 'none'; }
				if (!page) { page = '1'; }
				if (!search || search == '') { search = ''; }
				var targetUrl;
				if (target == 'duplicates') { targetUrl = $('#duplicateResults'); } else { targetUrl = $('#results'); }
				targetUrl.html('<p style="text-align:center;">Loading...</p><div class="progress"><div class="bar indeterminate"></div></div>');
				$.ajax({
					url: url,
					data: {
						needKs: false,
						categoryTag: tag,
						currentPage: page.toString(),
						pageSize: '25',
						searchString: search
					},
					dataType: 'json',
					cache: false
				})
					.done(function(results){
					if (target == 'duplicates') {
						printDuplicates(results);
					} else {
						printList(results);
					}
				})
					.fail(function(a,b,c){
					console.log(a);
					console.log(b);
					console.log(c);
				});
			}

			// Create the list
			function printList(data){
				perPage = parseInt(data.entries.perPage);
				totalPages = Math.ceil(data.entries.totalCount / perPage);
				currentPage = parseInt(data.entries.currentPage);
				
				var template = Handlebars.compile($('#results-template').html());
				var content = template(data);

				$('#results').html(content);
				
				$('#currentPage').html(currentPage);
				$('#totalPages').html(totalPages);
				
				if (currentPage == 1 && currentPage < totalPages) {
					$('.previous').addClass('disabled');
				}
				if (currentPage != 1 && currentPage == totalPages) {
					$('.next').addClass('disabled');
				}
				if (currentPage == 1 && currentPage == totalPages) {
					$('.pagination').html('<div class="all">Showing all results</div><div class="top">Top</div>');
				}
				if (totalPages == 0) {
					$('#results').html('<ul><li class="card empty">No results found :(</li></ul>');
				}
				if (data.entries.searched !== '' || $('#categories').val() != 'none') {
					showFilters();
				} else { hideFilters(); }

			}
			
			function printDuplicates(data){
				if (data.entries.objects.length == 0) {
					$('#duplicateResults').html('<p>No possible duplicates found. Continue to submit your video.</p>');
					$('#duplicateResults > input[type="checkbox"], #duplicateResults > label').hide();
					$('#duplicateResults > input[type="checkbox"]').prop('checked', true);
				} else {
					var template = Handlebars.compile($('#duplicates-template').html());
					var content = template(data);
					$('#duplicateResults').html(content);
				} 
			}
			
			function search() {
				callMedia('session.php', $('#categories').val(), 1, $('#search').val());
			}
			
			function beginUploadForm() {
				$('html, body').addClass('inactive');
				$('#uploadForm').show();
				$('#user-agreement').removeClass('hidden');
			}
			
			function hideUserAgreement() {
				$('#user-agreement').addClass('hidden');
			}
			
			function toggleFilters(){
				if (filters) { hideFilters(); } else { showFilters(); }
			}
			
			function showFilters(){
				filters = true;
				$('#main').addClass('filters');
				$('.select-wrapper').addClass('active');
			}
			
			function hideFilters(){
				filters = false;
				$('#main').removeClass('filters');
				$('.select-wrapper').removeClass('active');
			}
			
			function nextUploadPage(){
				var fileVal = $('#fileData').val();
				var titleVal = $('#title').val();
				var catVal = $('#category').val();
				var videoProgress = $('.upload.progress .bar');
				if (fileVal === '' || titleVal === '' || catVal === 'none' || !videoProgress.hasClass('finished')) {

					if (fileVal === '') {
						$('#fileData').addClass('unfinished');
					} else {
						$('#fileData').removeClass('unfinished');
					}
					if (titleVal == '') {
						$('#title').addClass('unfinished');
					} else {
						$('#title').removeClass('unfinished');
					}
					if (catVal === 'none') {
						$('#category').addClass('unfinished');
					} else {
						$('#category').removeClass('unfinished');
					}
					if (!videoProgress.hasClass('finished') && $('.upload.progress').css('display') === 'block') {
						videoProgress.addClass('unfinished');
					} else {
						videoProgress.removeClass('unfinished');
					}

				} else {
					$('#fileUploadform, #uploadVideo').hide();
					callMedia('findduplicates.php', 'none', 1, $('#title').val(), 'duplicates');
					$('#possibleDuplicates').show();
				}
			}
			
			function checkForm(){
				if ($('.upload.progress .bar').hasClass('finished') && $('#fileData').val() != '' && $('#title').val() != '' && $('#category').val() != 'none') {
					$('#searchDuplicates').removeClass('off');
				} else {
					$('#searchDuplicates').addClass('off');
				}
			}
			
			function checkForCheck(){
				if ($('#duplicateConfirm').is(':checked')) {
					$('#submitVideo').removeClass('off');
				} else {
					$('#submitVideo').addClass('off');
				}
			}
			
			function closeUploadForm() {
				$('#uploadForm').hide();
				$('html, body').removeClass('inactive');
			}
			
			function checkDuration(files){
				videoFile.push(files[0]);
				var video = document.createElement('video');
				video.preload = 'metadata';
				video.onloadedmetadata = function() {
					window.URL.revokeObjectURL(this.src)
					var duration = video.duration;
					videoFile[videoFile.length-1].duration = duration;
					if (duration > 3600) {
						$('#searchDuplicates').addClass('restricted');
						$('#fileUploadform').after('<p id="videoWarning">We\'ve found your video is longer than 60 minutes, which goes against best practices for video on the web.<br/>If you still require this video to be uploaded, please contact WDS at <a href="mailto:websupport@uvu.edu">websupport@uvu.edu</a></p>');
					} else {
						$('#searchDuplicates').removeClass('restricted');
						$('#videoWarning').remove();
						console.log('uploading...');
						$('#uploadHook').fileupload('add', {
							fileInput: $('#fileData'), 
							uploadBoxId: 1
						});
					}
				}
				video.src = URL.createObjectURL(files[0]);
			}

			// Event handlers
			// Regenerate list when category changes
			$('#categories').on('change', function(){
				callMedia('session.php', $('#categories').val(), 1, $('#search').val());
			});
			
			// Download captions
			$('#results').on('click', '#download-captions', function(){
				var url = $(this).attr('data-srturl');
				$.ajax({
					url: url,
					dataType: 'text',
					cache: false
				}).done(function(results){
					console.log(results);
				}).fail(function(a,b,c){
					console.log(a);
					console.log(b);
					console.log(c);
				});
			});

			// On search / clear search
			$('#search').on('focusin', function(){
				$('.search-wrapper').addClass('focus');
			});
			$('#search').on('focusout', function(){
				$('.search-wrapper').removeClass('focus');
			});
			$('#searchForm').submit(function (){
				search();
				return false;
			});
			$('#clearSearch').on('click', function(){
				$('#search').val('');
				$('#categories').val('none');
				search();
			});
			
			// Accordion for entries
			$('#results').on('click', '.card.entry header', function(){
				$(this).parent().toggleClass('open');
				$(this).addClass('toggled');
				$(this).siblings('.collapse').slideToggle();
				$('html, body').animate({ scrollTop: ($(this).offset().top - 5) }, 'slow');
			});
			$('#duplicateResults').on('click', '.card.entry header', function(){
				$(this).parent().toggleClass('open');
				$(this).addClass('toggled');
				$(this).siblings('.collapse').slideToggle();
			});

			// Next and previous buttons
			$('#results').on('click', '.next:not(.disabled)', function(){
				$(this).addClass('toggled');
				setTimeout(function(){
					callMedia('session.php', $('#categories').val(), currentPage + 1, $('#search').val());
				}, 300);
			});
			$('#results').on('click', '.previous:not(.disabled)', function(){
				$(this).addClass('toggled');
				setTimeout(function(){
					callMedia('session.php', $('#categories').val(), currentPage - 1, $('#search').val());
				}, 300);
			});

			// Back to top
			$('#results').on('click', '.top', function(e){
				$(this).addClass('toggled');
				$("html, body").animate({ scrollTop: 0 }, 'slow');
			});

			// Animation handler
			$('#results, #duplicateResults').on('webkitAnimationEnd oanimationend msAnimationEnd animationend', '.top, .card.entry header, .btn-group a, label, #main > button', function(){
				$(this).removeClass('toggled');
			});

			// Thumbnail -> embedded player
			$('#results, #duplicateResults').on('click', '.img-cover', function(){
				var videoId = $(this).parent().parent().parent().attr('id');
				var videoTitle = $(this).parent().parent().parent().find('.title').text();
				$(this).parent().html('<iframe src="https://cdnapisec.kaltura.com/p/1908451/sp/190845100/embedIframeJs/uiconf_id/38726632/partner_id/1908451?iframeembed=true&playerId=kaltura_player_1493739740&entry_id=' + videoId + '" title="' + videoTitle + '" frameborder="0" allowfullscreen="allowfullscreen" webkitallowfullscreen="webkitallowfullscreen" mozAllowFullScreen="mozAllowFullScreen"></iframe>');
			});
			
			// Interaction with upload form
			$('#uploadForm').on('focus', '#fileData, #title, #category', function(){
				$(this).parents('label').find('p').addClass('onFocus');
			});
			$('#uploadForm').on('focusout', '#fileData, #title, #category', function(){
				$(this).parents('label').find('p').removeClass('onFocus');
				checkForm();
			});

			// Load page clean
			$('#categories').val('none');
			$('#search').val('');
			callMedia('session.php', 'none', 1);


			// UPLOAD SCRIPTS



			// Gather user UVID
			var gadget = new Gadget();
			gadget.ready().then(gadget.fetch).then(function () {
				var user = gadget.get('user');
				$('#user').val(user);
			});

			$.ajax({url: 'getks.php', dataType: 'text', cache: false}).done(function(results){
				console.log('session.php contacted successfully');
				// Begin upload handle
				var categoryId = -1;
				var widget = $('#uploadHook').fileupload({
					// continue to pass this, even not used, to trigger chunk upload
					maxChunkSize: 3000000,
					dynamicChunkSizeInitialChunkSize: 1000000,
					dynamicChunkSizeThreshold: 50000000,
					dynamixChunkSizeMaxTime: 30,
					host: "https://www.kaltura.com",
					apiURL: "https://www.kaltura.com/api_v3/",
					url: "https://www.kaltura.com/api_v3/?service=uploadToken&action=upload&format=1",
					ks: results,
					fileTypes: '*.mts;*.MTS;*.qt;*.mov;*.mpg;*.avi;*.mp3;*.m4a;*.wav;*.mp4;*.wma;*.vob;*.flv;*.f4v;*.asf;*.qt;*.mov;*.mpeg;*.avi;*.wmv;*.m4v;*.3gp;*.jpg;*.jpeg;*.bmp;*.png;*.gif;*.tif;*.tiff;*.mkv;*.QT;*.MOV;*.MPG;*.AVI;*.MP3;*.M4A;*.WAV;*.MP4;*.WMA;*.VOB;*.FLV;*.F4V;*.ASF;*.QT;*.MOV;*.MPEG;*.AVI;*.WMV;*.M4V;*.3GP;*.JPG;*.JPEG;*.BMP;*.PNG;*.GIF;*.TIF;*.TIFF;*.MKV;*.AIFF;*.arf;*.ARF;*.webm;*.WEBM;*.rm;*.RM;*.ra;*.RA;*.RV;*.rv;*.aiff',
					context: '',
					categoryId: categoryId,
					messages: {
						acceptFileTypes: 'File type not allowed',
						maxFileSize: 'File is too large',
						minFileSize: 'File is too small'
					},
					android: "",
					singleUpload: "",
					progressall: function (e, data){
						var progress = parseInt(data.loaded / data.total * 100, 10);
						$('.upload.progress .bar').css('width', progress + '%');
					}

				})
				// file added
				.bind('fileuploadadd',function(e, data){
					console.log('fileuploadadd has fired');
					var uploadBoxId = widget.fileupload('getUploadBoxId',e,data);
					data.uploadBoxId = uploadBoxId;
					var uploadManager = widget.fileupload("getUploadManager");
					if (!uploadManager.hasWidget($(this)) && !widget.fileupload('option', 'android') && !widget.fileupload('option', 'singleUpload')) {
						// load the next uploadbox (anyway even if there is an error)
						widget.fileupload('addUploadBox',e,data);
					}
				})
				// actual upload start
				.bind('fileuploadsend',function(e, data){
					console.log('fileuploadsend has fired - ui should update now');
					var uploadBoxId = widget.fileupload('getUploadBoxId',e,data);
					var file = data.files[0];
					var uploadManager = widget.fileupload("getUploadManager");
					if(file.error === undefined){
						var context = widget.fileupload('option', 'context');
						// Switch out file picker for progress bar
						$('.upload.progress').show();
					}
				})
				// upload done
				.bind('fileuploaddone', function(e, data){
					var uploadBoxId = widget.fileupload('getUploadBoxId',e,data);
					var file = data.files[0];
					$('#uploadToken').val(data.uploadTokenId);
					$('.upload.progress .bar').addClass('finished').css('width', '100%');
					checkForm();
				})
				// upload error
				.bind('fileuploaderror', function(e, data){
					var uploadBoxId = widget.fileupload('getUploadBoxId',e,data);
					var uploadBox = widget.fileupload('getUploadBox',uploadBoxId);
					$("#entry_details", uploadBox).addClass('hidden');
					if (widget.fileupload('option', 'singleUpload')){
						// load the next uploadbox (if an error occured and it's a single upload do not cause a dead end for the user)
						widget.fileupload('addUploadBox',e,data);
					}
				})
				// upload cancelled
				.bind('fileuploadcancel', function(e, data){
					var uploadBoxId = widget.fileupload('getUploadBoxId',e,data);                
					var uploadBox = widget.fileupload('getUploadBox',uploadBoxId);
					$("#entry_details", uploadBox).addClass('hidden');  
					console.log('cancelled');
				});

			}).fail(function(a,b,c){console.log(a);console.log(b);console.log(c);});

			$('#submitVideo').on('click', function(){
				// Form validation
				var fileVal = $('#fileData').val();
				var titleVal = $('#title').val();
				var catVal = $('#category > option:selected').val();
				var videoProgress = $('.upload.progress .bar');

				if (fileVal === '' || titleVal === '' || catVal === 'none' || !videoProgress.hasClass('finished')) {

					if (fileVal === '') {
						$('#fileData').addClass('unfinished');
					} else {
						$('#fileData').removeClass('unfinished');
					}
					if (titleVal == '') {
						$('#title').addClass('unfinished');
					} else {
						$('#title').removeClass('unfinished');
					}
					if (catVal === 'none') {
						$('#category').addClass('unfinished');
					} else {
						$('#category').removeClass('unfinished');
					}
					if (!videoProgress.hasClass('finished') && $('.upload.progress').css('display') === 'block') {
						videoProgress.addClass('unfinished');
					} else {
						videoProgress.removeClass('unfinished');
					}

				} else {

					// Switch to 'in progress' look and submit info to Kaltura, then to the form mailer
					$(this).addClass('working');
					$('div.cover').addClass('working');
					var title = $('#title').val();
					var category = $('#category > option:selected').val() + ', 59117502' + ', 47547862';
					var token = $('#uploadToken').val();
					$.post('upload.php', { videoTitle: title, videoCategory: category, videoToken: token }, function(){
						$('#category > option:selected').val( $('#category > option:selected').text() );

						$.post('index.php', {
							user: $('#user').val(),
							title: title,
							category: $('#category > option:selected').val(),
							uploadToken: token
						}, function(){
							$('div.cover, #submitVideo').removeClass('working');
							$('.upload-fileChooser, .upload-formInputs, #closeFormButton, #possibleDuplicates').hide();
							$('#success').show();
						});

					});

				}


			});
		</script>
	<!-- ouc:button --><a href="http://a.cms.omniupdate.com/10?skin=uvu&amp;account=10733374&amp;site=UVU_Dev_Site&amp;action=de&amp;path=/_resources/gadgets/kaltura-videos-2.0/index.php" target="_top"></a><!-- /ouc:button --></body>
</html>
