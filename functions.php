<?php 

//NEW EVENT GTM FOR GA ENHANCED
function push_to_datalayer($order_id) {
    
	$referral_done = get_post_meta( $order_id, '_referral_done', true￼ );
	if( empty($referral_done￼) ) {
		$order = wc_get_order( $order_id );
		?>
		<script type='text/javascript'>
			window.dataLayer = window.dataLayer || [];
					 dataLayer.push({
						 'event' : 'ITTD transaction',
						 'ecommerce' : {
							 'purchase' : {
								 'actionField' : {
									 'id': '<?php echo $order->get_order_number(); ?>',
									 'affiliation': '<?php echo get_option("blogname"); ?>',
									 'revenue' : '<?php echo number_format($order->get_total(), 2, ".", ""); ?>', 
									 'tax': '<?php echo number_format($order->get_total_tax(), 2 ,".", ""); ?>',
									 'shipping': '<?php echo number_format($order->calculate_shipping(), 2 , ".", ""); ?>',
									 <?php if($order->get_used_coupons()) : ?>
									  'coupon' : '<?php echo implode("-", $order->get_used_coupons()); ?>'
									 <?php endif; ?>
								 },
									 'products': [
											<?php
											 foreach ( $order->get_items() as $key => $item ) :
												$product = $order->get_product_from_item($item);
												$variant_name = ($item['variation_id']) ? wc_get_product($item['variation_id']) : '';
											?>
										{
											'item_name' : '<?php echo $item['name']; ?>',
											'item_id' : '<?php echo $item['product_id']; ?>',
											'price' : '<?php echo number_format($order->get_line_subtotal($item), 2, ".", ""); ?>',
											'item_brand' : '',
											'item_category' : "<?php echo strip_tags($product->get_categories(', ', '', '')); ?>",
											'item_variant' : '<?php echo ($variant_name) ? implode("-" , $variant_name->get_variation_attributes()) : ''; ?>',
											'quantity' : <?php echo $item['qty']; ?>,
											'coupon' : ''
										},
									<?php endforeach; ?>
									 ]
								 }

							 }
						 });
			</script>         
		<?php
	}  
}

add_action('woocommerce_thankyou' , 'push_to_datalayer');

// END NEW EVENT GTM FOR GA ENHANCED






include('custom-shortcodes/custom-shortcodes.php');

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);

function salient_child_enqueue_styles() {
		
		$nectar_theme_version = nectar_get_theme_version();
		wp_enqueue_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );
		
    if ( is_rtl() ) {
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
		}
}
/* Simple Solutions */
function register_my_menus() {
  register_nav_menus(
    array(
      'new-menu' => __( 'Footer Menu 1' ),
      'another-menu' => __( 'Footer Menu 2' ),
      'an-extra-menu' => __( 'Footer Menu 3' )
    )
  );
}
add_action( 'init', 'register_my_menus' );



add_filter( 'woocommerce_checkout_fields', 'bbloomer_shipping_phone_checkout' );
 
function bbloomer_shipping_phone_checkout( $fields ) {
   $fields['shipping']['shipping_phone'] = array(
      'label' => 'Phone',
      'required' => true,
      'class' => array( 'form-row-wide' ),
      'priority' => 25,
   );
   return $fields;
}
  
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'bbloomer_shipping_phone_checkout_display' );
 
function bbloomer_shipping_phone_checkout_display( $order ){
    echo '<p><b>Shipping Phone:</b> ' . get_post_meta( $order->get_id(), '_shipping_phone', true ) . '</p>';
}


add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true' );

// add_action( 'woocommerce_thankyou', 'bbloomer_redirectcustom');
  
