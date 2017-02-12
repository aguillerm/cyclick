{* REQUIRED PARAMETERS *}
{*
    $post - wp entity of Ait Review
	$meta - review meta
	$item - wp object of review's item
*}

<div class="review-container">
	<div class="review-content">
		<div class="entry-content">
			<p class="txtrows-2">
			{if $post->hasContent}
				{!$post->content|striptags|trim|truncate: 140}
			{else}
				{!$post->excerpt|striptags|trim|truncate: 140}
			{/if}</p>
		</div>

		<span class="review-rating-overall" data-score="{$rating}"></span>
	</div>

	<div class="review-details">
		<div class="author">{!$post->title}</div>
		<div class="item"><i class="fa fa-map-marker"></i> <a href="{get_permalink($item->ID)}#review">{!$item->post_title}</a></div>
	</div>
</div>
