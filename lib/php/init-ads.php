<?php 
//ADS
new postType('{
	"label":"Ads",
	"singular_label" : "Ad",
	"add_new" : "Create Ad",
	"tag": "ads",
	"publicly_queryable" : "FALSE",
	"exclude_from_search" : "TRUE",
	"taxonomy" : {
			"tag":"location",
			"label" :"Locations",
			"singular_label" : "Location"
			}
}');
//Image Size Notification
new metaBox('{
	"add_to":"ads",
	"box_id":"ads-size",
	"box_title":"Ad Size Requirement",
	"inputs":[
		{"html":"<p>Ad Image Must Be <strong>285 x 250</strong></p><br/><p>Featured Ads must be <strong>470 x 180</strong></p>"}
	]	
}');
//Ad Link
new metaBox('{
	"add_to":"ads",
	"box_id":"ad-link",
	"box_title":"Ad Link",
	"inputs": [
		{ "type":"text", "label":"Ad URL", "name":"_ad-link" }
	]
}');

?>