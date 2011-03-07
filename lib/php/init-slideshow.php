<?php 

new postType('{
	"label":"Slideshow",
	"singular_label" : "Slide",
	"add_new" : "Add Slide",
	"tag": "slideshow"
}');

//Image Size Notification
new metaBox('{
	"add_to":"slideshow",
	"box_id":"slide-size",
	"box_title":"Slide Size Requirement",
	"inputs":[
		{"html":"<p>Ad Image Must Be 675x350 Pixels.</p>"}
	]	
}');

//Ad Link
new metaBox('{
	"add_to":"slideshow",
	"box_id":"slide-link",
	"box_title":"Slide Link",
	"inputs": [
		{ "type":"text", "label":"URL", "name":"_slide-link" }
	]
}');

?>