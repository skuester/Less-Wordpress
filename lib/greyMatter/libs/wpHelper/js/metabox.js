/*
 * MetaBox
 */
function WPHDeletePostMeta(metaKey)
{
	//TODO: Make this perform an ajax delete
	/*jQuery.post(ajaxurl, {
		action: 'WPHDeletePostMeta',
		meta_key: metaKey,
		ajax_nonce: nonce */
		
	
		WPHDeleteMetaInput(metaKey);

}


var WPHDeleteMetaInput;
(function($){	

	
WPHDeleteMetaInput = function(metaKey){
	$('input[name="'+metaKey+'"]').val('');
};

})(jQuery);

