function extra_field() {
  echo '

  <form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" >
      <table class="variations custom_vr" cellspacing="0" border="1">
      <tbody>

          <tr > 
           <td>Other Charges :</td> <td>Price ($)</td> <td>Add To Cart</td>
          </tr>

          <tr>

          <td class="label"><label for="color">State filing fees (as applicable)</label></td>
    
          <td class="value">
              <input type="text" name="extracost" value="" placeholder= "Price ($)" />

          </td>
 <td class="label"><label for="color">Shipping & Handling Charges (as applicable)</label></td>
<td class="value">
              <input type="text" name="extracostone" value="" placeholder= "Price ($)" />

          </td>
	   <td>
		 <input type="submit" value="Add" />
           </td>
      </tr>                             
      </tbody>
  </table></form>';

}

add_action( 'woocommerce_after_cart_contents', 'extra_field' );

function add_custom_price_front($p, $obj) {
$post_id = $obj->post->ID;
$pro_price_extra_info = get_post_meta($post_id, 'pro_price_extra_info', true);
	if (is_admin()) {

  			//show in new line

		$tag = 'div';
	} else {

		$tag = 'span';
}

	if (!empty($pro_price_extra_info)) {

		$additional_price= "<$tag style='font-size:80%' class='pro_price_extra_info'> $pro_price_extra_info</$tag>";

	}

	if( !empty($additional_price)	 )

		return  $p . $additional_price ;
	else
		return  $p ;
}
add_filter('woocommerce_get_price_html', 'add_custom_price_front', 10, 2);

add_action('woocommerce_cart_calculate_fees','woocommerce_custom_surcharge' );

function register_session(){
    if( !session_id() )
        session_start();
 if(!isset($_SESSION['var']))  
{
$_SESSION['var'] = 0 ;
$_SESSION['var2'] = 0 ;
}

 if($_POST['extracost']  &&  $_POST['extracostone']  )
	  {
		$var = $_POST['extracost'] ; 
                $var2 = $_POST['extracostone'] ;

    $_SESSION['var'] = $var ;
   $_SESSION['var2'] = $var2 ;
	   }
	  	   
}
add_action('init','register_session');

function woocommerce_custom_surcharge() {

    global $woocommerce;

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )

    return;

    $surcharge =  $_SESSION['var'] + $_SESSION['var2'] ; 

    $woocommerce->cart->add_fee( 'Extra Charge', $surcharge, true, '' );

}
