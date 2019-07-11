/**
 * jQueryオブジェクトの拡張
 *
 * @date 2015-01-08
 */
 (function($) {
	/**
	 * userAgent判定フラグ
	 *
	 * @date 2014-09-03
	 */
	 var ua = navigator.userAgent.toLowerCase();
	 $.ua = {
		// Windows
		isWindows: /windows/.test(ua),
		// Mac
		isMac: /macintosh/.test(ua),
		// IE
		isIE: /msie (\d+)|trident/.test(ua),
		// IE8未満
		isLtIE8: /msie (\d+)/.test(ua) && RegExp.$1 < 8,
		// IE9未満
		isLtIE9: /msie (\d+)/.test(ua) && RegExp.$1 < 9,
		// IE10未満
		isLtIE10: /msie (\d+)/.test(ua) && RegExp.$1 < 10,
		// Firefox
		isFirefox: /firefox/.test(ua),
		// WebKit
		isWebKit: /applewebkit/.test(ua),
		// Chrome
		isChrome: /chrome/.test(ua),
		// Safari
		isSafari: /safari/.test(ua)&&(!/chrome/.test(ua))&&(!/mobile/.test(ua)),
		// iOS
		isIOS: /i(phone|pod|pad)/.test(ua),
		// iPhone、iPod touch
		isIPhone: /i(phone|pod)/.test(ua),
		// iPad
		isIPad: /ipad/.test(ua),
		// Android
		isAndroid: /android/.test(ua),
		// モバイル版Android
		isAndroidMobile: /android(.+)?mobile/.test(ua),
		// タッチデバイス
		isTouchDevice: 'ontouchstart' in window,
		// スマートフォン
		isMobile: /i(phone|pod)/.test(ua)||/android(.+)?mobile/.test(ua),
		// タブレット型端末
		isTablet: /ipad/.test(ua)||/android(.+)(?!mobile)/.test(ua)
	};



	/**
	 * ロールオーバー
	 *
	 * @date 2012-10-01
	 *
	 * @example $('.rollover').rollover();
	 * @example $('.rollover').rollover({ over: '-ov' });
	 * @example $('.rollover').rollover({ current: '_cr', currentOver: '_cr_ov' });
	 * @example $('.rollover').rollover({ down: '_click' });
	 */
	 $.fn.rollover = function(options) {
	 	var defaults = {
	 		over: '_ov',
	 		current: null,
	 		currentOver: null,
	 		down: null
	 	};
	 	var settings = $.extend({}, defaults, options);
	 	var over = settings.over;
	 	var current = settings.current;
	 	var currentOver = settings.currentOver;
	 	var down = settings.down;
	 	return this.each(function() {
	 		var src = this.src;
	 		var ext = /\.(gif|jpe?g|png)(\?.*)?/.exec(src)[0];
	 		var isCurrent = current && new RegExp(current + ext).test(src);
	 		if (isCurrent && !currentOver) return;
	 		var search = (isCurrent && currentOver) ? current + ext : ext;
	 		var replace = (isCurrent && currentOver) ? currentOver + ext : over + ext;
	 		var overSrc = src.replace(search, replace);
	 		new Image().src = overSrc;
	 		$(this).mouseout(function() {
	 			this.src = src;
	 		}).mouseover(function() {
	 			this.src = overSrc;
	 		});

	 		if (down) {
	 			var downSrc = src.replace(search, down + ext);
	 			new Image().src = downSrc;
	 			$(this).mousedown(function() {
	 				this.src = downSrc;
	 			});
	 		}
	 	});
	 };



	/**
	 * フェードロールオーバー
	 *
	 * @date 2012-11-21
	 *
	 * @example $('.faderollover').fadeRollover();
	 * @example $('.faderollover').fadeRollover({ over: '-ov' });
	 * @example $('.faderollover').fadeRollover({ current: '_cr', currentOver: '_cr_ov' });
	 */
	 $.fn.fadeRollover = function(options) {
	 	var defaults = {
	 		over: '_ov',
	 		current: null,
	 		currentOver: null
	 	};
	 	var settings = $.extend({}, defaults, options);
	 	var over = settings.over;
	 	var current = settings.current;
	 	var currentOver = settings.currentOver;
	 	return this.each(function() {
	 		var src = this.src;
	 		var ext = /\.(gif|jpe?g|png)(\?.*)?/.exec(src)[0];
	 		var isCurrent = current && new RegExp(current + ext).test(src);
	 		if (isCurrent && !currentOver) return;
	 		var search = (isCurrent && currentOver) ? current + ext : ext;
	 		var replace = (isCurrent && currentOver) ? currentOver + ext : over + ext;
	 		var overSrc = src.replace(search, replace);
	 		new Image().src = overSrc;

	 		$(this).parent()
	 		.css('display','block')
	 		.css('width',$(this).attr('width'))
	 		.css('height',$(this).attr('height'))
	 		.css('background','url("'+overSrc+'") no-repeat');

	 		$(this).parent().hover(function() {
	 			$(this).find('img').stop().animate({opacity: 0}, 200);
	 		}, function() {
	 			$(this).find('img').stop().animate({opacity: 1}, 200);
	 		});
	 	});
	 };



	/**
	 * 不透明度ロールオーバー
	 *
	 * @date 2014-09-03
	 *
	 * @example $('.opacity').opacityRollover();
	 * @example $('.opacity').opacityRollover({ overOpacity: 0.6 });
	 * @example $('.opacity').opacityRollover({ fade: false });
	 */
	 $.fn.opacityRollover = function(options) {
	 	var defaults = {
	 		fade: true,
	 		defaultOpacity: 1,
	 		overOpacity: 0.7,
	 		inDuration: 250,
	 		outDuration: 200,
	 		easing: 'easeOutQuart'
	 	};
	 	var settings = $.extend({}, defaults, options);
	 	var fade = settings.fade;
	 	var defaultOpacity = settings.defaultOpacity;
	 	var overOpacity = settings.overOpacity;
	 	var inDuration = settings.inDuration;
	 	var outDuration = settings.outDuration;
	 	var easing = settings.easing;
	 	return this.each(function() {
	 		$(this).hover(function() {
	 			if (fade) {
	 				$(this).stop().animate({opacity: overOpacity}, inDuration, easing);
	 			} else {
	 				$(this).css({opacity: overOpacity});
	 			}
	 		}, function() {
	 			if (fade) {
	 				$(this).stop().animate({opacity: defaultOpacity}, outDuration, easing);
	 			} else {
	 				$(this).css({opacity: defaultOpacity});
	 			}
	 		});
	 	});
	 };



	/**
	 * スムーズスクロール
	 *
	 * @date 2014-12-01
	 *
	 * @example $.scroller();
	 * @example $.scroller({ hashMarkEnabled: true });
	 * @example $.scroller({ scopeSelector: '#container', noScrollSelector: '.no-scroll' });
	 * @example $.scroller('#content');
	 * @example $.scroller('#content', { pitch: 20, delay: 5, marginTop: 200, callback: function(){} });
	 */
	 $.scroller = function() {
	 	var self = $.scroller.prototype;
	 	if (!arguments[0] || typeof arguments[0] == 'object') {
	 		self.init.apply(self, arguments);
	 	} else {
	 		self.scroll.apply(self, arguments);
	 	}
	 };

	// プロトタイプにメンバを定義
	$.scroller.prototype = {
		// 初期設定
		defaults: {
			hashMarkEnabled: false,
			scopeSelector: 'body',
			noScrollSelector: '.noscroll',
			pitch: 10,
			delay: 10,
			marginTop: 0,
			callback: function() {}
		},

		// 初期化
		init: function(options) {
			var self = this;
			var settings = this.settings = $.extend({}, this.defaults, options);
			$(settings.scopeSelector).find('a[href^="#"]').not(settings.noScrollSelector).each(function() {
				var hash = this.hash || '#';
				var eventName = 'click.scroller';
				$(this).off(eventName).on(eventName, function(e) {
					e.preventDefault();
					this.blur();
					self.scroll(hash, settings);
				});
			});
		},

		// スクロールを実行
		scroll: function(id, options) {
			if (this.timer) this.clearScroll();
			var settings = (options) ? $.extend({}, this.defaults, options) : (this.settings) ? this.settings : this.defaults;
			if (!settings.hashMarkEnabled && id == '#') return;
			var self = this;
			var win = window;
			var $win = $(win);
			var d = document;
			var pitch = settings.pitch;
			var delay = settings.delay;
			var scrollLeft = $win.scrollLeft();
			if (($.ua.isIPhone || $.ua.isAndroidMobile) && win.pageYOffset === 0) win.scrollTo(scrollLeft, (($.ua.isAndroidMobile) ? 1 : 0));
			var scrollEnd = (id == '#') ? 0 : $(id + ', a[name="' + id.substr(1) + '"]').eq(0).offset().top;
			var windowHeight = ($.ua.isAndroidMobile) ? Math.ceil(win.innerWidth / win.outerWidth * win.outerHeight) : win.innerHeight || d.documentElement.clientHeight;
			var scrollableEnd = $(d).height() - windowHeight;
			if (scrollableEnd < 0) scrollableEnd = 0;
			scrollEnd = scrollEnd - settings.marginTop;
			if (scrollEnd > scrollableEnd) scrollEnd = scrollableEnd;
			if (scrollEnd < 0) scrollEnd = 0;
			scrollEnd = Math.floor(scrollEnd);

			if ($.ua.isAndroid && scrollEnd === 0) scrollEnd = 1;
			var dir = (scrollEnd > $win.scrollTop()) ? 1 : -1;
			(function _scroll() {
				var prev = self.prev;
				var current = self.current || $win.scrollTop();
				if (current == scrollEnd || typeof prev == 'number' && (dir > 0 && current < prev || dir < 0 && current > prev)) {
					self.clearScroll();
					settings.callback();
					return;
				}
				var next = current + (scrollEnd - current) / pitch + dir;
				if (dir > 0 && next > scrollEnd || dir < 0 && next < scrollEnd) next = scrollEnd;
				win.scrollTo(scrollLeft, next);
				self.prev = current;
				self.current = next;
				self.timer = setTimeout(function() {
					_scroll();
				}, delay);
			})();
		},

		// スクロールを解除
		clearScroll: function() {
			clearTimeout(this.timer);
			this.timer = null;
			this.prev = null;
			this.current = null;
		}
	};



	/**
	 * orientationchangeに関するイベントハンドラ登録用メソッド
	 *
	 * @date 2011-05-30
	 *
	 * @example $(window).orientationchange(function() { alert(window.orientation); });
	 * @example $(window).portrait(function() { alert(window.orientation); });
	 * @example $(window).landscape(function() { alert(window.orientation); });
	 */
	 var type = ($.ua.isAndroid) ? 'resize' : 'orientationchange';
	 $.fn.extend({
		// オリエンテーションチェンジ
		orientationchange: function(fn) {
			return this.bind(type, fn);
		},
		// ポートレイト
		portrait: function(fn) {
			return this.bind(type, function() {
				if (window.orientation === 0) fn();
			});
		},
		// ランドスケープ
		landscape: function(fn) {
			return this.bind(type, function() {
				if (window.orientation !== 0) fn();
			});
		}
	});



	/**
	 * script要素のsrc属性を利用して指定したファイル名のルートにあたるパスを取得
	 *
	 * @date 2011-06-20
	 *
	 * @example $.getScriptRoot('common/js/base.js');
	 */
	 $.getScriptRoot = function(filename) {
	 	var elms = document.getElementsByTagName('script');
	 	for (var i = elms.length - 1; i >= 0; i--) {
	 		var src = elms[i].src;
	 		if (new RegExp('(.*)?' + filename + '([\?].*)?').test(src)) return RegExp.$1;
	 	}
	 	return false;
	 };



	/**
	 * script要素のsrc属性からオブジェクトに変換したクエリを取得
	 *
	 * @date 2011-06-20
	 *
	 * @example $.getScriptQuery();
	 * @example $.getScriptQuery('common/js/base.js');
	 */
	 $.getScriptQuery = function(filename) {
	 	var elms = document.getElementsByTagName('script');
	 	if (!filename) {
	 		return $.getQuery(elms[elms.length - 1].src);
	 	} else {
	 		for (var i = elms.length - 1; i >= 0; i--) {
	 			var src = elms[i].src;
	 			if (new RegExp(filename).test(src)) return $.getQuery(src);
	 		}
	 		return false;
	 	}
	 };



	/**
	 * 文字列からオブジェクトに変換したクエリを取得
	 *
	 * @date 2011-05-30
	 *
	 * @example $.getQuery();
	 * @example $.getQuery('a=foo&b=bar&c=foobar');
	 */
	 $.getQuery = function(str) {
	 	if (!str) str = location.search;
	 	str = str.replace(/^.*?\?/, '');
	 	var query = {};
	 	var temp = str.split(/&/);
	 	for (var i = 0, l = temp.length; i < l; i++) {
	 		var param = temp[i].split(/=/);
	 		query[param[0]] = decodeURIComponent(param[1]);
	 	}
	 	return query;
	 };



	/**
	 * 画像をプリロード
	 *
	 * @date 2012-09-12
	 *
	 * @example $.preLoadImages('/img/01.jpg');
	 */
	 var cache = [];
	 $.preLoadImages = function() {
	 	var args_len = arguments.length;
	 	for (var i = args_len; i--;) {
	 		var cacheImage = document.createElement('img');
	 		cacheImage.src = arguments[i];
	 		cache.push(cacheImage);
	 	}
	 };



	/**
	 * スクロール時に要素を遅延表示
	 *
	 * @date 2015-01-08
	 *
	 * @example $('img').scrollDisplay();
	 * @example $('img').scrollDisplay({duration: 2000, posFix: 200});
	 * @example $('img').scrollDisplay({beforeFadeIn: function() {...}, afterFadeIn: function() {...}});
	 */
	 $.fn.scrollDisplay = function(options) {
	 	var defaults = {
	 		duration: 1000,
	 		easing: 'easeInOutQuart',
	 		posFix: 100,
	 		beforeFadeIn: function() {},
	 		afterFadeIn: function() {}
	 	};
	 	var settings = $.extend({}, defaults, options);
	 	return this.each(function() {
	 		var win = window;
	 		var _this = this;
	 		var obj = $(this);
	 		var length = obj.length;
	 		var pos = [];

	 		var func = {
	 			init: function() {
	 				obj.not('.faded').css({opacity: 0});

	 				for (var i = 0; i < length; i++) {
	 					var posY = obj.eq(i).offset().top;
	 					pos.push(posY);
	 				}
	 				func.scroll();
	 			},

	 			scroll: function() {
	 				var scrollTop  = $(win).scrollTop();
	 				var windowBottom = $(win).height() + scrollTop - settings.posFix;

	 				for (var i = 0; i < obj.length; i++) {
	 					if (pos[i] <= windowBottom) {
	 						func.fadeIn(i);
	 					}
	 				}
	 			},

	 			fadeIn: function(i) {
	 				if (!obj.eq(i).hasClass('faded')) {
	 					settings.beforeFadeIn.call(_this);
	 					obj.eq(i).animate({opacity: 1}, settings.duration, settings.easing, function() {
	 						settings.afterFadeIn.call(_this);
	 					}).addClass('faded');
	 				}
	 			}
	 		};

	 		func.init();

	 		$(win).on('scroll', function() {
	 			func.scroll();
	 		});
	 	});
};



	/**
	 * 高さ揃え
	 *
	 * @date 2014-08-27
	 *
	 * @example $('#itemList').heightAlign();
	 * @example $('#itemList').heightAlign({target: 'li'});
	 * @example $('#itemList').heightAlign({target: 'li', base: 'ul'});　※各 <ul> ごとに <li> の高さを揃える
	 * @example $('#itemList').heightAlign({target: 'li', col: 5});　※個数ごとに <li> の高さを揃える（1行分の数など）
	 * @example $('#itemList').heightAlign({target: 'li', resizable: true});　※ウィンドウリサイズ時に高さを再設定
	 */
	 $.fn.heightAlign = function(options) {
	 	var _this = this;

	 	var defaults = {
	 		target: 'a',
	 		base: null,
	 		col: 0,
	 		resizable: false,
	 		imgLoad: true
	 	};

	 	var settings = $.extend({}, defaults, options);
	 	var windowResizeId = Math.random();
	 	var imgLoadCompleted = false;

		// 高さを調べて揃える
		var setHeight = function(elm) {
			var maxHeight = 0;
			var imgElm = elm.find('img');
			var imgCnt = imgElm.length;
			var loadChkSpan = 20;
			var loadWait = 1000;
			var waiting = 0;

			var func = function() {
				elm.each(function() {
					if ($(this).height() > maxHeight) {
						maxHeight = $(this).height();
					}
				});
				elm.css('height', maxHeight);
			};


			if (!imgLoadCompleted && settings.resizable) {
				imgElm.on('load', function() { imgCnt--; });
				var loadCheckTimer = setInterval(function() {
					if (imgCnt === 0 || waiting > loadWait) {
						clearTimeout(loadCheckTimer);
						imgLoadCompleted = true;
						func();
					} else {
						waiting = waiting + loadChkSpan;
					}
				}, loadChkSpan);
			} else {
				func();
			}
		};

		// 要素を個数ごと（行ごと）に小分け　→ 高さを調べて揃える
		var setHeightByRow = function(elms) {
			var rows = [],
			temp = [];

			elms.each(function(i) {
				temp.push(this);
				if (i % settings.col == (settings.col - 1)) {
					rows.push(temp);
					temp = [];
				}
			});
			if (temp.length) rows.push(temp);

			$.each(rows, function() {
				setHeight($(this));
			});
		};

		// リサイズイベント追加
		var attachResizeEvent = function() {
			$(window).off('resize.' + windowResizeId).on('resize.' + windowResizeId, function() {
				refresh();
			});
		};

		// リサイズイベント削除
		var removeResizeEvent = function() {
			$(window).off('resize.' + windowResizeId);
		};

		// optionに応じて処理を振り分け
		var alignFunc;
		if (settings.base) {
			alignFunc = function() {
				$(_this).find(settings.base).each(function() {
					if (settings.col > 1) {
						setHeightByRow($(this).find(settings.target));
					} else {
						setHeight($(this).find(settings.target));
					}
				});
			};
		} else {
			alignFunc = function() {
				if (settings.col > 1) {
					setHeightByRow($(_this).find(settings.target));
				} else {
					setHeight($(_this).find(settings.target));
				}
			};
		}
		if (settings.resizable) {
			attachResizeEvent();
		}
		alignFunc();

		var refresh = function() {
			destroy();
			alignFunc();
		};

		var destroy = function() {
			$(_this).find(settings.target).css('height', '');
		};


		/**
		 *
		 * PUBLIC FUNCTIONS
		 *
		 */

		// 高さ揃え再設定
		_this.refresh = function() {
			refresh();
			if (settings.resizable) {
				attachResizeEvent();
			}
		};

		// 高さ揃えを解除
		_this.destroy = function() {
			destroy();
			removeResizeEvent();
		};

		return this;
	};
})(jQuery);


