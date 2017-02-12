{* REQUIRED PARAMETERS *}
{*
    $item
	$meta - item's meta
*}

{*var $meta = $item->meta(item-data)*}
{var $imageUrl = $item->hasImage ? $item->imageUrl : $options->theme->item->noFeatured}
{var $test = ""}
{var $title = $item->title}
{if $title != 'Tyrone Husseini' && $title != 'Arthur Guillerm'}
	{var $test = "Devenez le prochain"}
{/if}
<div class="item-data">
	<h3 id="title-marker">{$test} {$title}</h3>
	<h2 id="subtitle-marker">{$meta->subtitle}</h2>
	{* <span class="item-address">{!$meta->map[address]}</span> *}
	{if $title != 'Tyrone Husseini' && $title != 'Arthur Guillerm'}
	<a href="/rejoignez-nous">
		<span class="item-button">{__ 'REJOIGNEZ NOUS'}</span>
	</a>
	{else}
	<a href="{!$item->permalink}">
		<span class="item-button">{__ 'Show More'}</span>
	</a>
	{/if}
</div>
<div class="item-picture">
	<img src="{imageUrl $imageUrl, width => 145, height => 180, crop => 1}" alt="image">
	{if $elements->unsortable[header-map]->option('infoboxEnableTelephoneNumbers') && $meta->telephone }
	<a href="tel:{!$meta->telephone}" class="phone">{!$meta->telephone}</a>
	{/if}
</div>



