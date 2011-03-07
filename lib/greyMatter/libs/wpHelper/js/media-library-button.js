/*
 * Media Library Button
 */
function WPHUpdatePostMeta(parentPostID, metaKey, data, nonce)
{
	jQuery.post(ajaxurl, {
		action: 'WPHUpdatePostMeta',
		parent_post_id: parentPostID,
		meta_key: metaKey,
		meta_data: data,
		ajax_nonce: nonce
		
	}, function(){
		var win = window.dialogArguments || opener || parent || top;
		win.WPHUpdateMetaInput(metaKey, data);
	});
}


var WPHUpdateMetaInput;
(function($){	

/* Updates a custom meta field with a value
 * Note: input name attr must = meta key. Meant to work with WPH MetaBox class
 * 
 * @param postID: int
 * @param metaKey: str
 * @param data: mixed
 */
WPHUpdateMetaInput = function(metaKey, data)
{
	$('input[name="'+metaKey+'"]').val(data);
};

})(jQuery);

