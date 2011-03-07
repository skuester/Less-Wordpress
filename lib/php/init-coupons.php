<?php 

new postType('{
	"label":"Coupons",
	"singular_label" : "Coupon",
	"add_new" : "Create Coupon",
	"tag": "coupons",
	"publicly_queryable" : "FALSE",
	"exclude_from_search" : "TRUE",
	"taxonomy" : {
			"tag":"promotion",
			"label" :"Promotions",
			"singular_label" : "Promotion",
			"for" : "coupons"
			}
}');

//Coupon Options
new metaBox('{
	"add_to":"coupons",
	"box_id":"coupon-options",
	"box_title":"Coupon Options",
	"inputs": [
		{ "type":"text", "label":"Expiration", "name":"_coupon-expiration" },
		{ "type":"text", "label":"Coupon Code (optional)", "name":"_coupon-code" }
	]
}');