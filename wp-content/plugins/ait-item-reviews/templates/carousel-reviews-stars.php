{if isset($item)}
	{var $rating_count = AitItemReviews::getRatingCount($item->id)}
	{var $rating_mean = get_post_meta($item->id, 'rating_mean', true)}

	{var $showCount = isset($showCount) ? $showCount : false}
	<div class="review-stars-container">
		<div class="content">
			{if $rating_count > 0}
				<span class="review-stars" data-score="{$rating_mean}"></span>
				{if $showCount}<span class="review-count">({$rating_count})</span>{/if}
			{else}
				<a href="{$item->permalink}#review"><?php _e('Rate now','ait-item-reviews') ?></a>
			{/if}
		</div>
	</div>
{/if}