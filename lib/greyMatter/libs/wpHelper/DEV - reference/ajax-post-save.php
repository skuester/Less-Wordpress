<?php
/*
Plugin Name: Ajax Post Save
Plugin URI: http://blog.bull3t.me.uk/projects/ajax-post-save/
Description: Allows you to save any WordPress post/page without needing to reload the page - with use of Ajax.
Version: 1.1
Author: Philip Hughes (Bull3t)
Author URI: http://www.bull3t.me.uk/
*/

/*	Copyright 2007 Philip Hughes (Bull3t)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	
*/


/*     Variables     */
@define('AJAXPOSTSAVE_VERSION', '1.1'); // Version
@define('AJAXPOSTSAVE_ROOT', get_bloginfo('url') . '/' . PLUGINDIR . "/" . str_replace("ajax-post-save.php", "", plugin_basename(__FILE__))); // Ajax Post Save directory


/*     WordPress Hooks     */
add_action('admin_menu', 'asp_options_page');
add_action('admin_head', 'asp_inject');


/*     Variables     */
add_option('ajaxpostsave_remove_button', false);
add_option('ajaxpostsave_save_all', true);
add_option('ajaxpostsave_button_value', 'Save Through Ajax');
add_option('ajaxpostsave_auto_save_interval', 120);
add_option('ajaxpostsave_show_frame', false);


