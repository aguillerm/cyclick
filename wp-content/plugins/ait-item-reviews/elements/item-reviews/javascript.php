<script id="{$htmlId}-script">


(function($, $window, $document, undefined){
	"use strict";

	var element = {
		container: jQuery("#"+{$htmlId}),

		buffer: {
			size: 10,
			items: [],
			index: 0,
			foundPosts: 0,
			offset: 0,
			optionalArgs: {},
		},



		getItems: function(direction) {
			var request_data = {
				requestData: {
					args: {
						posts_per_page: element.buffer.size,
						offset: element.buffer.offset,
					},
				}
			};

			// disable arrows until request is finished
			element.container.find('.navigation-arrows').addClass('wait');

			ait.ajax.post('getReviews', request_data).done(function(data){
				if(data.status == true){
					element.buffer.items = data.reviews;
					if (direction == 'next') {
						element.buffer.index = 0;
					} else {
						element.buffer.index = element.buffer.size - 1;
					}
					element.displayItem(data.reviews[element.buffer.index]);
					element.updateArrows();
					element.container.find('.navigation-arrows').removeClass('wait');
				} else {
					console.log("not success");
				}
			}).fail(function(){
				console.log("fail");
			});
		},



		hasNext: function() {
			var queryIndex = element.buffer.offset + element.buffer.index;
			if (queryIndex + 1 < element.buffer.foundPosts) {
				return true;
			}
			return false
		},



		hasPrev: function() {
			var queryIndex = element.buffer.offset + element.buffer.index;
			if (queryIndex > 0) {
				return true;
			}
			return false;
		},



		endOfBuffer: function(direction) {
			switch(direction) {
				case 'next':
					if (element.buffer.index == element.buffer.size) {
						return true;
					}
					break;
				case 'prev':
					if (element.buffer.index == -1) {
						return true;
					}
					break;
				default:
					return false;
			}
		},



		willLoadMore: function(direction) {
			if (element.endOfBuffer(direction)) {
				return true;
			}
			return false;
		},



		displayItem: function(item) {
			element.container.find('.ajax-container').html(item);

			element.container.find('.review-rating-overall').raty({
				font: true,
				readOnly:true,
				halfShow:true,
				starHalf:'fa-star-half-o',
				starOff:'fa-star-o',
				starOn:'fa-star',
				score: function() {
					return jQuery(this).attr('data-score');
				},
			});
		},



		updateArrows: function() {
			var prev = true;
			var next = true;

			if (!element.hasPrev()) {
				prev = false;
			}

			if (!element.hasNext()) {
				next = false;
			}

			if (prev) {
				element.container.find('.navigation-arrows .arrow-left').removeClass("disabled");
			} else {
				element.container.find('.navigation-arrows .arrow-left').addClass("disabled");
			}

			if (next) {
				element.container.find('.navigation-arrows .arrow-right').removeClass('disabled');
			} else {
				element.container.find('.navigation-arrows .arrow-right').addClass('disabled');
			}
		},
	};

	// ===============================================
	// Start
	// -----------------------------------------------

	$(function(){
		element.buffer.size = {$elmData->bufferSize};
		element.buffer.items = {$elmData->reviews};
		element.buffer.foundPosts = {$elmData->foundPosts}

		// get order and orderby parameters from element options
		element.buffer.optionalArgs['orderby'] = {$el->option('orderby')};
		element.buffer.optionalArgs['order'] = {$el->option('order')};

		element.updateArrows();

		// left arrow click action
		// display previous item from buffer if available or query more items if available
		element.container.find('.navigation-arrows .arrow-left').click(function(){
			if (element.container.find('.navigation-arrows').hasClass('wait')) {
				console.log("Wait for ajax response!");
				return;
			}

			element.buffer.index -= 1;

			if (element.willLoadMore('prev')) {
				element.buffer.offset = element.buffer.offset - element.buffer.size;
				element.getItems('prev');
			} else {
				element.displayItem(element.buffer.items[element.buffer.index]);
				element.updateArrows();
			}
		});


		// right arrow click action
		// display next item from buffer if available or query more items if available
		element.container.find('.navigation-arrows .arrow-right').click(function(){
			if (element.container.find('.navigation-arrows ').hasClass('wait')) {
				console.log("Wait for ajax response!");
				return;
			}

			element.buffer.index += 1;

			if (element.willLoadMore('next')) {
				element.buffer.offset = element.buffer.offset + element.buffer.size;
				element.getItems('next');
			} else {
				element.displayItem(element.buffer.items[element.buffer.index]);
				element.updateArrows();
			}
		});

	});



})(jQuery, jQuery(window), jQuery(document));

jQuery(window).load(function(){
	{if $options->theme->general->progressivePageLoading}
		if(!isResponsive(1024)){
			jQuery('#{!$htmlId}-main').waypoint(function(){
				jQuery('#{!$htmlId}-main').addClass('load-finished');
			}, { triggerOnce: true, offset: "95%" });
		} else {
			jQuery('#{!$htmlId}-main').addClass('load-finished');
		}
	{else}
		jQuery('#{!$htmlId}-main').addClass('load-finished');
	{/if}
});

</script>
