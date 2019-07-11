/**
 * restaurant.js
 *
 * @date 2015-06-06
 */

;(function($){
	var $win = $(window);
	var winWidth = $win.width();

	({
		// 初期化
		init: function() {
			var self = this;

			$(function(){
				self.articleHeightAlign();
			});
		},

		// 高さ揃え
		articleHeightAlign: function() {
			var self = this;
			var $obj = $('#js-heightAlign');
			var $base = $obj.find('.rest__list');
			var baseWidth;
			var alignActiveFlg;
			var alignObj;
			var resizeTimer;
			var breakPoint = [880, 662, 440]; // $baseのCSSのwidth値

			// レストラン部分高さ揃え
			// リサイズ時に実行
			$win.on('resize load', function() {
				baseWidth = parseInt($base.css('width'),10);

				if(!resizeTimer){
					clearTimeout(resizeTimer);
				}
				resizeTimer = setTimeout(function(){
					columChangeHandle();
				},　200);

			});

			function columChangeHandle() {
				if (baseWidth === breakPoint[0] && alignActiveFlg !== 1) {
					// PC 4カラム
					alignReset(4);
					alignActiveFlg = 1;

				} else if (baseWidth === breakPoint[1] && alignActiveFlg !== 2) {
					// タブレット 3カラム
					alignReset(3);
					alignActiveFlg = 2;

				} else if (baseWidth === breakPoint[2] && alignActiveFlg !== 3) {
					// タブレット 2カラム
					alignReset(2);
					alignActiveFlg = 3;

				} else if (baseWidth !== breakPoint[0] && baseWidth !== breakPoint[1] && baseWidth !== breakPoint[2] && alignActiveFlg !== 4 && alignActiveFlg) {
					// スマホ　高さ揃え解除
					alignObj.destroy();
					alignActiveFlg = 4;

				}
			}

			function alignReset(colum) {
				if (alignActiveFlg) alignObj.destroy();
				alignObj = $obj.heightAlign({target: 'article a', base: '.rest__list', col: colum, imgLoad: false});
			}
		}

	}).init();
})(jQuery);
