var Sheet = new (function() {
	this.init = function() {
		jQuery('sheet').each(function() {
			var sheet = jQuery(this);
			var rows = sheet.find('row');
			var cellWidths = [];
			var rowHeights = [];
			rows.each(function(i) {
				var height = parseInt(jQuery(this).height()) - 5;
				if(typeof rowHeights[i] == 'undefined' || width > rowHeights[i]) {
					rowHeights[i] = height;
				}
				rows.eq(i).find('cell').each(function(j) {
					var width = parseInt(jQuery(this).width());
					if(typeof cellWidths[j] == 'undefined' || width > cellWidths[j]) {
						cellWidths[j] = width;
					}
				});
			});
			rows.each(function(i) {
				rows.eq(i).find('cell').each(function(j) {
					jQuery(this).width(cellWidths[j]).height(rowHeights[i]);
				});
			});
			var totalWidth = 0;
			for(var i = 0; i < cellWidths.length; i++) {totalWidth += cellWidths[i] + 11;}
			sheet.width(totalWidth);
		});
	}
	jQuery(document).ready(this.init);
});
var Form = function(params) {
	if(typeof params != "object" || typeof params.formId != "string") {return false;}
	if(typeof params.submitMessage != "string") {params.submitMessage = "Submitting...";}
	jQuery('form#'+params.formId).ajaxForm({
		beforeSubmit: function() {
			PopupMessage.show({message: params.submitMessage});
		},
		success: function(data) {
			Util.serverResponse(data, params);
		},
		error: function() {
			Util.serverResponse('{"title": "404 Error", "message": "Something went wrong, unable to submit form."}', params);
		}
	});
}
var Comments = new (function() {
	this.deleteComment = function(id) {
		PopupMessage.show({
			message: "Are you sure you want to delete this comment?",
			buttons: [{
				value: "Yes",
				onclick: "Comments.realDeleteComment("+id+");"
			}, {
				value: "Cancel",
				onclick: "PopupMessage.close();"
			}]
		});
	}
	this.realDeleteComment = function(id) {
		PopupMessage.show({message: "Deleting comment.."});
		jQuery.ajax({
			url: 'comment/delete',
			type: 'POST',
			data: {id: id, test: "test", csrf_token: jQuery('input[name="csrf_token"]').val()},
			success: function(data) {
				Util.serverResponse(data, {
					successAction: function() {
						document.location.reload(true);
					}
				});
			}
		});		
	}
});
var PopupMessage = new (function() {
	this.init = function() {
		if(jQuery('#body_wrapper').length == 0) {
			jQuery('body').append("<div id='body_wrapper'></div>");
			jQuery('body').children(':not(#body_wrapper)').each(function() {
				jQuery(this).appendTo(jQuery('#body_wrapper'));
			});
		}
		var html = "<div class='popup'><div class='box'><h3></h3><div class='message'></div></div></div>";
		jQuery('body').append(html);
	}
	this.show = function(opts) {
		if(typeof opts != 'object') {return false;}
		if(typeof opts.message != 'string') {return false;}
		if(typeof opts.title != 'string') {opts.title = 'Message'}
		if(typeof opts.buttons == 'object') {
			opts.message += "<div class='options'>";
			for(var i = 0; typeof opts.buttons[i] == 'object'; i++) {
				if(typeof opts.buttons[i].onclick != 'string') {opts.buttons[i].onclick = "PopupMessage.close();"}
				opts.message += "<input type='button' onclick='"+opts.buttons[i].onclick+"' value='"+opts.buttons[i].value+"' />";
			}
			opts.message += "</div>";	
		}
		jQuery('.popup h3').html(opts.title);
		jQuery('.popup .message').html(opts.message);
		jQuery('.popup .options input').eq(0).focus();
		jQuery('.popup').addClass('active');
		PopupMessage.resize();
	}
	this.close = function() {
		jQuery('.popup').removeClass('active');
	}
	this.resize = function() {
		var winHeight = jQuery(window).height();
		jQuery('.popup.active').each(function() {
			var box = jQuery(this).find('.box');
			var body = jQuery('body');
			if(box.height() > winHeight) {
				if(!body.hasClass('oversizePopup')) {
					var ele = jQuery('#body_wrapper *:eq(0)');
					var scrollTop = jQuery(document).scrollTop();
					var marginTop = ele.css('margin-top');
					ele.data({'margin-top': marginTop, 'scroll-top': scrollTop}).css({'margin-top': -scrollTop});
				}
				jQuery('body').addClass('oversizePopup');
				box.css({'margin-top':''});
			} else {
				if(body.hasClass('oversizePopup')) {
					var ele = jQuery('#body_wrapper *:eq(0)');
					ele.css({'margin-top': ele.data('margin-top')});
					jQuery('body').removeClass('oversizePopup');
					jQuery(document).scrollTop(ele.data('scroll-top'));
				}
				box.css({'margin-top':-parseInt(box.height())/2});
			}
		});
	}
	jQuery('document').ready(this.init);
	jQuery(window).resize(this.resize);
});
var Util = new (function() {
	this.htmlEntities = function(str) {
	    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}
	this.serverResponse = function(data, params) {
		if(typeof params != "object") {params = {};}
		if(typeof params.successAction != "function") {
			params.successAction = function() {document.location.reload(true);}
		}
		if(typeof params.failAction != "string") {params.failAction = "PopupMessage.close();";}
		if(data.indexOf("{") == 0) {data = jQuery.parseJSON(data);}
		else {data = {message: Util.htmlEntities(data)};}
		if(data.status == 'success') {
			if(typeof data.action == 'string') {
				eval(data.action);
			} else {
				params.successAction();	
			}
		} else {
			var title = "Error";
			var message = "Unable to process.";
			if (typeof data.field == 'string') {params.failAction += ' jQuery("form input[name='+data.field+']").focus();';}
			if (typeof data.message == 'string' && data.message.length > 0) {message = data.message;}
			if (typeof data.title == 'string') {title = data.title;}
			PopupMessage.show({
				title: title,
				message: message,
				buttons: [{
					value: "Ok",
					onclick: params.failAction
				}]
			});
		}
	}
});