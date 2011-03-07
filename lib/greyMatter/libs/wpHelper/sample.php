<?php 
/*
 * Author: Shane Kuester
 * Author URI: darkgreymedia.com
 * Author Email: shane@darkgreymedia.com
 * 
 * The following is a sample implementation of the various
 * classes included with wpHelper. All classes accept arguments
 * in JSON form, or as an associative array.
 */

//include the main php which loads each helper class
//NOTE: WPHELPER_PATH must be defined first in wphelper-config.php <=================== NOTE!
require_once('wpHelper/wphelper.php');


//Create a post type (with taxonomy)
//NOTE: Never use "type" as a taxonomy tag - this will mess up the media library
new postType('{
			"label" : "Portfolio", 
			"singular_label" : "Portfolio Item",
			"add_new":"Add Item",
			"tag" : "portfolio",
			"taxonomy" : {
				"tag" : "gallery",
				"label" : "Galleries",
				"singular_label" : "Gallery",
				"for" : "portfolio"
				}
			}
		');


//Create a taxonomy
new taxonomy('{
	"tag":"book",
	"label":"Books",
	"singular_label":"Book",
	"for":"post"
	}');

//Create Custom Columns
new customColumns('{
	"post_type" : "portfolio",
	 "columns": [
	 	{"tag":"title", "label":"Entry Title"},
	 	{"tag":"date", "label":"Date"},
	 	{"tag":"mod_date", "label":"Modified Date", "content":{ "post":"date-m.d.y" }},
	 	{"tag":"col1", "label":"Col1 Label", "content":{ "taxonomy" : "gallery"}},
	 	{"tag":"col2", "label":"Col2 Label", "content":{ "post" : "excerpt"}},
	 	{"tag":"col3", "label":"Col3 Label", "content":{ "post" : "thumbnail-medium" }},
	 	{"tag":"col4", "label":"Multiple Elements", "content":{ "meta" : "_meta_key", "post" : "permalink" }}
	 	]
}');

//Create a Meta Box
new metaBox('{
		"box_id": "new-meta-box",
		"box_title" : "Custom Options",
		"add_to" : "page",
		"inputs" : [
			{"type":"text", "label":"Text Input", "name":"_text-input", "description":"This is a description."},
			{"type":"select", "label":"Selection Input", 	"name":"_select-input", "description":"Select something.",
				"options": { 
					"value1":"label1", 
					"value2":"label2" 
					}
			},
			{"type":"radio", "label":"Radio Input", "name":"_radio-input", "description":"", 
				"options":{
					"value1":"label1", 
					"value2":"label2"
					}
			},
			{"type":"checkbox", "label":"Multiple CheckBoxes",	"name":"_m-checkbox",
				"options":{
					"value1":"label1", 
					"value2":"label2"
					}
			},
			{"type":"checkbox", "label":"Single CheckBox", "name":"_single-checkbox", "option_label":"label"},
			{"type":"custom", "label":"Custom HTML", "html":"<h1>Hello World</h1>"}
			]
		}');


//Create an Admin Panel
new adminPanel('{
	"id":"new-panel",
	"label":"New Admin Panel",
	"page_title":"New Admin Panel",
	"add_to":"settings",
	"form": [
		{"type":"text", "label":"Text Input", "name":"_input", "description":"This is a description."},
		{"type":"select", "label":"Selection Input", "name":"_select", "description":"Select something.",
			"options": { 
				"value1":"label1", 
				"value2":"label2" 
				}
		},
		{"type":"radio", "label":"Radio Input", "name":"_radio", "description":"", 
			"options":{
				"value1":"label1", 
				"value2":"label2"
				}
		},
		{"type":"checkbox", "label":"Multiple CheckBoxes",	"name":"_m-checkbox",
			"options":{
				"value1":"label1", 
				"value2":"label2"
				}
		},
		{"type":"checkbox", "label":"Single Checkbox", "name":"_single-checkbox", "option_label":"label"},
		{"type":"custom", "label":"Custom HTML", "html":"<h1>Hello World</h1>", "description":"Custom HTML in the input column"},
		{"type":"custom_row", "html":"<img src=\"http://www.google.com/intl/en_ALL/images/logos/images_logo_lg.gif\" alt=\"google\"/>"},
		{"type":"custom_row", "label":"<h1>label td</h1>", "input":"<h2>Input td</h2>", "description":"<h3>Description td</h3>"}
	]
}');


//Register a Menu (and enable menu support)
new menu('location-tag', 'Location Title');
/*
 ALTERNATE SYNTAXES
(1) using JSON
	new menu('{"location-tag":"Location Title"}');
	
(2) using assoc array
	new menu(array('location-tag'=>'Location Title'));
*/
//Alternate way of registering multiple menus
	new menus('{
		"location-tag1":"Location Title1",
		"location-tag2":"Location Title2"
		}');
	



?>