// function bbloomer_redirectcustom( $order_id ){
//     $order = wc_get_order( $order_id );
//     $url = '';
//     if ( ! $order->has_status( 'failed' ) ) {
//         wp_safe_redirect( $url );
//         exit;
//     }
// }
add_filter( 'woocommerce_get_related_product_cat_terms', 'remove_related_product_categories', 10, 2 );
function remove_related_product_categories( $terms_ids, $product_id  ){
    return array();
}
add_filter( 'woocommerce_product_add_to_cart_text', function( $text ) {
    if ( 'Read more' == $text ) {
        $text = __( 'View Product', 'woocommerce' );
    }

    return $text;
} );
/**
 * @snippet       Upsell Area - WooCommerce Checkout
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.1
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_action( 'woocommerce_review_order_before_shipping', 'bbloomer_checkout_add_on', 9999 );
 
function bbloomer_checkout_add_on() {
   $product_ids = 28383;
   $in_cart = false;
   foreach( WC()->cart->get_cart() as $cart_item ) {
      $product_in_cart = $cart_item['product_id'];
      if ( in_array( $product_in_cart, $product_ids ) ) {
         $in_cart = true;
         break;
      }
   }
   if ( ! $in_cart ) {
      echo '<div class="gift-box-checkout"><h4>Would you like to add a gift box?</h4>';
      echo '<div class="gift-box-checkout"><div class="col-md-5 col-xs-5"><img src="https://babynoomie.com/wp-content/uploads/2021/06/gift-box-new.png" /></div>
<div class="col-md-3 gift-box-price col-xs-3"><h4>$3.50</h4></div>
<div class="col-md-4 col-xs-4">
	<a class="button  custom-btn-sp continue-btn-position" style="margin-right: 1em; width: auto" href="?add-to-cart=28383"> Add </a>
</div>
</div>
</div>';
   }
}
////////Begin - Simple Solutions - Only Display Variation Price //////////
function replace_variable_price_range_by_chosen_variation_price_woocommerce() {
    load_plugin_textdomain( 'replace-variable-price-range-by-chosen-variation-price-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'replace_variable_price_range_by_chosen_variation_price_woocommerce' );

add_action( 'woocommerce_before_single_product', 'check_if_variable_first' );
function check_if_variable_first(){
    if ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product->is_type( 'variable' ) ) {
            // removing the price of variable products
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

// Change location of
add_action( 'woocommerce_single_product_summary', 'custom_wc_template_single_price', 10 );
function custom_wc_template_single_price(){
    global $product;

// Variable product only
if($product->is_type('variable')):

    // Main Price
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
    $price = $prices[0] !== $prices[1] ? sprintf( __( 'From: %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'From: %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    // if ( $price !== $saleprice && $product->is_on_sale() ) {
    //     $price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
    //}

    ?>
    <style>
        div.woocommerce-variation-price,
        div.woocommerce-variation-availability,
        div.hidden-variable-price {
            height: 0px !important;
            overflow:hidden;
            position:relative;
            line-height: 0px !important;
            font-size: 0% !important;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        $('input.variation_id').change( function(){
            //Correct bug, I put 0
            if( 0 != $('input.variation_id').val()){
                $('p.price').html($('div.woocommerce-variation-price > span.price').html());
                console.log($('input.variation_id').val());
            } else {
                $('p.price').html($('div.hidden-variable-price').html());
                if($('p.availability'))
                    $('p.availability').remove();
                console.log('NULL');
            }
        });
    });
    </script>
    <?php

    echo '<p class="price">'.$price.'</p>
    <div class="hidden-variable-price" >'.$price.'</div>';

endif;
}

        }
    }
}


//En caso de no diponer stock de una de las variaciones, la desactivamos
add_filter( 'woocommerce_variation_is_active', 'desactivar_variaciones_sin_stock', 10, 2 );
function desactivar_variaciones_sin_stock( $is_active, $variation ) {
    if ( ! $variation->is_in_stock() ) return false;
    return $is_active;
}

///////////////////// END  - Simple Solutions - Only display Variation Price ////////////////////

// Adding Custom Sidebar //
 add_action( 'widgets_init', 'my_register_sidebars' );
function my_register_sidebars() {
    /* Register the 'primary' sidebar. */
    register_sidebar(
        array(
            'id'            => 'filter',
            'name'          => __( 'Filter Sidebar' ),
            'description'   => __( 'A short description of the sidebar.' ),
//             'before_widget' => '<div id="%1$s" class="widget %2$s">',
//             'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
//END - Adding custom Sidebar //

//Adding the checkbox //
function cw_custom_checkbox_fields( $checkout ) {
//     echo '<div class="cw_custom_class"><h3>'.__('Give Sepration Heading: ').'</h3>';
    woocommerce_form_field( 'custom_checkbox', array(
        'type'          => 'checkbox',
        'label'         => __('This is a gift'),
        'required'  => false,
    ), $checkout->get_value( 'custom_checkbox' ));
    echo '</div>';
}
add_action('woocommerce_before_order_notes', 'cw_custom_checkbox_fields');

add_action('woocommerce_checkout_update_order_meta', 'cw_checkout_order_meta');
function cw_checkout_order_meta( $order_id ) {
    if ($_POST['custom_checkbox']) update_post_meta( $order_id, 'This is a gift', esc_attr($_POST['custom_checkbox']));
}
//END - Adding the checkbox //
// Changing Order Notes Title and placeholder //
function md_custom_woocommerce_checkout_fields( $fields ) 
{
    $fields['order']['order_comments']['label'] = 'Order Notes / Gift Message';
    $fields['order']['order_comments']['placeholder'] = 'Enter your order notes here. If this is a gift, please enter your gift message here.';

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'md_custom_woocommerce_checkout_fields' );

// irene edit: filter fix
// add_action( 'woocommerce_before_shop_loop_item', 'wk_out_of_stock_variations_loop' );
function wk_out_of_stock_variations_loop(){
    global $product;
// 	$a_filter = $_GET['filter_size'];
	$a_filter = $_GET['filters'];

// 	echo "MARKER 1";
	
	if($a_filter){
		
// 	echo "APPLYING FILTER!";
// 	die('dead');
		
	if ( $product->is_type( 'variable' ) ) { // if variation product is out of stock
        $available = $product->get_available_variations();
		
// 		echo "AVAILABLE VARIATIONS";
// 		echo json_encode($available);
// 		die('dead');
// 		
// 		echo "<br/>PRODUCT<br/>";
// 		echo $product->get_title();echo "<br/>";
		
		$show_product = false;
        if ( $available )foreach ( $available as $instockvar ) {
            if ( isset($instockvar['attributes']['attribute_pa_size'] ) ) {
				
// 				echo "<br/>";
// 				echo json_encode($instockvar['attributes']['attribute_pa_size']);
// 				echo "<br/>";
// 				die('dead');
			  
// 				if(isset($_GET['filter_size'])){
				if(isset($_GET['filters'])){

// 					$destostock = $_GET['filter_size'];
					$destostock = $_GET['filters'];
					
// 					echo "FILTERS 1: ";
// 					echo $destostock;
// 					echo "<br/>substring: ";

					$destostock = substr($destostock,5);
				
					$destostock = substr($destostock, 0, -1);
					
			
					$array = explode('+', $destostock);
					
// 					echo "<br/>FILTERS 2: ";
// 					echo json_encode($array);
// 					echo "<br/>";
// 					die('dead');
					
					$var_size = $instockvar['attributes']['attribute_pa_size'];
					$size_str = strtoupper($var_size);
					
// 					echo "<br/>SIZE_STR: ";
// 					echo $size_str;
// 					echo "<br/>";
// 					die('dead');
// 					
					
					$input = array_flip($array); 
  
					// Step 2: change case of new keys to upper 
					$input = array_change_key_case($input, CASE_UPPER); 

					// Step 3: reverse the flip process to  
					// regain strings as value 
					$input = array_flip($input); 

					// print array after conversion of string 
// 					print"\nArray after string conversion:\n"; 
// 					print_r($input);
// 					die('dead');
// 					
					$res = in_array($size_str, $input );
					
// 					echo "<br/>";
// 					echo "IS IT IN THE FILTER?";
// 					echo "<br/>";
// 					var_dump($res);
// 					echo "<br/>";
// 					die('dead');

					// if (in_array($instockvar['attributes']['attribute_pa_size'], $array )&& (!$instockvar['max_qty']>0)){
					if (!in_array($size_str, $input )){
// 						echo "GONNA HIDEEE!!!!";
						
// 						global $product;
// 						$id = $product->get_id();
// 						echo "<style>";
// // 						echo ".post-$id{color: red }";
// 						echo ".post-$id{display: none }";
// 						echo "</style>";
						
						
					}
					else{
					
// 						if (in_array($size_str, $input ) && ($instockvar['max_qty']>0)){
						if (in_array($size_str, $input )){
							global $product;
							$id = $product->get_id();
							$show_product = true;
// 							echo "<style>.post-$id{display: flex !important}</style>";

// 							echo "<style>";
// 							echo ".post-$id{color: green }";
// 	// 						echo ".post-$id{display: none }";
// 							echo "</style>";
							// echo "WOOOO";
							// die('dead');
						}
					}
					
				}
    		}	
 
		}
		
		if(!$show_product) {
			global $product;
			$id = $product->get_id();
// 			echo "<script>";
// 			echo "let x = document.getElementByClass('post-" . $id . ")";
// 			echo "x.remove()";
// 			echo "</script>";
			echo "<style>";
			echo ".post-$id{display: none }";
			echo "</style>";
		}
	}	
	}
    
}











// Irene: add to cart button on category page product listing

add_action( 'woocommerce_loop_add_to_cart_link', 'wk_addtocart' );
function wk_addtocart(){
    global $product;
	global $variations ;
	if ( $product ) { // if variation product is out of stock
		$prod_id = $product->get_ID();
		$prod_sku = $product->get_sku();
		
		if ($product->product_type == 'variable'){
			$children = $product->get_children();
			foreach($children as $key=>$value){
				$product_variatons = new WC_Product_Variation($value);
				if ( $product_variatons->exists() && $product_variatons->variation_is_visible() && ($product_variatons->get_stock_quantity() > 0)) {
					$var_id = $product_variatons->get_variation_id();
					$var_attr[$value] = $product_variatons->get_variation_attributes();
					$var_size = $var_attr[$value]["attribute_pa_size"];
				
// 		WORKS BUT COMPLETES REDIRECT TO ADDTOCART url instead of using ajax
				echo '<a href="?add-to-cart='.$prod_id.'&variation_id='.$var_id.'&quantity=1&attribute_pa_size='.$var_size.'" class="button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow">'.$var_size.'</a>';


				}
			}

		}		

	}
}


function cm_redirect_users_by_role() {


    if ( is_product_category() or is_product()){

	?>
    <script>

			const queryString = window.location.search;
			console.log(queryString);
			const urlParams = new URLSearchParams(queryString);
			const addToCart = urlParams.get('add-to-cart')
			if(null !== addToCart){
				console.log('Removing Add to cart');
				var url = location.protocol + '//' + location.host + location.pathname;
				window.location.replace(url);
					
			}

	</script>
<?php				
    }
} 
add_action( 'wp', 'cm_redirect_users_by_role' );






// Irene: removing additional info tab
add_filter( 'woocommerce_product_tabs', 'wpb_remove_product_tabs', 99 );
function wpb_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );  // Remove the additional information tab
    return $tabs;
}