/*     Inject JavaScript     */
function asp_inject() {
	?>
	<script type='text/javascript' src='../../../wp-includes/js/tw-sack.js'></script>
	
	<?php if (get_option('ajaxpostsave_remove_button') == true) { ?>
		<script type="text/javascript">
			addLoadEvent(asp_remove_button)
			function asp_remove_button() {
				var unused = document.getElementById('save');
				unused.parentNode.removeChild(unused);
			}
		</script>
	<?php } ?>
	
	<script type="text/javascript">
		addLoadEvent(asp_add_button)
		
		function asp_add_button() {
			document.getElementById('autosave').innerHTML = '<input type="button" value="<?php echo get_option('ajaxpostsave_button_value'); ?>" id="ajaxsave" name="ajaxsave" onclick="javascript:autosave()" />';
		}
		
		function asp_check_button() {
			var button = document.getElementById('ajaxsave');
			if (isNaN(button)) {
				asp_add_button();
			}
		}
		
		var autosavePeriodical;
		
		function autosave_start_timer() {
			var form = $('post');
			
			autosavePeriodical = new PeriodicalExecuter(autosave, autosaveL10n.autosaveInterval);
			
			if(form.addEventListener) {
				form.addEventListener("submit", function () { autosavePeriodical.currentlyExecuting = true; }, false);
			}
			
			if(form.attachEvent) {
				form.save ? form.save.attachEvent("onclick", function () { autosavePeriodical.currentlyExecuting = true; }) : null;
				form.submit ? form.submit.attachEvent("onclick", function () { autosavePeriodical.currentlyExecuting = true; }) : null;
				form.publish ? form.publish.attachEvent("onclick", function () { autosavePeriodical.currentlyExecuting = true; }) : null;
				form.deletepost ? form.deletepost.attachEvent("onclick", function () { autosavePeriodical.currentlyExecuting = true; }) : null;
			}
		}
		addLoadEvent(autosave_start_timer)
		
		function autosave_cur_time() {
			var now = new Date();
			return "" + ((now.getHours() >12) ? now.getHours() -12 : now.getHours()) + 
			((now.getMinutes() < 10) ? ":0" : ":") + now.getMinutes() +
			((now.getSeconds() < 10) ? ":0" : ":") + now.getSeconds();
		}
		
		function autosave_update_nonce() {
			var response = nonceAjax.response;
			document.getElementsByName('_wpnonce')[0].value = response;
		}
		
		function autosave_update_post_ID() {
			var response = autosaveAjax.response;
			var res = parseInt(response);
			var message;
		
			if(isNaN(res)) {
				if(response = "") {
					responce = "Could not connect to your database and/or the Ajax script.";
				}
				
				message = autosaveL10n.errorText.replace(/%response%/g, response);
			} else {
				message = autosaveL10n.saveText.replace(/%time%/g, autosave_cur_time());
				$('post_ID').name = "post_ID";
				$('post_ID').value = res;
				// We need new nonces
				nonceAjax = new sack();
				nonceAjax.element = null;
				nonceAjax.setVar("action", "autosave-generate-nonces");
				nonceAjax.setVar("post_ID", res);
				nonceAjax.setVar("cookie", document.cookie);
				nonceAjax.setVar("post_type", $('post_type').value);
				nonceAjax.requestFile = autosaveL10n.requestFile;
				nonceAjax.onCompletion = autosave_update_nonce;
				nonceAjax.method = "POST";
				nonceAjax.runAJAX();
				$('hiddenaction').value = 'editpost';
			}
			$('autosave').innerHTML = message;
			autosave_enable_buttons();
		}
		
		function autosave_loading() {
			$('autosave').innerHTML = autosaveL10n.savingText;
		}
		
		function autosave_saved() {
			var response = autosaveAjax.response;
			var res = parseInt(response);
			var message;
		
			if(isNaN(res)) {
				if(response = "") {
					responce = "Could not connect to your database and/or the Ajax script.";
				}
			
				message = autosaveL10n.errorText.replace(/%response%/g, response);
				$('autosave').innerHTML = message + '<input type="button" value="Save Through Ajax" onclick="javascript:autosave()" style="margin-left:5px;" />';
			} else {
				message = autosaveL10n.saveText.replace(/%time%/g, autosave_cur_time());
				$('autosave').innerHTML = message + '<input type="button" value="Save Through Ajax" onclick="javascript:autosave()" style="margin-left:5px;" />';
			}
			autosave_enable_buttons();
			asp_check_button();
		}
		
		function autosave_disable_buttons() {
			var form = $('post');
			form.save ? form.save.disabled = 'disabled' : null;
			form.submit ? form.submit.disabled = 'disabled' : null;
			form.publish ? form.publish.disabled = 'disabled' : null;
			form.deletepost ? form.deletepost.disabled = 'disabled' : null;
			setTimeout('autosave_enable_buttons();', 1000); // Re-enable 1 sec later.  Just gives autosave a head start to avoid collisions.
		}
		
		function autosave_enable_buttons() {
			var form = $('post');
			form.save ? form.save.disabled = '' : null;
			form.submit ? form.submit.disabled = '' : null;
			form.publish ? form.publish.disabled = '' : null;
			form.deletepost ? form.deletepost.disabled = '' : null;
		}
		
		function autosave() {
			var form = $('post');
			var rich = ((typeof tinyMCE != "undefined") && tinyMCE.getInstanceById('content')) ? true : false;
		
			autosaveAjax = new sack();
		
			/* Gotta do this up here so we can check the length when tinyMCE is in use */
			if ( typeof tinyMCE == "undefined" || tinyMCE.configs.length < 1 || rich == false ) {
				autosaveAjax.setVar("content", form.content.value);
			} else {
				// Don't run while the TinyMCE spellcheck is on.
				if(tinyMCE.selectedInstance.spellcheckerOn) return;
				tinyMCE.wpTriggerSave();
				autosaveAjax.setVar("content", form.content.value);
			}
		
			if(form.post_title.value.length == 0 || form.content.value.length == 0)
				return;
		
			autosave_disable_buttons();
		
			cats = document.getElementsByName("post_category[]");
			if (cats) {
				goodcats = ([]);
				for(i=0;i<cats.length;i++) {
					if(cats[i].checked)
						goodcats.push(cats[i].value);
				}
				catslist = goodcats.join(",");
			}
		
			autosaveAjax.setVar("action", "autosave");
			autosaveAjax.setVar("cookie", document.cookie);
			autosaveAjax.setVar("catslist", catslist);
			autosaveAjax.setVar("post_ID", $("post_ID").value);
			autosaveAjax.setVar("post_title", form.post_title.value);
			autosaveAjax.setVar("post_type", form.post_type.value);
			
			<?php if (get_option('ajaxpostsave_save_all') == true) { ?>
				// Post Status
				for (i = 0; i < form.post_status.length; i++) {
					if (form.post_status[i].checked) {
						autosaveAjax.setVar("post_status", form.post_status[i].value);
					}
				}
				
				// Comment Status
				if (form.comment_status.checked) {
					autosaveAjax.setVar("comment_status", 'open');
				} else {
					autosaveAjax.setVar("comment_status", 'closed');
				}
				
				// Ping Status
				if (form.ping_status.checked) {
					autosaveAjax.setVar("ping_status", 'open');
				} else {
					autosaveAjax.setVar("ping_status", 'closed');
				}
				
				// Excerpt
				if(form.excerpt) {
					autosaveAjax.setVar("excerpt", form.excerpt.value);
				}
				
				// Password
				if(form.post_password) {
					autosaveAjax.setVar("post_password", form.post_password.value);
				}
				
				// Slug
				if (form.post_name) {
					autosaveAjax.setVar("post_name", form.post_name.value);
				}
				
				// Author
				if (form.post_author) {
					autosaveAjax.setVar("post_author", form.post_author.value);
				}
				
				// Author Override			
				if (form.post_author_override) {
					autosaveAjax.setVar("post_author", form.post_author_override.value);
				}
				
				// Page Menu Order
				if (form.menu_order) {
					autosaveAjax.setVar("menu_order", form.menu_order.value);
				}
				
				// Page Template
				if (form.page_template) {
					autosaveAjax.setVar("page_template", form.page_template.value);
				}
				
				// Parent Page
				if (form.parent_id) {
					autosaveAjax.setVar("post_parent", form.parent_id.value);
				}
			<?php } ?>
			
			// Content
			if ( typeof tinyMCE == "undefined" || tinyMCE.configs.length < 1 || rich == false ) {
				autosaveAjax.setVar("content", form.content.value);
			} else {
				tinyMCE.wpTriggerSave();
				autosaveAjax.setVar("content", form.content.value);
			}
		
			// Send Ajax
			autosaveAjax.requestFile = autosaveL10n.requestFile;
			autosaveAjax.method = "POST";
			autosaveAjax.element = null;
			autosaveAjax.onLoading = autosave_loading;
			autosaveAjax.onInteractive = autosave_loading;
			if(parseInt($("post_ID").value) < 1)
				autosaveAjax.onCompletion = autosave_update_post_ID;
			else
				autosaveAjax.onCompletion = autosave_saved;
			autosaveAjax.runAJAX();
		}
		
		/* <![CDATA[ */
			autosaveL10n = {
				autosaveInterval: "<?php echo get_option('ajaxpostsave_auto_save_interval'); ?>",
				errorText: "Error: %response%!",
				saveText: "Saved at %time%.",
				requestFile: "<?php echo AJAXPOSTSAVE_ROOT; ?>admin-ajax.php",
				savingText: "Saving..."
			}
		/* ]]> */
	</script>
	<?php
}


