function addLine(el) {
	var id = el.attr('id');
	var c = el.children('div').last().children('input').last().attr('id');
	var n = c.substr(-1);
	n++;
	var content = '<div><label for="'+id+'_name_'+n+'">Name:</label>' +
	'<input type="text" name="'+id+"_name_"+n+'" id="'+id+"_name_"+n+'" value="" size="30" style="width:50%;"/>' +
	'<label for="'+id+'_value_'+n+'">Price Increase: </label>' +
	'<input type="text" name="'+id+"_value_"+n+'" id="'+id+"_value_"+n+'" value="" size="6" />' +
	'<span style="cursor:pointer;color:#21759B;" onclick="jQuery(this).parent().remove();">X</span></div>';
	el.children('.add').before(content)
}

function detachImage(id,reload) {
	if ( confirm("Are you sure you want to detach this image?") ) {
		jQuery.get( templateurl+"/include/images-meta/ajax.php?id="+id+"&detachImage="+id, function(data) {
			if (data) {
				jQuery('#image-meta-'+id).remove();
				if (reload) location.reload();
			}
		} );
	}
}

function deleteImage(id) {
	if ( confirm("Are you sure you want to delete this image?") ) {
		jQuery.get( templateurl+"/include/images-meta/ajax.php?id="+id+"&deleteImage="+id, function(data) {
			if (data) {
				jQuery('#image-meta-'+id).remove();
			}
		} );
	}
}
