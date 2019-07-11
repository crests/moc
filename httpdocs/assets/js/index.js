/**
 * index.js
 *
 * @date 2015-06-06
 */

;(function($){
	var $win = $(window),
	scrollTop = $win.scrollTop(),
	winWidth = $win.width(),
	ASOBI = 500;

	({
		// 初期化
		init: function() {
			var self = this;

			$(function(){
				// self.slider();
				self.articleHeightAlign();
				if (!$.ua.isLtIE9) {
					self.coverAnime.init();
					if (winWidth >= 1050) {
						if (!$.cookie('setConceptAnimeCookie')) {
							self.conceptAnime.init();
						}
						if (!$.cookie('setAboutAnimeCookie')) {
							self.aboutAnime.init();
						}
					}
				}
			});

			$win.on('scroll', function() {
				scrollTop = $win.scrollTop();
			});
		},

		// カバーアニメーション
		coverAnime: {
			$cover: {},
			$item: {},
			$logo: {},
			$logoMark: {},
			$btn: {}, // 201704 追加

			init: function(){
				var self = this;

				self.$cover = $('#top--cover-2');
				self.$item = self.$cover.find('.top--cover-2__item');
				self.$logo = self.$cover.find('.top--cover-2__logo');
				self.$logoMark = self.$logo.find('.top--cover-2__logo__mark');

				// セッティング
				TweenMax.set(self.$cover, {alpha: 1});

				// 実行
				if (winWidth >= 1050) {
					if (!$.cookie('setCoverAnimeCookie')) {
						self.coverTimeline();
					} else {
						// クッキーを持っている場合、ロゴのスプライトアニメをさせずにラスコマへ
						self.$logoMark.addClass('has-cookie');
					}
				}
			},

			// カバー　タイムライン
			coverTimeline: function() {
				var self = this,
				tl,
				panel01 = self.$cover.find('.js-panel-01'),
				panel02 = self.$cover.find('.js-panel-02'),
				panel03 = self.$cover.find('.js-panel-03'),
				panel04 = self.$cover.find('.js-panel-04'),
				panel05 = self.$cover.find('.js-panel-05'),
				panel06 = self.$cover.find('.js-panel-06'),
				panel07 = self.$cover.find('.js-panel-07'),
				panel08 = self.$cover.find('.js-panel-08'),
				panel09 = self.$cover.find('.js-panel-09'),
				panel10 = self.$cover.find('.js-panel-10');

				tl = new TimelineLite({
					delay: 0.5,
					onComplete: function() {
						$.cookie('setCoverAnimeCookie' , '1');
					}
				});
				tl.from(panel01, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut})
				.from(panel02, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel03, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel04, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel05, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel06, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel07, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel08, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel09, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(panel10, 0.6, { y: '+=5', alpha: 0, ease:Cubic.easeInOut}, '-=0.4')
				.from(self.$logo, 1.5, { y: '+=5', alpha: 0, ease:Back.easeOut}, '+=0.1');
			}
		},

		conceptAnime: {
			$concept : {},
			$lead : {},
			$imgs : [],
			$txt : {},
			offset : null,

			init: function() {
				var self = this,
				animated = false;

				self.$concept = $('#top--concept');
				self.$lead = $('#top--consept__title');
				self.$imgs = [$('#top--concept__img--01'), $('#top--concept__img--02'), $('#top--concept__img--03')];
				self.$txt = self.$concept.find('.top--concept__txt-box');
				self.offset = self.$concept.offset().top;

				// lead文を一文字づつspanで囲む
				self.$lead.children().andSelf().contents().each(function() {
					if (this.nodeType == 3) {
						$(this).replaceWith($(this).text().replace(/(\S)/g, '<span class="js-span">$1</span>'));
					}
				});

				// セッティング
				TweenMax.set(self.$lead.find('.js-span'), { y: '-=10', alpha: 0});
				TweenMax.set(self.$imgs, { alpha: 0});
				TweenMax.set(self.$txt, { top: '+=5', alpha: 0});

				// タイムライン実行
				$win.on('scroll', function() {
					if (scrollTop >= self.offset - ASOBI && animated === false) {
						self.conceptTimeline();
						animated = true;
					}
				});
			},

			// アニメーション　タイムライン
			conceptTimeline: function() {
				var self = this,
				tl;

				tl = new TimelineLite({
					onComplete: function() {
						$.cookie('setConceptAnimeCookie' , '1');
					}
				});
				tl.staggerTo(self.$lead.find('#js-txt-01 .js-span'), 0.5, { y: '+=10', alpha: 1, ease: Back.easeInOut.config(1) }, 0.05)
				.staggerTo(self.$lead.find('#js-txt-02 .js-span'), 0.5, { y: '+=10', alpha: 1, ease: Back.easeInOut.config(1) }, 0.05)
				.to(self.$imgs, 1, { alpha: 1, ease:Cubic.easeInOut }, '-=0.2')
				.to(self.$txt, 1, { top: '-=5', alpha: 1, ease:Power1.easeInOut }, '-=0.6');
			}

		},

		aboutAnime: {
			$about: {},
			$txt: {},
			$papers: [],
			$imgs: [],
			$btn: {},
			offset: 0,

			init: function() {
				var self = this,
				animated = false;

				self.$about = $('#top--about');
				self.$txt = self.$about.find('.top--about__txt');
				self.$papers = [$('#top--about__paper--left'), $('#top--about__paper--right')];
				self.$imgs = [$('#top--about__img--01'), $('#top--about__img--02'), $('#top--about__img--03')];
				self.$btn = self.$about.find('.top--about__btn');
				self.offset = self.$about.offset().top;

				// セッティング
				TweenMax.set(self.$txt, {alpha: 0});
				TweenMax.set(self.$papers, { scale: 0, alpha: 0});
				TweenMax.set(self.$papers[0], { x: '-=3', rotation: -15});
				TweenMax.set(self.$papers[1], { x: '+=3', rotation: 15});
				TweenMax.set(self.$btn, {top: '+=5', alpha: 0});

				// タイムライン実行
				$win.on('scroll', function() {
					if (scrollTop >= self.offset - ASOBI && animated === false) {
						self.aboutTimeline();
						animated = true;
					}
				});

			},

			// アニメーション　タイムライン
			aboutTimeline: function() {
				var self = this,
				tl = new TimelineLite({
					onComplete: function() {
						$.cookie('setAboutAnimeCookie' , '1');
					}
				});

				tl.to(self.$papers[0], 1.5, { transformOrigin: '0 100%', y: '-=10', rotation: 0, scale: 1, alpha: 1, ease: Expo.easeOut})
				.to(self.$papers[1], 1.5, { transformOrigin: '100% 100%', y: '-=10', rotation: 0, scale: 1, alpha: 1, ease: Expo.easeOut}, '-=1.5')
				.to(self.$papers[0], 2.0, { x: '+=3' , y: '+=8', ease: Linear.easeNone}, '-=1.2')
				.to(self.$papers[1], 2.0, { x: '-=3' , y: '+=8', ease: Linear.easeNone}, '-=2.0')
				.to(self.$txt, 1.0, {alpha: 1, ease:Cubic.easeInOut}, '-=1.8')
				.to(self.$btn, 1.0, {top: '-=5', alpha: 1, ease:Cubic.easeInOut}, '-=1.0');
			}
		},

		// スライダー
		slider: function() {
			var slider;
			var sliderSwitchFlg;

			var $slider = $('#coverSlider');
			var $slide_all = $('#slide_all');
			var $slide_wrap = $('#slide_wrap');
			var slideLength = $slider.find('.sp-slide').length;

			if (winWidth >= 1000) {
				setSlider('full');
			} else {
				setSlider('compact');
			}

			// リサイズイベント
			$win.on('resize', function(event) {
				event.preventDefault();
				winWidth = $win.width();
				if (winWidth > 1000) {
					if (sliderSwitchFlg !== 'full') {
						resetSlider();
						setSlider('full');
						sliderSwitchFlg = 'full';
					} else {
						return;
					}

				} else {
					if (sliderSwitchFlg !== 'compact') {
						resetSlider();
						setSlider('compact');
						sliderSwitchFlg = 'compact';
					} else {
						return;
					}
				}
			});

			// スライダーセッティング
			function setSlider(type) {
				if (type === 'full') {

					$slide_all.addClass('is-sliderFull');
					$slide_wrap.addClass('is-sliderFull');

					$slide_wrap.css({
						width: 1000 * slideLength,
						marginLeft: -(1000 * slideLength / 2)
					});

					slider = $slider.bxSlider({
						slideWidth: 1000,
						auto: true,
						minSlides: 3,
						maxSlides: 3,
						moveSlides: 1,
						useCSS: false //Safari対策
					});

				} else if (type === 'compact') {

					$slide_all.removeClass('is-sliderFull');
					$slide_wrap.removeClass('is-sliderFull');

					slider = $slider.bxSlider({
						slideWidth: 1000,
						auto: true
					});

					$slide_wrap.css({
						width: '',
						marginLeft: ''
					});
				}
			}

			// スライダーリセット
			function resetSlider() {
				$slider.destroySlider();
			}
		},

		// レストラン　高さ揃え
		articleHeightAlign: function() {
			var $obj = $('#js-heightAlign');
			var $base = $obj.find('.top--rest__list');
			var baseWidth;
			var alignActiveFlg;
			var alignObj;
			var resizeTimer;
			var breakPoint = [869, 425]; // $baseのCSSのwidth値

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
					// タブレット 2カラム
					alignReset(2);
					alignActiveFlg = 2;

				} else if (baseWidth !== breakPoint[0] && baseWidth !== breakPoint[1] && alignActiveFlg !== 3 && alignActiveFlg) {
					// スマホ　高さ揃え解除
					alignObj.destroy();
					alignActiveFlg = 3;

				}
			}

			function alignReset(colum) {
				if (alignActiveFlg) alignObj.destroy();
				alignObj = $obj.heightAlign({target: 'article a', base: '.top--rest__list', col: colum, imgLoad: false});
			}
		}

	}).init();
})(jQuery);
