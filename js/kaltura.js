$(function(){

	// Gather user UVID
	var gadget = new Gadget();
	gadget.ready().then(gadget.fetch).then(function () {
		var user = gadget.get('user');
		$('#user').val(user);
	});
	
	var searchRegex; // Array of video IDs for regex searching later

	// Navigation tabs and element setup
	$('#browse-tab').on('click', function(){
		$('.upload').hide();
		$('.browse').show();
		$('#browse-tab').addClass('active');
		$('#upload-tab').removeClass('active');
	});
	$('#upload-tab').on('click', function(){
		$('.browse').hide();
		$('.upload').show();
		$('#upload-tab').addClass('active');
		$('#browse-tab').removeClass('active');
	});
	$('.upload, #progress, #success').hide();

	// Get #mediaList from Kaltura
	$.ajax({url: './medialist.php', cache: false}).done(function(results){
		$('#mediaList').html('');
		var html = ''; // Begin media list population string
		results.entries.objects.reverse(); // Newest videos first
		searchRegex = 'entry_id=(';
		for (var i = 0; i < results.entries.objects.length; i++) {
			
			searchRegex += (results.entries.objects.length - 1) > i ? results.entries.objects[i].id + '|' : results.entries.objects[i].id;

			results.entries.objects[i].embedString = '<iframe src="http://cdnapi.kaltura.com/p/1908451/sp/190845100/embedIframeJs/uiconf_id/32613972/partner_id/1908451?iframeembed=true&playerId=kaltura_player_1473788560&entry_id=' + results.entries.objects[i].id + '" width="640" height="360" frameborder="0" allowfullscreen="allowfullscreen" title="' + results.entries.objects[i].name + '"></iframe>'; // Create string to be used in the editor

			html += "<li id='" + results.entries.objects[i].id + "' class='browse-listItem " + results.entries.objects[i].tags + "'><h2>" + results.entries.objects[i].name + "</h2><div class='img'><img src='" + results.entries.objects[i].thumbnailUrl + "' alt='" + results.entries.objects[i].name + "' /></div><br /><div class='input-group'><span>&lt;/&gt;</span><input type='text' readonly='readonly' value='" + results.entries.objects[i].embedString + "' onfocus='$(this).select()' /></div></li>"; // Build each item of the list

		}
		$('#mediaList').append(html); // Attach list to page
		if ($('#categories > option:selected').val() === 'none') {
			$('#mediaList li').hide(); // Page loads empty
		}
		searchRegex += ')';
		console.log('RegEx search string:')
		console.log(searchRegex);
	}).fail(function(a,b,c){console.log(a);console.log(b);console.log(c);});

	// Sort categories based on dropdown
	$('#categories').on('change', function(){
		if (this.value === '') {
			$('#mediaList li').hide();
		} else {
			$('#mediaList li').hide();
			$('#mediaList li[class*="' + this.value + '"]').show();
		}
	});

	// Clicking on thumbnail replaces it with video preview
	$('#mediaList').on('click', 'div.img', function(){
		var vidID = $(this).parent().attr('id');
		$(this).replaceWith('<iframe src="http://cdnapi.kaltura.com/p/1908451/sp/190845100/embedIframeJs/uiconf_id/32613972/partner_id/1908451?iframeembed=true&playerId=kaltura_player_1473788560&entry_id=' + vidID + '" title="' + $(this).children('img').attr('alt') + '" frameborder="0" allowfullscreen="allowfullscreen"></iframe>');
	});

	// Generate Kaltura session and haldle video uploads
	$.ajax('./session.php').done(function(results){

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
				$('#progress .bar').css('width', progress + '%');
			}

		})
		// file added
		.bind('fileuploadadd',function(e, data){
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
			var uploadBoxId = widget.fileupload('getUploadBoxId',e,data);
			var file = data.files[0];
			var uploadManager = widget.fileupload("getUploadManager");
			if(file.error === undefined){
				var context = widget.fileupload('option', 'context');
				// Switch out file picker for progress bar
				$('#progress').show();
				$('#fileData').hide();
			}
		})
		// upload done
		.bind('fileuploaddone', function(e, data){
			var uploadBoxId = widget.fileupload('getUploadBoxId',e,data);
			var file = data.files[0];
			$('#uploadToken').val(data.uploadTokenId);
			$('#submitVideo').removeClass('off');
			$('#progress .bar').addClass('finished');
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
		});
		// bind to the first upload input
		$("#fileData").bind('change', function (e) {
			$('#uploadHook').fileupload('add', {
				fileInput: $(this), 
				uploadBoxId: 1
			});
		});

	}).fail(function(a,b,c){console.log(a);console.log(b);console.log(c);});

	// When clicking 'Submit'
	$('#submitVideo').on('click', function(){

		// Form validation
		var fileVal = $('#fileData').val();
		var titleVal = $('#title').val();
		var catVal = $('#category > option:selected').val();
		var videoProgress = $('#progress .bar');

		if (fileVal === '' || titleVal === '' || catVal === 'none' || !videoProgress.hasClass('finished')) {

			if (fileVal === '') {
				$('#fileData').css('border-color', '#f00');
			} else {
				$('#fileData').css('border-color', '#999');
			}
			if (titleVal == '') {
				$('#title').css('border-color', '#f00');
			} else {
				$('#title').css('border-color', '#999');
			}
			if (catVal === 'none') {
				$('#category').css('border-color', '#f00');
			} else {
				$('#category').css('border-color', '#999');
			}
			if (!videoProgress.hasClass('finished') && $('#progress').css('display') === 'block') {
				videoProgress.css('border-color', '#f00');
			} else {
				videoProgress.css('border-color', '#999');
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
					$('.upload-fileChooser, .upload-formInputs').hide();
					$('#success').show();
				});

			});
			
		}


	});

	// Reset all page elements when clicking on 'Go back'
	$('#goback').on('click', function(){
		$('.upload-fileChooser, .upload-formInputs, #fileData').show();
		$('#fileData, #title').val('');
		$('#category').val('none');
		$('#submitVideo').addClass('off');
		$('#progress .bar').removeClass('finished').css('width', '0%');
		$('#progress, #success').hide();
	});


});
