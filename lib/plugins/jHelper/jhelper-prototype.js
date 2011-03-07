/* jHelper Prototype v1.1
 * 
 * Requires:
 * - jHelper core
 * 
 * Description: Defines object methods for the jHelper plugin
 * Note: Receives data from php as object vars from the jHelper core in jHelper-core.js.php
 * 
 * CHANGE LOG:
 * 1.1 removed initFormValidation() as it was depreciated. All forms will be AJAX, and use initAjaxContactForms()
 * 
 * TODO: Seperate validation and send logic out from initAjaxContactForms()?
 */
$(function(){
	

jHelper.prototype = {
			
		init : function(){
			//Selectors ==================================
			this.modalLinks = $('.j-modal'); 
			this.slides = $('#slideshow').find('.slide');
			this.contactForm = $('#j-contact');
			
			//PROCEEDURE =================================
			
			//1. Modal Windows
			this.initModalWindows(this.modalLinks);
			//1a. init AJAX Contact Forms on click via MODAL WINDOWS controller
			//2. Slideshow
			this.setSlideshow(this.slides, 600, 5000);
			//3. Init non-modal contact form.
			this.initAjaxContactForms(this.contactForm, this.contactForm.attr('action'));			
		},
		
		/* Modal Window Controller
		 * v 1.0
		 * 
		 * Description: Controls the opening and closing of modal windows. Currently using fancybox.
		 * Method: Load the file via AJAX from the name attribute
		 * Eg: name="filemane" loads "/filename.php", name="file-name" loads "/file-name.php"
		 */
		initModalWindows : function(links){	
			// Cache the jHelper object for use in functions
			var that = this;
			var formAndController;
			
			$.each(links, function(){
				var load, name = this.getAttribute("name");
				if(name !== null) {
					formAndController = that.templateDirectory + "/" + name + ".php";
					$(this).attr('href', formAndController);
				}
			});
			//Activate FancyBox
			links.fancybox({
				'padding'			:	20,
				'transitionIn'		:	'fade',
				'transitionOut'		:	'fade',
				'speedIn'			:	600,
				'speedOut'			:	600,
				'centerOnScroll'	:	false, /* TRUE does not play well with validation engine */
				'overlayOpacity'	:	0.7,
				'overlayColor'		:	'#222',
				'onComplete'		:	function(){ that.initAjaxContactForms($('.j-contact'), formAndController); },
				'onCleanup'			:	function(){ $('.formError').fadeOut().delay(400).remove(); }
			});
		},
		
		/* AJAX Contact Forms
		 * v1.1
		 * Changelog:
		 * 1.1 Modal Windows provide the form as the default controller 
		 * 
		 * Description: Controls the validation and submission of ajax forms
		 */
		initAjaxContactForms: function(forms, controllerPath) {
			//default error check status
			var use_ajax = true;
			//store jHelper
			var that = this;
			//Form Elements
			var loadingGif = $('.j-contact-loading');
			var response = $('.j-contact-response');
			var formClose = $('#fancybox-close');
			
			//Init Validation for Ajax forms
			$(forms).validationEngine({
				scroll:false,
				inlineValidation:false,
				success: function(){use_ajax=true;},
				failure: function(){use_ajax=false;}
			});
			
			//If validated, Submit Data
			forms.submit(function(e){
				e.preventDefault;
				if(use_ajax)
				{ 
					//Show Loading Gif
					loadingGif.css('display', 'inline');
					
					//send data via post
					var contactData = forms.serialize() + '&ajax=1';
					$.post(controllerPath, contactData, function(data){
						
						//Display Resonse
						forms.slideUp(500, function(){ 
							response.fadeIn(400, function(){
								setTimeout(function(){ formClose.click(); }, 2200);
							});
						});
						
						//Reset Inputs
						forms.find('input[type="text"]').each(function(){
							$(this).val('');
						});
						forms.find('textarea').val('');
						
						//Hide Loading GIF
						loadingGif.css('display', 'none');
					});//post 
				}
			});//submit
		},
		
		/* Set Slideshow
		 * v 1.0
		 * 
		 * Description: Controlls a simple slideshow
		 */
		setSlideshow : function(slides, speed, interval) {
			var index = 0;
			if (speed == null){ speed = 600; }
			if (interval == null) { interval = 8000; }
			
			//Instantiate
			setInterval(sift, interval);
			//Controler
				function sift(x)
					{
						if(index<(slides.length-1)){index+=1;}
						else {index=0;}
						show(index);
					}
			//CORE
				function show(num)
					{
						$(slides).fadeOut(speed);
						$(slides[num]).stop().fadeIn(speed);
					}
			//Instantiate
				$(slides).hide();
				show(index);
		},
		
		/* Get Site Root
		 * V 1.0
		 * 
		 * Description: Returns site root, whether localhost or live site
		 * URI: http://www.gotknowhow.com/articles/how-to-get-the-base-url-with-javascript
		 */
		getSiteRoot : function () {
		    var url = location.href;  // entire url including querystring - also: window.location.href;
		    var baseURL = url.substring(0, url.indexOf('/', 14));


		    if (baseURL.indexOf('http://localhost') != -1) {
		        // Base Url for localhost
		        var url = location.href;  // window.location.href;
		        var pathname = location.pathname;  // window.location.pathname;
		        var index1 = url.indexOf(pathname);
		        var index2 = url.indexOf("/", index1 + 1);
		        var baseLocalUrl = url.substr(0, index2);

		        return baseLocalUrl;
		    }
		    else {
		        // Root Url for domain name
		        return baseURL;
		    }

		}
		
	};
		
	//run
	new jHelper();
});