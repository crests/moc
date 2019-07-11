/**
 * concept.js
 *
 * @date 2015-08-05
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
						self.conceptAnime();
					} else {
						self.conceptShow();
					}
				} else {
					self.conceptShow();
				}
			});

			$win.on('scroll', function() {
				scrollTop = $win.scrollTop();
			});
		},

		conceptShow: function() {
			$('.concept__cover').css('visibility' , 'visible');
		},

		conceptAnime: function() {

			// アニメーション実行位置
			var conceptCoverOffset = $('.concept__cover').offset().top + 100;
			var startAnime = function() {
				if (scrollTop > conceptCoverOffset) {
					conceptIllust();
				}
			};
			$win.scroll(startAnime);

			// lead文を一文字づつspanで囲む
			$('#concept__cover__title').children().andSelf().contents().each(function() {
				if (this.nodeType == 3) {
					$(this).replaceWith($(this).text().replace(/(\S)/g, '<span class="js-span">$1</span>'));
				}
			});

			// 初期値
			TweenMax.set('.concept__cover' , { visibility: 'visible'});
			TweenMax.set('#concept__cover__top' , { y: -10, opacity: 0});
			TweenMax.set('.js-span' , { y: -10, opacity: 0, display: 'inline-block'});
			TweenMax.set('#concept__cover__txt' , { y: 6, opacity: 0});
			TweenMax.set('#concept__cover__img--01' , { x: -10, opacity: 0});
			TweenMax.set('#concept__cover__img--02' , { x: -10, opacity: 0});
			TweenMax.set('#concept__cover__img--03' , { x: -10, opacity: 0});
			TweenMax.set('#concept__border--01' , { x: -10, opacity: 0});
			TweenMax.set('#concept__border--02' , { x: -10, opacity: 0});
			TweenMax.set('#concept__cover__summary' , { y: 6, opacity: 0});
			TweenMax.set('#concept__cover__summary--notes' , { y: 6, opacity: 0});

			// アニメーション
			var topTimeLine = new TimelineMax({delay: 0.5});
			topTimeLine.to('.concept__cover__top', 0.8, { ease: Power3.easeOut, y: 0, opacity: 1})
			.staggerTo('#js-txt-01 .js-span', 0.5, { ease: Back.easeInOut.config(1), y: 0, opacity: 1}, 0.05, '-=0.7')
			.staggerTo('#js-txt-02 .js-span', 0.5, { ease: Back.easeInOut.config(1), y: 0, opacity: 1}, 0.05)
			.to('#concept__cover__txt' , 1.7 , { ease: Power3.easeOut, y: 0, opacity: 1} ,'-=0.2');

			function conceptIllust() {
				var illustTimeLine = new TimelineMax();
				illustTimeLine.to('#concept__cover__img--01' , 0.7 , { ease: Power3.easeOut, x: 0, opacity: 1} , '-=0.9')
				.to('#concept__border--01' , 0.7 , { ease: Power3.easeOut, x: 0, opacity: 1} , '-=0.6')
				.to('#concept__cover__img--02' , 0.7 , { ease: Power3.easeOut, x: 0, opacity: 1} , '-=0.4')
				.to('#concept__border--02' , 0.7 , { ease: Power3.easeOut, x: 0, opacity: 1} ,'-=0.4')
				.to('#concept__cover__img--03' , 0.7 , { ease: Power3.easeOut, x: 0, opacity: 1} ,'-=0.4')
				.to('#concept__cover__summary' ,1.7 , { ease: Power3.easeOut, y: 0, opacity: 1} ,'-=0.5')
				.to('#concept__cover__summary--notes' ,1.7 , { ease: Power3.easeOut, y: 0, opacity: 1} ,'-=1.3');
			}
		}
	}).init();
})(jQuery);
