<?php

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// On supprime le nom de la compagnie dans la page de checkotu
function custom_override_checkout_fields( $fields ) {
     unset($fields['billing']['billing_company']);
     return $fields;
}
/**
 * Insert the opening anchor tag for products in the loop.
 */
function my_woocommerce_template_loop_product_link_open() {
	//$suffixe = "1" ."/";
	//Recuperation de l'id
	$product_id = get_the_ID();
	//On fait pointer le poduit booked sur un veritable produit
	//On supprime le /
	//$link  = substr($link, 0,-1);
	//$link .= $suffixe;
	//On ajoute la nomenclature
	//A cause de la quick view on flingue le lien
	$anchor = "#wpb_wl_quick_view_$product_id";
	//wp_enqueue_script('lightboxFull' , get_stylesheet_directory_uri() . '/lightboxFull.js');
	echo "<a href='" . $anchor . "' class='woocommerce-LoopProduct-link wpb_wl_preview open-popup-link'>";
	
}

/**
* Affichage des infos de la lightbox perso
*/
function my_wpb_wl_hook_quickview_content(){
	wp_enqueue_style('lightbox',get_stylesheet_directory_uri() . '/lightboxFull.css');
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

			<!-- Bouton de prise de rendez vous -->
			<a href="#calendrier" class="go-calend mfp-close">PRENDRE RDV</a>

		</div>
	</div>
	<?php
}
// Function to change email address

function wpb_sender_email( $original_email_address ) {
    return 'contact@cyclick.fr';
}

// Function to change sender name
function wpb_sender_name( $original_email_from ) {
	return 'CYCLICK';
}

//SUpression dashboard widget for non admin user
function pinkstone_remove_jetpack() {
	if( class_exists( 'Jetpack' ) && !current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'jetpack' );
	}
}

function remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

}

if (!current_user_can('manage_options')) {
	add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
}
add_action( 'admin_init', 'pinkstone_remove_jetpack' );

//Changement du statut de la commande en complete de facon automatique
add_action( 'woocommerce_thankyou', 'abw_woocommerce_auto_complete_order' );
function abw_woocommerce_auto_complete_order( $order_id ) {
    if ( !$order_id ) return;
    $order = new WC_Order( $order_id );
    $order->update_status( 'completed' );
}

// Hooking up our functions to WordPress filters 
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
//wp_enqueue_script('fondateur' , get_stylesheet_directory_uri() . '/fondateur.js');

//On supprime le hook et on ajoute avec notre fonction
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
add_action('woocommerce_before_shop_loop_item','my_woocommerce_template_loop_product_link_open');
remove_action( 'woocommerce_after_shop_loop_item', 'wpb_wl_hook_quickview_content');
add_action( 'woocommerce_after_shop_loop_item','my_wpb_wl_hook_quickview_content' );

remove_all_filters('woocommerce_cart_item_name');	