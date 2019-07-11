/**
 * form.js
 *
 * @date 2015-06-24
 */

;(function($){
	({
		// 初期化
		init: function() {
			var self = this;

			$(function(){
				self.inputSelectPlaceholderColorChange();
			});
		},

		inputSelectPlaceholderColorChange: function(){
			var self = this;
			var $select = $('#form__input__category');

			$select.change(function () {
				if ($(this).val() === '') {
					$(this).addClass('is-noSelect');
				} else {
					$(this).removeClass('is-noSelect');
				}
			});

			$select.change();
		}


	}).init();
})(jQuery);
