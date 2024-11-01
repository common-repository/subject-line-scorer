jQuery(document).ready( function($){
	$('.title_pop').qtip({
    content: {
        text: function(event, api) {
			return $('.content_'+$(this).attr('data-id') ).html() ;
			
        }
    },
	position: {
        my: 'left center',  // Position my top left...
        at: 'right center', // at the bottom right of...
        
    },
	style: {
        classes: 'qtip-light qtip-shadow'
    },
	hide: {when: {event:'mouseout unfocus'}, fixed: true, delay: 500}
});
	
}) // global end
