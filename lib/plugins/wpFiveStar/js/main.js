jQuery(function($){
	
var wpFiveStar = function() {

	//vars / selections
	this.starForm = $('#wpfs-rating-input');
	this.params = this.starForm.find('.wpfs-param');
	this.form = $('#wpfs-review-form');
	
	//Init
	this.init();
};

wpFiveStar.prototype = {

	init : function() {
		this.hideRadios(this.params);
		this.replaceRadios(this.params);
		this.enableForm(this.form);
	},
	
	hideRadios : function(params) {
		$.each(params, function(){
			$(this).find('input').hide();
			$(this).find('span').hide();
		});
	},
	
	replaceRadios : function(params) {
		var that = this;
		
		
		$.each(params, function(){
			var inputs = $(this).find('input');
			var thisParam = $(this);
			
			$.each(inputs, function(){
				var name = $(this).attr('name'); 
				var value = $(this).attr('value');
				
				thisParam.find('.wpfs-stars').append($('<div></div>')
							.attr({'name': name })
							.addClass('wpfs-star wpfs-clickable star-'+value)
							.text(value)
							//DECLARE HOVER AND CLICK EVENTS
							.hover(
								function(){ that.starHoverOn($(this)); },
								function(){ that.starHoverOff($(this)); }
								)
							.click(function(){
								that.handleStarClick($(this), '')
							})
						);
			});
		});
	},
	
	starHoverOn : function(star) {
		star.prevAll().addClass('wpfs-on');
	},
	
	starHoverOff : function(star) {
		star.prevAll().removeClass('wpfs-on');
	},
	
	starHoverOffUntil : function(star, until) {
		star.prevUntil(until).removeClass('wpfs-on');
	},
	
	handleStarClick : function(star) {
		var c = 'wpfs-on', prev = star.prevAll(), next = star.nextAll(), that = this, value = star.text();
		//Lock In Hover States
		/* - this set "on"
		 * - prev hovers removed
		 * - next set "off"
		 * - next hovers restored
		 */
		prev.unbind('mouseout');
		prev.unbind('mouseover');
		next.unbind('mouseover');
		next.unbind('mouseout');
		star.unbind('mouseout');
		star.unbind('mouseover');
		
		star.addClass(c);
		prev.addClass(c);
		next.removeClass(c);

		//Check the existing radio input
		star.siblings('input[value="' + value + '"]').attr('checked', 'checked');
	},
	
	enableForm : function(form) {
		form.css({'display' : 'block'});
	}
	
};

new wpFiveStar();
	
	
});