// refactored
jQuery(document).ready(function(){
	// basic init
	var selectors = [
		".review-stars-container .review-stars",
		".review-container .review-rating-overall",
		".review-container .review-rating-stars"
	];
	jQuery(selectors.join(', ')).raty({
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

	// special init -> form init
	jQuery('.reviews-form-container .review-ratings .review-rating-stars').raty({
		font		: true,
		halfShow	: false,
		starHalf	: 'fa-star-half-o',
		starOff		: 'fa-star-o',
		starOn		: 'fa-star',
	});

});