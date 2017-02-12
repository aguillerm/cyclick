<?php

/**
 * Woocommerce Lighbox by WpBean
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


add_action( 'woocommerce_after_shop_loop_item','wpb_wl_hook_quickview_link', 11 );

function wpb_wl_hook_quickview_link(){
	echo '<div class="wpb_wl_preview_area"><a class="wpb_wl_preview open-popup-link" href="#wpb_wl_quick_view_'.get_the_id().'" data-effect="mfp-zoom-in">'.__( 'Quick View','woocommerce-lightbox' ).'</a></div>';
}


add_action( 'woocommerce_after_shop_loop_item','wpb_wl_hook_quickview_content' );

function wpb_wl_hook_quickview_content(){
	global $post, $woocommerce, $product;
	?>
	<div id="wpb_wl_quick_view_<?php echo get_the_id(); ?>" class="mfp-hide mfp-with-anim wpb_wl_quick_view_content wpb_wl_clearfix">
		<div class="wpb_wl_images">
			<?php
				if ( has_post_thumbnail() ) {

				$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
				$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
				$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
					'title' => $image_title
					) );

				$attachment_count = count( $product->get_gallery_attachment_ids() );

				if ( $attachment_count > 0 ) {
					$gallery = '[product-gallery]';
				} else {
					$gallery = '';
				}

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $post->ID );

				} else {

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce-lightbox' ) ), $post->ID );

				}
			?>
		</div>
		<div class="wpb_wl_summary">
			<!-- Product Title -->
			<h2 class="wpb_wl_product_title"><?php the_title();?></h2>

			<!-- Product Price -->
			<?php if ( $price_html = $product->get_price_html() ) : ?>
				<span class="price wpb_wl_product_price"><?php echo $price_html; ?></span>
			<?php endif; ?>

			<!-- Product short description -->
			<?php woocommerce_template_single_excerpt();?>

			<!-- Product cart link -->
			<?php woocommerce_template_single_add_to_cart();?>

		</div>
	</div>
	<?php
}