// Irene: renaming Description tab
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'More Details' );		// Rename the description tab
	return $tabs;

}


// Irene: adding tabs for product page
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {
	
	// Adds the new tab
	$tabs['return'] = array(
		'title' 	=> __( "Don't Love It. Return It", 'woocommerce' ),
		'priority' 	=> 60,
		'callback' 	=> 'woo_return_tab_content'
	);
	return $tabs;

}



function woo_return_tab_content() {

	// The new tab content

	echo "<h2 onclick='toggle(this);' data-type='love'>Don't Love It. Return It<i class='fa fa-angle-down'></i><img class='green_lines' src='/wp-content/uploads/2021/09/Noomie_Green.png'></h2>";
	echo '<p style="display:none;">At Baby Noomie we strive to provide our customers with items that fit their size and style. If you feel that your purchase does not fit either, please do not hesitate to return it. You may receive a full refund (minus $7 for shipping costs), within 30 days starting on the day of purchase on unworn/unwashed merchandise that comes in its original packaging. All exchanges are free of shipment costs if exchanged within 30 days of purchase. No refunds or exchanges will be made on sale merchandise. During sale events, no price adjustments will be made.</p>';
	
}



add_action( 'woocommerce_single_product_summary', 'noomie_display_badge_if_checkbox', 6 );
  
