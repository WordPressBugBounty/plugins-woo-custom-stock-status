<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* WC Stock Status General Setting Tab functions
*/

class Woo_Stock_General extends Woo_Stock_Base {
    
    public function __construct() {
    	add_action( 'woocommerce_settings_tabs_wc_stock_list_rename',array( $this ,'general_settings_tab' ));
    	add_action( 'woocommerce_update_options_wc_stock_list_rename',array( $this ,'update_settings' ));
    }

    public function general_settings_tab(){
    	global $current_section;

    	if ( $current_section == '' ) {
    		woocommerce_admin_fields( $this->general_settings() );
    	}
    }

    /**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses $this->get_settings()
	 */
	public function update_settings() {
		woocommerce_update_options( $this->general_settings() );
	}

    public function general_settings() {
		global $current_section;

		$settings = array();

		if (is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')) {

			$settings['section_polylang'] = array(
					'name'     => __( 'Notification', 'woo-custom-stock-status' ),
					'type'     => 'title',
					'desc'     => '<div style="background: #e4efff;border: #bdd9fe solid 4px;font-size: 17px;padding: 23px;">You are using the Polylang plugin, which requires you to add stock statuses for each language. Please navigate to the <a href="'.admin_url('admin.php?page=mlang_strings&s&group=woo-custom-stock-status&paged=1').'" style="font-weight: bold;">Polylang Translation page</a> to configure global custom stock status settings. For more details visit <a href="https://softound.com/managing-custom-stock-statuses-with-polylang-plugin/" target="_blank" style="font-weight: bold;">our official page</a></div>',
					'id'       => 'wc_wc_stock_polylang_notification'
				);
		}
		
		$settings['section_title'] = array(
				'name'     => __( 'Custom Stock Status', 'woo-custom-stock-status' ),
				'type'     => 'title',
				'desc'     => '<div style="background: #e4efff;border: #bdd9fe solid 4px;font-size: 17px;padding: 23px;"><a href="https://softound.com/products/woo-custom-stock-status-pro/" target="_blank" style="font-weight: bold;">Get Woo Custom Stock Status Pro</a> to edit stock status using <strong>bulk edit option</strong> with <strong>WPML</strong> compatibility and custom stock status for <strong>Product Categories</strong>.</div>',
				'id'       => 'wc_wc_stock_list_rename_section_title'
			);

		if( is_plugin_active( 'woo-custom-stock-status-pro/woo-custom-stock-status-pro.php' ) ){
			$settings['section_title']['desc'] = '';
		}

		 if ( $current_section == '' ) {
			/**
			 * Option for show matched stock status in category page
			 * @since 1.3.2
			 */
			$settings['show_fastest_stock_status_category_page'] =  array(
					'name' => __( 'Category page stock status', 'woo-custom-stock-status' ),
					'type' => 'text',
					'desc'     => 'Given text will be checked with variation product stock status. If variation product custom stock status contains this text then that custom status will be shown in category page',
					'id'   => 'wc_slr_show_fastest_stock_status_category_page',
					'class' => 'large-text'
				);

			

			/**
			 * Option to move the stock status below add to cart button
			 */
			$settings['stock_status_after_addtocart'] = array( 
													'name'		=>	__( 'Move stock status below add to cart button in single product page' , 'woo-custom-stock-status' ),
													'id'		=>	'wc_slr_stock_status_after_addtocart',
													'type'		=>	'checkbox',
													'default'	=>	'no',
													'desc_tip'	=> false,
													'autoload'	=> false
												);

			/**
			 * Option to move the stock status below add to cart button
			 */
			$settings['stock_status_before_price'] = array( 
													'name'		=>	__( 'Move stock status before price on shop / archive page' , 'woo-custom-stock-status' ),
													'id'		=>	'wc_slr_stock_status_before_price',
													'type'		=>	'checkbox',
													'default'	=>	'no',
													'desc_tip'	=> false,
													'autoload'	=> false
												);


			/**
			 * Option for show/hide sad face for out of stock status
			 */
			$settings['hide_sad_face'] = array( 
													'name'		=>	__( 'Hide sad face in out of stock' , 'woo-custom-stock-status' ),
													'id'		=>	'wc_slr_hide_sad_face',
													'type'		=>	'checkbox',
													'default'	=>	'no',
													'desc_tip'	=> false,
													'autoload'	=> false
												);
			

			/**
			 * Option for show/hide stock status in shop page
			 * @since 1.1.1
			 */
			$settings['show_in_shop_page'] = array( 
													'name'		=>	__( 'Show Stock Status in Shop Page' , 'woo-custom-stock-status' ),
													'id'		=>	'wc_slr_show_in_shop_page',
													'type'		=>	'checkbox',
													'default'	=>	'yes',
													'desc_tip'	=> false,
													'autoload'	=> false
												);


			/**
			 * Option for show/hide stock status in Cart page
			 * @since 1.3.5
			 */
			$settings['show_in_cart_page'] = array( 
													'name'		=>	__( 'Show Stock Status in Cart Page' , 'woo-custom-stock-status' ),
													'id'		=>	'wc_slr_show_in_cart_page',
													'type'		=>	'checkbox',
													'default'	=>	'yes',
													'desc_tip'	=> false,
													'autoload'	=> false
												);



			/**
			 * Option for show/hide stock status in order email
			 * @since 1.2.5
			 */
			$settings['show_in_order_email'] = array( 
													'name'		=>	__( 'Show Stock Status in Order Email' , 'woo-custom-stock-status' ),
													'id'		=>	'wc_slr_show_in_order_email',
													'type'		=>	'checkbox',
													'default'	=>	'no',
													'desc_tip'	=> '<i>'.__( 'Note: The custom stock status message in email may be differ based on availability of product at the time of order, it is not always same as customers see in shop page ' , 'woo-custom-stock-status' ).'</i>',
													'autoload'	=> false
												);
			/**
			 * Option for show/hide "Stock Status" tag before custom stock status text in Order Email
			 * @since 1.4.4
			 */
			$settings['show_stock_status_tag_in_email'] = array( 
				'name'		=>	__( 'Show Stock Status tag in Order Email' , 'woo-custom-stock-status' ),
				'id'		=>	'wc_slr_show_stock_status_tag_in_email',
				'type'		=>	'checkbox',
				'default'	=>	'yes',
				'desc_tip'	=> '<i>'.__( 'Note: Show "Stock Status" tag before custom stock status text in Order Email. Example: "Stock Status: In stock" ' , 'woo-custom-stock-status' ).'</i>',
				'autoload'	=> false
			);


			/**
			 * Option for show/hide stock status in wordpress blocks
			 * @since 1.3.2
			 */
			$settings['show_in_wordpress_blocks'] = array( 
				'name'		=>	__( 'Show Stock Status in Wordpress Blocks' , 'woo-custom-stock-status' ),
				'id'		=>	'wc_slr_show_in_wordpress_blocks',
				'type'		=>	'checkbox',
				'default'	=>	'no',
				'desc_tip'	=> '<i>'.__( 'Note: Show the stock status in "New In", "Fan Favorites", "On Sale", and "Best Sellers" blocks ' , 'woo-custom-stock-status' ).'</i>',
				'autoload'	=> false
			);

			/**
			 * Option for show/hide stock status in woocommerce invoice
			 */
			$settings['hide_in_woocommerce_invoice'] = array( 
				'name'		=>	__( 'Hide Stock Status in Woocommerce Invoice' , 'woo-custom-stock-status' ),
				'id'		=>	'wc_slr_hide_in_woocommerce_invoice',
				'type'		=>	'checkbox',
				'default'	=>	'no',
				'desc_tip'	=> '<i>'.__( 'Note: Hide the stock status in the WooCommerce invoice PDF. This feature is supported only by the <strong><a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/" target="_blank">PDF Invoices & Packing Slips for WooCommerce</a></strong> plugin' , 'woo-custom-stock-status' ).'</i>',
				'autoload'	=> false
			);

			/**
			 * Option to disable yoast seo compatibility
			 */
			$settings['disable_yoast_compatibility'] = array( 
				'name'		=>	__( 'Disable yoast seo compatibility' , 'woo-custom-stock-status' ),
				'id'		=>	'wc_slr_disable_yoast_compatibility',
				'type'		=>	'checkbox',
				'default'	=>	'no',
				'desc_tip'	=> '<i>'.__( 'Note: When enabled, the custom stock status will not be compatible with the Yoast SEO plugin.' , 'woo-custom-stock-status' ).'</i>',
				'autoload'	=> false
			);

			/**
			 * Option to Show / Hide instock status with backorder status on backordered product
			 * @since 1.5.6
			 */
			$settings['show_instockstatus_on_backordered_product'] = array( 
				'name'		=>	__( 'Show instock status for backordered product' , 'woo-custom-stock-status' ),
				'id'		=>	'wc_slr_show_instock_backordered',
				'type'		=>	'checkbox',
				'default'	=>	'no',
				'desc_tip'	=> '<i>'.__( 'Note: When enabled, the custom in-stock status and backorder status will be displayed on the order received page and in the order email for backordered products. Eg. 2 Instock (You can backorder additional quantities.)' , 'woo-custom-stock-status' ).'</i>',
				'autoload'	=> false
			);

			$settings['section_end'] = array(
				'type' => 'sectionend',
				'id' => 'wc_wc_stock_list_rename_section_end'
		   );

		}

		return apply_filters( 'wc_wc_stock_list_rename_settings', $settings );
	}

}