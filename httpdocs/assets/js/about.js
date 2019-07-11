/**
 * about.js
 *
 * @date 2015-07-28
 */

;(function($){
	var $win = $(window),
	winWidth = $win.width();

	({
		// 初期化
		init: function() {
			var self = this;

			$(function(){
				if (!$.ua.isLtIE9) {
					if (winWidth >= 1050) {
						self.aboutAnime();
					} else {
						self.aboutShow();
					}
				} else {
					self.aboutShow();
				}
			});
		},

		// コンテンツを表示
		aboutShow: function() {
			$('.about__cover').css('visibility' , 'visible');
		},

		// アニメーション
		aboutAnime: function() {

			// 初期値
			TweenMax.set('.about__cover' , { visibility: 'visible'});
			TweenMax.set('#about__cover__logo', { y: -10, opacity: 0});
			TweenMax.set('.is-about__cover__img', { y: -25, opacity: 0});
			TweenMax.set('#about__cover__txt', { y: 6, opacity: 0});

			// タイムライン
			var timeLine = new TimelineMax({delay: 0.5});
			timeLine.to('.is-about__cover__img', 0.8 , { ease: Power3.easeOut, y: 0, opacity: 1})
			.to('#about__cover__logo', 1.5 , { ease: Power3.easeOut, y: 0, opacity: 1} , '-=0.2')
			.to('#about__cover__txt', 1.7 , { ease: Power3.easeOut, y: 0, opacity: 1} , '-=1.0');
		}
	}).init();
})(jQuery);