/*     Administration     */
function asp_options_page() {
    if (function_exists('add_options_page')) {
        add_options_page('Ajax Post Save', 'Ajax Post Save', 'manage_options', basename(__FILE__), 'asp_options_page_show');
	}
}

function asp_options_page_show() {
	/*     Option Changes     */
	if (isset($_POST['ajaxpostsave_options_update'])) {
		if (!current_user_can('manage_options')) {
			wp_die('Cheatin&#8217; uh?');
		}
		
		// Remove 'Save and Continue Editing' button
		update_option('ajaxpostsave_remove_button', $_POST['ajaxpostsave_remove_button']);
		
		// Remove 'Save and Continue Editing' button
		update_option('ajaxpostsave_save_all', $_POST['ajaxpostsave_save_all']);

		// Ajax save button value
		update_option('ajaxpostsave_button_value', $_POST['ajaxpostsave_button_value']);
		
		// Ajax auto save interval
		update_option('ajaxpostsave_auto_save_interval', $_POST['ajaxpostsave_auto_save_interval']);
		
		// Inline frame
		update_option('ajaxpostsave_show_frame', $_POST['ajaxpostsave_show_frame']);
		
		?>
		<div id="message" class="updated fade">
		  <p><strong>Options successfully updated!</strong></p>
		</div>
		<?php
	}
	
	/*     Option Display     */
	?>
	<div class="wrap">
	<h2>Ajax Post Save Options</h2>
	<form method="post">
		<?php if (function_exists("wp_nonce_field")) wp_nonce_field("ajax-post-save"); ?>
		<fieldset class="options">
			<legend>General Options</legend>
			<table class="optiontable editform" width="100%" cellpadding="0" cellspacing="0">
				<tr valign="top">
					<th scope="row">
						Edit Post/Page
						<td>
							<label for="ajaxpostsave_remove_button">
								<input type="checkbox" name="ajaxpostsave_remove_button" id="ajaxpostsave_remove_button" value="1" <?php if (get_option('ajaxpostsave_remove_button') == true){ ?>checked="checked"<?php } ?> />
								Remove the 'Save and Continue Editing' button
							</label><br />
							<label for="ajaxpostsave_save_all">
								<input type="checkbox" name="ajaxpostsave_save_all" id="ajaxpostsave_save_all" value="1" <?php if (get_option('ajaxpostsave_save_all') == true){ ?>checked="checked"<?php } ?> />
								Automatically save all post/page settings (such as post categories and slugs) with Ajax
								<br />
								<small>If you are experiencing slow responce times from the Ajax script, I would recommond disabling this.</small>
							</label><br />
						</td>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Ajax Save Button
						<td>
							<label for="ajaxpostsave_button_value">
								Use the following text value for the button:<br />
							</label>
							<input type="text" name="ajaxpostsave_button_value" id="ajaxpostsave_button_value" size="40" value="<?php echo stripslashes(get_option('ajaxpostsave_button_value')); ?>" />
							<br /><br />
						</td>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Ajax Auto Save
						<td>
							<label for="ajaxpostsave_auto_save_interval">
								Automatically save my post/page after:<br />
							</label>
							<input type="text" name="ajaxpostsave_auto_save_interval" id="ajaxpostsave_auto_save_interval" size="2" value="<?php echo stripslashes(get_option('ajaxpostsave_auto_save_interval')); ?>" /> seconds.
							<br />
							<small>It is best to keep this above 60 seconds to avoid slowing down the script.</small>
							<br /><br />
						</td>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Options Page
						<td>
							<label for="ajaxpostsave_show_frame">
								<input type="checkbox" name="ajaxpostsave_show_frame" id="ajaxpostsave_show_frame" value="1" <?php if (get_option('ajaxpostsave_show_frame') == true){ ?>checked="checked"<?php } ?> />
								Show the help and support inline frame on this page
							</label><br />
						</td>
					</th>
				</tr>
			</table>
		</fieldset>
		<div class="submit">
			<input type="submit" name="ajaxpostsave_options_update" value="Update Options &raquo;" />
		</div>
		</form>
		<?php if (get_option('ajaxpostsave_show_frame') == true) { ?>
			<h2>Help and Support</h2>
			<p>Find answers to common questions here.</p>
			<form name="ajaxpostsave_jump2">
				<select name="ajaxpostsave_jump2box" onchange="frames['ajaxpostsave_support'].location.href = ajaxpostsave_jump2.ajaxpostsave_jump2box.options[selectedIndex].value">
					 <option value="http://blog.bull3t.me.uk/projects/ajax-post-save/faq/">Ajax Post Save frequently asked questions</option>
					 <option value="http://blog.bull3t.me.uk/projects/ajax-post-save/">Ajax Post Save about page</option>
					 <option value="http://blog.bull3t.me.uk/about/contact/project-support/">Project Support form</option>
					 <option value="http://blog.bull3t.me.uk/projects/">More projects by Bull3t</option>
					 <option value="http://blog.bull3t.me.uk/">Bull3t's Blog</option>
 					 <option value="http://www.bull3t.me.uk/">Bull3t's portfolio</option>
				</select>
			</form>
			<iframe name="ajaxpostsave_support" id="ajaxpostsave-support" style="height: 600px; width: 100%;" src="http://blog.bull3t.me.uk/projects/ajax-post-save/faq/"></iframe>
		<?php } ?>
	</div>
	<?php
}
?>
