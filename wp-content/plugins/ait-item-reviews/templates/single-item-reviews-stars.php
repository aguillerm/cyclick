{var $rating_count = AitItemReviews::getRatingCount($post->id)}
{var $rating_mean = floatval(get_post_meta($post->id, 'rating_mean', true))}

{var $showCount = isset($showCount) ? $showCount : false}
<div class="review-stars-container">
	{if $rating_count > 0}
		<div class="content" itemscope itemtype="http://schema.org/AggregateRating">
			{* RICH SNIPPETS *}
			<span style="display: none" itemprop="itemReviewed">{!$post->title}</span>
			<span style="display: none" itemprop="ratingValue">{$rating_mean}</span>
			<span style="display: none" itemprop="ratingCount">{$rating_count}</span>
			{* RICH SNIPPETS *}
			<span class="review-stars" data-score="{$rating_mean}"></span>
			{if $showCount}<span class="review-count">({$rating_count})</span>{/if}
			<a href="{$post->permalink}#review"><?php _e('Submit your rating', 'ait-item-reviews') ?></a>
		</div>
	{else}
		<div class="content">
			<a href="{$post->permalink}#review"><?php _e('Submit your rating', 'ait-item-reviews') ?></a>
		</div>
	{/if}
</div>