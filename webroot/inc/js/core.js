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
		var html = "<div class='popup'><div class='message'><h4>Title</h4>message</div></div>";
		$('body').append(html);
	}
	$('document').ready(this.init);
});