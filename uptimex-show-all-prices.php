<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Uptimex Show All Prices In Single Product Page
Description: On hover, show all currencies prices in single product page
Author: Aram Dekart
Author URI: https://github.com/uptimex
Plugin URI: https://github.com/uptimex/uptimex-show-all-prices
*/


function woocss_change_product_price_display( $price ) {
	
	// Making global $WOOCS and $product
	global $WOOCS;
	global $product;
	
	// Get required information
	$product_id = $product->id;
	$product_obj = wc_get_product( $product_id );
	$nativePrice = $product_obj->get_price();	
	$all_currencies = $WOOCS->get_currencies();

	
	// Open string
	$str = '<div class="woocssShowBlock">';
	
	// Loop currencies, calculate prices and collecting string
	foreach($all_currencies as $currency) {
		// Check if it is not current currency
		if(get_woocommerce_currency() != $currency['name']) {
			$str .= $currency['name'] . ': ' . ($currency['rate']*$nativePrice) . '<br />';
		}
	}
	
	// Close collected string
	$str .= '</div>';
	
	// Add collected string to price html.. if single product page
	if(is_product()) {		
		$price .= $str ;
	}
	
	return $price;
}

add_filter( 'woocommerce_get_price_html', 'woocss_change_product_price_display' );


function woocss_scripts_and_styles()
{
	
	wp_enqueue_style( 'uptimex-wooss-style', plugins_url( '/css/uptimex-show-all-prices.css', __FILE__ ) );
	
	
    // Register the script like this for a plugin:
    wp_register_script( 'uptimex-wooss-script', plugins_url( '/js/uptimex-show-all-prices.js', __FILE__ ) );

	wp_enqueue_script( 'uptimex-wooss-script', false, array( 'uptimex-jquery' ), false, true );		
}
add_action( 'wp_enqueue_scripts', 'woocss_scripts_and_styles' );