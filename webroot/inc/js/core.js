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
/*
	params = {
		formId : "",  // Id of the form
		submitMessage : "",  // Message displayed while sending
		sucessAction : {},  // Function called on success
		failAction : "" // Function called on fail
	}
*/
var Form = function(params) {
	console.log(typeof params +"\n"+typeof params.formId +"\n"+typeof params.submitMessage +"\n"+typeof params.successAction +"\n"+typeof params.failAction +"\n")
	if(	typeof params != "object"
		|| typeof params.formId != "string"
		|| typeof params.submitMessage != "string"
		|| typeof params.successAction != "function"
		|| typeof params.failAction != "string")
	{ return false; }

	$('form#'+params.formId).ajaxForm({
		dataType: 'json',
		beforeSubmit: function() {
			PopupMessage.show({message: params.submitMessage});
		},
		success: function(data) {
			if(data.status == 'success') {
				params.sucessAction();
			} else {
				if(typeof data.message == 'string') {
					data = data.message;
				}
				PopupMessage.show({
					message: data,
					buttons: [{
						value: "Ok",
						onclick: params.failAction
					}]
				});
			}
		}
	})
}
var Signup = new (function() {
	var form;
	this.Form = function(id) {
		var params = {
			formId: id,
			submitMessage: "Creating account...",
			successAction: function() {
				document.location.href = document.getElementsByTagName("base").href;
			},
			failAction: "PopupMessage.close();"
		};
		form = new Form(params);
	}
});
var Comments = new (function() {
	this.Form = function(id) {
		$('form#'+id).ajaxForm({
			dataType: 'json',
			beforeSubmit: function() {
				PopupMessage.show({message: "Adding comment.."});
			},
			success : function(data) {
				if(data.status == 'success') {
					document.location.reload(true);
				} else {
					PopupMessage.show({
						message: data.message,
						buttons: [{
							value: "Ok",
							onclick: (true || data.error_id === 1) ? "PopupMessage.close();" : "document.location.reload(true);"
						}]
					});
				}
			}
		});
	}
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
		$.ajax('comment/delete/'+id, {
			success: function(data) {
				data = $.parseJSON(data);
				if(data.status == 'success') {
					document.location.reload(true);
				} else {
					PopupMessage.show({
						message: "Unable to delete comment.",
						buttons: [{
							value: "Ok",
							onclick: "PopupMessage.close();"
						}]
					});
				}
			}
		});		
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
		$('.popup h3').html(opts.title);
		$('.popup .message').html(opts.message);
		$('.popup .options input').eq(0).focus();
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