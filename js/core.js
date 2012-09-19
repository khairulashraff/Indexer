(function(root) {"use strict";
	var Search, ImagePreview;

	Search = (function () {
		return {
			table: null,
			searchInput: null,

			init: function() {
				var self = this;
				self.searchInput = $('#search');
				self.table = $("#idx");

				self.searchInput.keypress(function(e) {
					if(e.which == 13) 
					{
						var str = $(this).val();
						if(str == '')
						{
							self.reset();
						}
						else
						{
							self.search(str);
						}
					}
				});

				$('#btn-reset').click(function() {
					self.reset();
				});

				$('#btn-search').click(function() {
					var str = self.searchInput.val();
					self.search(str);
				});

				self.table.stupidtable();
			},
			search: function(str) {
				if($('input[name=deep]').is(':checked') == true) {
					window.location.href = 'index.php?search=' + str + '&deep=1';
				}
				else {
					$('tbody tr', this.table).hide();
					$('tbody td:first-child:icontains('+str+')', this.table).parent().show();
					this.highlight(str);
				}
			},
			reset: function() {
				var self = this;
				$('tbody tr', self.table).show();
				self.searchInput.val('');
				self.resetHighlight();
			},
			resetHighlight: function() {
				if($('span.highlight').length > 0) {
					$('span.highlight').replaceWith($('span.highlight').html());
				}
			},
			highlight: function (str)
			{
				this.resetHighlight();
				
				$('#idx a:icontains(' + str + ')').each(function() {
					var reg = new RegExp('('+str+')', 'i');
					$(this).html(
						$(this).html().replace(reg, '<span class="highlight">$1</span>')
					);
				});
			}			
		};
	}());
	
	ImagePreview = (function () {
		return {
			modal: null,
			
			init: function() {
				self = this;
				self.modal = $('#image-preview');

				$('a').filter(function(){ return /(jpe?g|png|gif)$/i.test($(this).attr('href')); }).click(function(e) {
					e.preventDefault();
					self.modal.modal();
					var imgSrc = $(this).attr('href');
					var imgNode = '<img src="'+ imgSrc +'" title="Click to close" rel="tooltip">';
					self.modal.html(imgNode);
					self.modal.css({'margin' : 0});

					var img = new Image();
					img.onload = function() {
						var left = $(window).width()/2 - this.width/2;
						var top = $(window).height()/2 - this.height/2;
						self.modal.css({'width' : this.width, 'left' : left, 'top': top});
						$('img', self.modal).tooltip()
					}
					img.src = imgSrc;
				});

				$(document).on('click','#image-preview img', function(e) {
					self.modal.modal('hide');
				});
			}
		};
	}());
	
	$(function() {
		Search.init();
		ImagePreview.init();
	});
	
}(window));