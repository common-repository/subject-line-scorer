jQuery(document).ready(function($){
	
	setInterval(function(){
		if( $('.verify_title').length == 0 ){
			console.log('append');
			//$('#editor .edit-post-header__settings ').prepend('<button type="button" aria-disabled="false" aria-expanded="false" class="components-button   is-button btn-custom-green verify_title"  title="Subject Line Scorer">Score Subject Line</button>');
			$('#wp-content-media-buttons').append('<span class="tw-bs4 classic_title_check"><button type="button"  class="components-button   btn btn-custom-green btn-sm verify_title" title="Subject Line Scorer">Score Subject Line</button></span>');
		}
	}, 1000)
	
	// trace active textqarea
	
	$('body').on('focus', 'textarea', function(){	 
		$('#last_ta_id').val( $(this).attr('id') );		 	
	})
	$('body').on('focus', '.wp-block', function(){	 
		$('#last_ta_id').val( $(this).attr('id') );		 	
	})
	
	// apply title
	
	$('body').on('click', '.apply_title_button', function(){
		$('.single_title_text').each(function(){
			
			
			//var parent = $('.single_title_text.active_title').parents('.single_title_line');
			var parent = $(this).parents('.single_title_line');
			var picked_json = parent.attr('data-params');
			
			
			var obj = $.parseJSON( picked_json );
			
			var string_to_replace = obj.title;
			var string_to_replace = parent.attr('data-title');
			var points = obj.points;
			
			var shortcode_string = '[title_replaced title="'+string_to_replace.replace('"', '\"')+'"]';
			// replace magic
			
			// if tinymce
			var tinymceActive = (typeof tinyMCE != 'undefined') && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
			
			console.log( 'tinymce' );
			console.log( tinymceActive );
			
			
			if( tinymceActive == true ){
				var all_content =    tinyMCE.activeEditor.getContent();
				console.log( all_content );
				all_content = all_content.replace( string_to_replace, shortcode_string );
				tinymce.activeEditor.setContent( all_content );
				//tinymce.activeEditor.execCommand('mceReplaceContent', false, '<b>{$selection}</b>' );
				}else{
				// if textarea or guttenberg
				var latest_editor = $('#last_ta_id').val();
				var all_content = $('#'+latest_editor+' .components-autocomplete ').html();
				
				
				all_content = all_content.replace( /&nbsp;/gi, "");
				all_content = all_content.replace( /&amp;/g, '&');
				
				
				console.log( '#'+latest_editor+' .components-autocomplete ' );
				console.log( all_content );
				console.log( string_to_replace );
				console.log( shortcode_string );
				
				
				
				all_content = all_content.replace( string_to_replace, shortcode_string );
				$('#'+latest_editor+' .components-autocomplete ').html( all_content );
			}
			
			
		})
		
		$('.fancybox-close-small').click();
	})
	
	$('body').on('click', '#wpadminbar', function(){
		/*
			wp.data.dispatch( 'core/editor' ).editPost( { title: 'My New Title' } );
			wp.data.dispatch( 'core/editor' ).editPost( { content: 'My New Title' } );
			wp.data.dispatch( 'core/editor' ).resetPost();
			wp.data.dispatch( 'core/editor' ).refreshPost();
			wp.data.dispatch( 'core/editor' ).refreshPost();
			wp.data.dispatch( 'core/editor' ).removeBlock();
		*/
		tinymce.activeEditor.setContent( '[title_replaced title="ss"]' );
		
		
	})
	
	$('body').on('click', '.fake_del_button', function(){
		
		var active_pointer = $('.active_title').parents('.single_title_line');
		
		if( active_pointer.next('.single_title_line').length > 0 ){
			var next_pnt = active_pointer.next('.single_title_line');
			$('.single_title_text', next_pnt).addClass('active_title');
			
			}else{
			/*
				if( $('.single_title_line').length == 2 ){
				$('.single_title_line:first').click();
				
				// close tab
				$('.fancybox-close-small').click();
				}
			*/
			if( $('.single_title_line').length >= 2 ){
				$('.single_title_line:first').click();
				
				
			}
			
		}
		
		active_pointer.replaceWith('');
		$('.active_title').click();
		
		//patch for isse
		//if( $('.single_title_line').length == 1 ){	 
		// close tab
		//		$('.fancybox-close-small').click();
		//	}
		
		// process content replacement
		
	})
	$('body').on('click', '.cancel_button', function(e){
		e.preventDefault();
		$('.fancybox-close-small').click();
	})
	$('body').on('click', '.single_title_line', function(){
		$('.active_title ').removeClass('active_title');
		$('.single_title_text', this).addClass('active_title');
		
		// perform graph
		var json = $.parseJSON( $(this).attr('data-params') );
		$('#g2').html('');
		var g2 = new JustGage({
			id: 'g2',
			value: json.points,
			min: 0,
			max: 100,
			symbol: ' ',
			pointer: false,
			pointerOptions: false,
			
			customSectors: [{
				color : "#FD7378",
				lo : 0,
				hi : 39
				},{
				color : "#FFC959",
				lo : 40,
				hi : 74
				},{
				color : "#7FC698",
				lo : 74,
				hi : 100
			}],
			
			gaugeWidthScale: 1,
			counter: true,
			hideMinMax: true,
			levelColorsGradient: false
		});
		
		
		/*
			
			customSectors: {
			percents: true,
			ranges: [{
			color : "#000000",
			lo : 0,
			hi : 50
			},{
			color : "#ffffff",
			lo : 50,
			hi : 100
			} ]
			},
			
		*/
		
		$('.right_subject_title').html( json.title );
	})
	
	/*
		<script>
		var g1;
		document.addEventListener("DOMContentLoaded", function(event) {
		var g2 = new JustGage({
		id: \'g2\',
		value: 45,
		min: 0,
		max: 100,
		symbol: \'%\',
		pointer: true,
		pointerOptions: {
		toplength: -15,
		bottomlength: 10,
		bottomwidth: 12,
		color: \'#8e8e93\',
		stroke: \'#ffffff\',
		stroke_width: 3,
		stroke_linecap: \'round\'
		},
		gaugeWidthScale: 1,
		counter: true
		});
		});
		</script>
	*/
	
	// classic editor add button
	
	
	
	// guttenberg editor
	
	$('body').on('click', '.verify_title', function(){
		
		//$('#fake_new_block').click();
		// return false;
		
		var tinymceActive = (typeof tinyMCE != 'undefined') && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
		
		var selected_text = getSelectionText();
		
		// patch js
		console.log( 'trace'  );		
		console.log( selected_text  );		
		
		
		selected_text = selected_text.replace( /&nbsp;/gi, "");
		
		selected_text = selected_text.replace( /&amp;/g, '&');
		
		
		console.log( selected_text  );
		
		
		if( tinymceActive == true ){
			selected_text = tinyMCE.activeEditor.selection.getContent();
			selected_text = selected_text.replace(/<br\/>/g, "\n");
			selected_text = selected_text.replace(/<br \/>/g, "\n");
		}
		
		if( selected_text == '' ){
			$('#submission_message').html( wta_local_data.empty_text );
			$('#click_submission_message').click();
			return true;
		}
		
		var data = {
			string  : selected_text,
			action : 'verify_title'
		}
		
		console.log( data );
		
		jQuery.ajax({url: wta_local_data.ajaxurl,
			type: 'POST',
			data: data,            
			beforeSend: function(msg){
				jQuery('body').append('<div class="global_loader"></div>');
			},
			success: function(msg){
				console.log( msg );
				
				$('.global_loader').fadeOut(500, function(){
					$('.global_loader').replaceWith('');
				});
				
				var obj = $.parseJSON( msg );
				
				if( obj.result == 'success'  ){
					$('#new_score_cont').html( obj.html );
					$('#fake_new_block').click();
					$('.single_title_text.active_title').click();
					}else{
					$('#submission_message').html( obj.html );
					$('#click_submission_message').click();;
				}
				
				
				
				
				
				
				
			} , 
			error:  function(msg) {
				
			}          
		});
		
		
	})
	
	
	function getSelectionText() {
		var text = "";
		
		if (window.getSelection) {
			text = window.getSelection().toString();
			} else if (document.selection && document.selection.type != "Control") {
			text = document.selection.createRange().text;
		}
		return text;
	}
	
	$('#submit_image').click(function( e ){
		
		var is_error_image = 0;
		var is_error_captcha = 0;
		if( $('#image_id').val() == "" ){
			is_error_image = 1;
		}
		if( $('#frame_id').val() == "" ){
			is_error1 = 1;
		}
		
		
		
		// recaptcha validation
		
		var data = {
			response  : $('#g-recaptcha-response').val(),
			action : 'verify_captcha'
		}
		jQuery.ajax({url: wws_local_data.ajaxurl,
			type: 'POST',
			data: data,            
			beforeSend: function(msg){
				jQuery('#submit_image').attr("disabled", true);
			},
			success: function(msg){
				
				
				console.log( msg );
				
				jQuery('#submit_image').attr("disabled", false);
				var obj = jQuery.parseJSON( msg );
				
				console.log( obj );
				console.log( obj.success );
				if( obj.success == false ){
					
					is_error_captcha = 1;
					e.preventDefault();
					alert('Please, check captcha!');
					}else{
					if( is_error_image == 0 ){
						$('#hidden_submit').click();
					}
				}
				
			} , 
			error:  function(msg) {
				
			}          
		});
		if( is_error_image == 1 ){
			e.preventDefault();
			alert('Please, select image!');
		}
		if( is_error_captcha == 1 ){
			
		}
	})
	
	
	// Uploading files	var file_frame;	
	// Uploading files
	var file_frame;
	
	jQuery('.upload_image').live('click', function( event ){
		
		var parent = $(this).parents('.media_upload_block');
		var if_single = $(this).attr('data-single');
		
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		
		// Create the media frame.
		if( if_single == 1 ){
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
					text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			}else{
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
					text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: true  // Set to true to allow multiple files to be selected
			});
		}
		
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			if( if_single == 1 ){
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				$('.item_id', parent).val( attachment.id );
				$('.image_preview', parent).html( '<img src="'+attachment.url+'" />' );
				// Do something with attachment.id and/or attachment.url here
				}else{
				var selection = file_frame.state().get('selection');	
				
				selection.map( function( attachment ) {						
					attachment = attachment.toJSON();					
					console.log( attachment.id );
					console.log( attachment.url );
					
					var this_val = [];
					if( $('.item_id', parent).val() != '' ){
						
						var this_tmp = $('.item_id', parent).val();						
						this_val = this_tmp.split(',');
					}
					this_val.push( attachment.id );
					$('.item_id', parent).val( this_val.join(',') );
					
					$('.image_preview', parent).append( '<img src="'+attachment.url+'" />' );
				})
			}
		});
		
		// Finally, open the modal
		file_frame.open();
	});
	
	
	
});