/**
 * 初期設定
 *
 * @date 2014-07-09
 */
 (function($) {
 	var $win = $(window);

 	({
 		conf: {
			flg_enlarged:false, // フルサイズ用の画像に変換したかのフラグ
			flg_shrinked:false	// SP用の画像に変換したかのフラグ
		},

		// 初期化
		init: function() {
			var self = this;
			$.siteRoot = $.getScriptRoot('common/js/base.js');
			this.setDevice();
			this.setBrowser();
			this.setOrientation();
			this.getTweetJson();
			if(!$.ua.isTouchDevice) $('.rollover').fadeRollover();
			$(function() {
				$.scroller();
				self.changeSrc();
				self.uiHeader();
			});
			$(window).load(function() {
				self.hideURLTextField();
			});
			$(window).resize(function() {
				self.changeSrc();
			});
		},

		// Twitter用Josn取得
		getTweetJson: function() {
			$.getJSON(
				location.protocol + '//' + location.host +'/common_gochi/tw-share.php',
				function(data){
					$('.social__item a').attr('href','http://twitter.com/intent/tweet?text=' + data.share_title +'&url=http://gochikai.com/');
				}
			);
		},

		// デバイスの種類を表すclass属性をhtml要素に設定

		setDevice: function() {
			var device = ($.ua.isAndroid) ? 'android'
			: ($.ua.isIPhone) ? 'iphone'
			: ($.ua.isIPad) ? 'ipad'
			: false;
			if (device) $('html').eq(0).addClass(device);
		},

		// ブラウザの種類を表すclass属性をhtml要素に設定
		setBrowser: function() {
			var browser = ($.ua.isSafari) ? 'safari'
			: ($.ua.isFirefox) ? 'firefox'
			: ($.ua.isChrome) ? 'chrome'
			: ($.ua.isIE) ? 'ie'
			: false;
			if (browser) $('html').eq(0).addClass(browser);
		},

		// オリエンテーションを表すclass属性をhtml要素に設定
		setOrientation: function() {
			var orientation = (window.orientation === 0) ? 'portrait' : 'landscape';
			var elm = $('html').eq(0).addClass(orientation);
			$(window).portrait(function() {
				elm.removeClass('landscape').addClass('portrait');
			}).landscape(function() {
				elm.removeClass('portrait').addClass('landscape');
			});
		},

		// URL text fieldを非表示
		hideURLTextField: function() {
			if (!$.ua.isIPhone && !$.ua.isAndroidMobile) return;
			var win = window;
			if (win.pageYOffset > 0) return;
			var h = $(document).height();
			if ($.ua.isIPhone && h < ((win.orientation === 0) ? screen.availHeight : screen.availWidth)) return;
			if ($.ua.isAndroid && h < Math.ceil(win.outerHeight / win.devicePixelRatio)) return;
			var top = ($.ua.isAndroid) ? 1 : 0;
			var delay = 100;
			setTimeout(function() {
				win.scrollTo(0, top);
			}, delay);
		},

		// ヘッダーのUI
		uiHeader: function(){
			var timerId;

			var contenMask = $('.content__mask');
			var header = $('.header');
			var headerHeight = header.height() + parseInt(header.css('border-top-width'),10);
			var heaerInner = $('.header__inner');
			var headerBtn = $('.header__is-btn--burger');
			var headerMenu = $('.header__menu');
			var headerMenuFlag = false;

			var headerFixPosition = $('.main').offset().top;

			var headerMini = $('#header__mini');
			var headerMiniFlag = false;
			var headerMiniSpeed = 400;
			var headerMiniEasing = 'swing';

			var contentMain = $('.main');


			function closeMenu(){
				contenMask.stop().fadeOut();

				headerBtn.removeClass('header__is-btn--active');
				header.addClass('header--shadow');
				headerMini.addClass('header--shadow');

				headerMenu.stop().animate({
					right: -headerMenu.width()
				}, 300 , function(){
					heaerInner.addClass('header__inner--hidden');
				});
				headerMenuFlag = false;
			}

			function openMenu(){
				contenMask.stop().fadeIn();

				headerBtn.addClass('header__is-btn--active');
				header.removeClass('header--shadow');
				headerMini.removeClass('header--shadow');
				heaerInner.removeClass('header__inner--hidden');

				headerMenu.stop().animate({	right: 0 });

				headerMenuFlag = true;
			}

			// メニューボタン
			$('.header__is-btn').on('click', function(event) {
				event.preventDefault();
				if (headerMenuFlag) {
					closeMenu();
				} else {
					openMenu();
				}
			});

			// ページトップでメニュー解除
			contenMask.on('click', function (){
				closeMenu();
			});

			// ヘッダー固定
			// var offset = header.offset();
			if (!$.ua.isMobile) {
				$win.on('scroll', function() {
					if($win.scrollTop() > headerFixPosition){
						if (headerMiniFlag === false) {
							header.addClass('header--fixed');
							headerMenu.removeClass('header__menu--static');
							headerMini.stop().animate({ top: ''}, headerMiniSpeed, headerMiniEasing);
							contentMain.css('padding-top', headerHeight);
							headerMiniFlag = true;
						} else {
							return;
						}
					} else {
						if (headerMiniFlag === true) {
							closeMenu();
							header.removeClass('header--fixed');
							headerMenu.addClass('header__menu--static');
							headerMini.stop().animate({ top: -headerHeight}, headerMiniSpeed, headerMiniEasing);
							contentMain.css('padding-top', '');
							headerMiniFlag = false;
						} else {
							return;
						}
					}
				});
			}

			// リサイズ時・オリエンテーションチェンジ時のメニューの挙動調整
			$win.on('resize orientationchange', function() {
				headerMenu.css('right', '');
				if (headerMenuFlag) {
					closeMenu();
				}
			});

		},

		// 画像パス切り替え（ロード時、リサイズ時に実行）
		changeSrc: function() {
			var reg;
			var self = this;
			var conf = self.conf;

			// ウインドウ幅が640px未満に変化した場合、画像のパスを変更
			if(conf.flg_shrinked === false) {
				if($(window).width() < 640) {
					$('img[data-src]').each(function() {
						reg = /(_l.)+(jpg|png|gif)/;
						var result = $(this).attr('src').match(reg);
						if(result) {
							$(this).attr('src', $(this).attr('src').replace(result[0], '_s.' + RegExp.$2));
						}
					});
					conf.flg_shrinked = true;
					conf.flg_enlarged = false;
				}
			}

			// ウインドウ幅が640px以上に変化した場合、画像のパスを変更
			if(conf.flg_enlarged === false) {
				if($(window).width() >= 640) {
					$('img[data-src]').each(function() {
						reg = /(_s.)+(jpg|png|gif)/;
						var result = $(this).attr('src').match(reg);
						if(result) {
							$(this).attr('src', $(this).attr('src').replace(result[0], '_l.' + RegExp.$2));
						}
					});

					conf.flg_shrinked = false;
					conf.flg_enlarged = true;
				}
			}
		}
	}).init();
})(jQuery);
