var Sheet = new (function() {
	this.init = function() {
		$('sheet').each(function() {
			var sheet = $(this);
			var rows = sheet.find('row');
			var cellWidths = [];
			var rowHeights = [];
			rows.each(function(i) {
				var height = parseInt($(this).height()) - 5;
				if(typeof rowHeights[i] == 'undefined' || width > rowHeights[i]) {
					rowHeights[i] = height;
				}
				rows.eq(i).find('cell').each(function(j) {
					var width = parseInt($(this).width());
					if(typeof cellWidths[j] == 'undefined' || width > cellWidths[j]) {
						cellWidths[j] = width;
					}
				});
			});
			rows.each(function(i) {
				rows.eq(i).find('cell').each(function(j) {
					$(this).width(cellWidths[j]).height(rowHeights[i]);
				});
			});
			var totalWidth = 0;
			for(var i = 0; i < cellWidths.length; i++) {totalWidth += cellWidths[i] + 11;}
			sheet.width(totalWidth);
		});
	}
	$(document).ready(this.init);
});
var CommentForm = new (function() {
	this.load = function(id) {
		var form = $('form#'+id);
	}
});
var PopupMessage = new(function() {
	this.init = function() {
		if($('#body_wrapper').length == 0) {
			$('body').append("<div id='body_wrapper'></div>");
			$('body').children(':not(#body_wrapper)').each(function() {
				$(this).appendTo($('#body_wrapper'));
			});
		}
		var html = "<div class='popup'><div class='box'><h3></h3><div class='message'></div></div></div>";
		$('body').append(html);
	}
	this.show = function(message, title) {
		if(typeof message != 'string') {return false;}
		if(typeof title != 'string') {title = 'Message'}
		$('.popup h3').html(title);
		$('.popup .message').html(message);
		$('.popup').addClass('active');
		PopupMessage.resize();
	}
	this.close = function() {
		$('.popup').removeClass('active');
	}
	this.resize = function() {
		var winHeight = $(window).height();
		$('.popup.active').each(function() {
			var box = $(this).find('.box');
			var body = $('body');
			if(box.height() > winHeight) {
				if(!body.hasClass('oversizePopup')) {
					var ele = $('#body_wrapper *:eq(0)');
					var scrollTop = $(document).scrollTop();
					var marginTop = ele.css('margin-top');
					ele.data({'margin-top': marginTop, 'scroll-top': scrollTop}).css({'margin-top': -scrollTop});
				}
				$('body').addClass('oversizePopup');
				box.css({'margin-top':''});
			} else {
				if(body.hasClass('oversizePopup')) {
					var ele = $('#body_wrapper *:eq(0)');
					ele.css({'margin-top': ele.data('margin-top')});
					$('body').removeClass('oversizePopup');
					$(document).scrollTop(ele.data('scroll-top'));
				}
				box.css({'margin-top':-parseInt(box.height())/2});
			}
		});
	}
	$('document').ready(this.init);
	$(window).resize(this.resize);
});