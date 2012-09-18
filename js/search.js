(function(root) {"use strict";
	var Search;

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

				$('#btn-search').click(function() {
					if(self.searchInput.val() != '') {
						self.reset();
					}
				});

				self.table.stupidtable();
			},
			search: function(str) {
				$('tbody tr', this.table).hide();
				$('tbody td:first-child:icontains('+str+')', this.table).parent().show();
				$('#btn-search i').removeClass().addClass('icon-repeat');
			},
			reset: function() {
				var self = this;
				$('tbody tr', self.table).show();
				$('#btn-search i').removeClass().addClass('icon-search');
				self.searchInput.val('');
			}
		};
	}());
	
	$(function() {
		Search.init();
	});
	
}(window));