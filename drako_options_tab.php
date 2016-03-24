<?php
/*
    Plugin Name: Drakotek Add Custom Woo Options Tab 
    Description: Adds a custom options tab on the product edit screen in Woocommerce.
    Version: 1.0
    Author: Kadon Hodson
*/

class drako_instaurls
{

    public function __construct()
    {
        add_action( 'woocommerce_product_write_panel_tabs', array( &$this, 'create_admin_tab' ) );
		
    }

    /* this creates the tab in the products section in the admin panel */
    public function create_admin_tab()
    {
        ?>
        <li class="drako_instaurls_tab"><a href="#drako_instaurls_data"><?php _e('InstaURLs', 'woocommerce'); ?></a></li>
        <?
    }
	
	public function get_instaurls() {
		global $post;
		
		$url_array = array();
		$attr_array = array();
		$my_array = array();
		$_site_url = get_site_url() . '/checkout/?';
		$thisID = $post->ID;
		$_pf = new WC_Product_Factory();
		$product = $_pf->get_product($thisID);
		if (($product->get_type()) == 'variable') {
			$child_array = $product->get_children();
			foreach ($child_array as $_child) {
				$prodid = $_child;
				$prod = $_pf->get_product($prodid);
				$cart_url = $prod->add_to_cart_url();
				$arr1 = explode('?',$cart_url,2);
				$arr2 = explode('&',$arr1[1],3);
				if ($product->is_purchasable()) {
					$_url = $_site_url . $arr2[2];
				} else {
					$_url = 'Not Purchasable';
				}
				$att1 = explode('&attribute_',$_url);
				$att_count = count($att1);
				$my_attr = array();
				for ($x = 1; $x <= ($att_count-1); $x++) {
					$my_attr[] = $att1[$x];
				}
				$my_array[] = array(
								'URL' => $_url,
								'ATTR' => $my_attr
								);
				//$url_array[] = $_site_url . $_url;
				//$attr_array[] = $my_attr;
			}
		} else {
			$cart_url = $product->add_to_cart_url();
			$arr1 = explode('?',$cart_url,2);
			$arr2 = explode('&',$arr1[1],3);
			if ($product->is_purchasable()) {
				$_url = $_site_url . $arr2[2];
			} else {
				$_url = 'Not Purchasable';
			}
			$thetitle = $product->get_title();
			$attr_array[] = $thetitle;
			$my_array[] = array(
								'URL' => $_url,
								'ATTR' => $attr_array
								);
		}
			
		
		return $my_array;
		
	}
	
	public function instaurls_options() { 
		global $post;
		
		$instaurls_options = array(
        'title' => get_post_meta($post->ID, 'drako_instaurls_title', true),
        'content' => get_post_meta($post->ID, 'drako_instaurls_content', true),
    	);
		$urls = array();
		$urls = drako_instaurls::get_instaurls();
		
	?>

		<div id="drako_instaurls_data" class="panel woocommerce_options_panel">
        <div class="wrap instaurls">
		<h2>Woo Products Checkout URLs</h2>
    	<table>        
        	<tr valign="top">
        		<th>Attributes</th>
        		<th>Checkout Link</th>
            </tr>
            
            
            <?
            foreach ($urls as $item) : 
			if ($item['URL'] == 'Not Purchasable') {
				$font_color = 'color:#f00;';
			} else {
				$font_color = 'color:default;';
			}?>
				<tr style=" <? echo $font_color; ?> ">
                	<td>
                    	<?php foreach ($item['ATTR'] as $prod_attr) : 
							echo $prod_attr . '<br />';
                    	endforeach; ?>
           			</td>
           			<td style="white-space:nowrap;"> <? echo $item['URL']; ?></td>
            	</tr>
            <? endforeach; ?>
            <?php /*
			$_site_url = get_site_url();
			$list = array();
			$list = get_woocommerce_product_list();
			foreach ($list as $prod) : 
				$font_color = $prod[8];?>
				<tr style=" <?php echo $font_color; ?> ">
                <td style="white-space:nowrap;"> <?php echo $prod[2]; ?> </td>
                <td style="white-space:nowrap;"> <?php echo $prod[3]; ?> </td>
                <?php if ($prod[6] == 0) : ?>
                	<td style="white-space:nowrap;"> <?php echo $prod[0]; ?> </td>
                <?php else : ?>
					<td style="white-space:nowrap;color:#00f;">
                        <?php foreach ($prod[7] as $prod_attr) : 
							echo $prod_attr . '<br />';
                    	endforeach; ?>
                	</td>
                <?php endif; ?>
                <td style="white-space: nowrap; width: 100%;"> <?php echo $_site_url . '/checkout/?' . $prod[4]; ?> </td>
                
                </tr>
			
			<?php endforeach; */?> 
        </table>
	</div>
    </div>

<?php
}
	
} // end class

$drako_instaurls = new drako_instaurls();
add_action( 'woocommerce_product_write_panels', array( 'drako_instaurls', 'instaurls_options' ) );