function noomie_display_badge_if_checkbox() {
    global $product;     
    if ( $product->is_on_sale() ) {
        echo '
<div class="woocommerce-message sale-message">Final Sale!</div>
 
';
    }
}


// END - Changing Order Notes Title and placeholder //





if (!function_exists('noomie_rpgc_checkout_form')) {
	/**
	 * Output the Giftcard form for the checkout.
	 * @access public
	 * @subpackage Checkout
	 * @return void
	 */
	function noomie_rpgc_checkout_form()
	{
		write_log("CHECKOUT GIFT CARD STUFF!");

		if (get_option('woocommerce_enable_giftcard_checkoutpage') == 'yes') {

			do_action('wpr_before_checkout_form');

			$info_message = apply_filters('woocommerce_checkout_giftcard_message', __('Have a giftcard?', 'kodiak-giftcards') . ' <a href="#" class="showgiftcard">' . __('Click here to enter your code', 'kodiak-giftcards') . '</a>');
			wc_print_notice($info_message, 'notice');
		?>

			<form class="checkout_giftcard" method="post" style="display:none">
				<p class="form-row form-row-first"><input type="text" name="giftcard_code" class="input-text" placeholder="<?php _e('Gift card', 'kodiak-giftcards'); ?>" id="giftcard_code" value="" /></p>
				<p class="form-row form-row-last"><input type="submit" class="button shopifu-btn-sm" name="apply_giftcard" value="<?php _e('Apply Gift Card', 'kodiak-giftcards'); ?>" /></p>
				<div class="clear"></div>
			</form>

			<?php do_action('wpr_after_checkout_form'); ?>

		<?php
		}
	}
	add_action('woocommerce_before_checkout_form_x', 'noomie_rpgc_checkout_form', 10);
}

add_action('wp_head', function () {
  global $wp_scripts;

  foreach ($wp_scripts->queue as $handle) {
    $script = $wp_scripts->registered[$handle];

    //-- If version is set, append to end of source.
    $source = $script->src . ($script->ver ? "?ver={$script->ver}" : "");

    //-- Spit out the tag.
    echo "<link rel='preload' href='{$source}'  as='script'/>\n";
  }
}, 1);



?>
