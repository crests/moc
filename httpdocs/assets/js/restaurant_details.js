/**
 * restaurant.js
 *
 * @date 2015-06-06
 */

// スライダー
;(function($){
	var slider = $('.rest__details__shop__slide');
	var sliderImg = $('.rest__details__shop__slide article').length;
	var sliderLoding = $('.rest__details__shop__slide__loading');

	$(window).load(function(){
		if(sliderImg > 1){
			slider.bxSlider({
				auto: true,
				controls: false,
				useCSS: false
			});
		}
		slider.css({
			height: 'auto',
			visibility: 'visible',
		});
		sliderLoding.css('display' , 'none');
	});
})(jQuery);