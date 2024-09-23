<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* WC Stock Status Setting Tab functions
*/

class Woo_Stock_Status extends Woo_Stock_Base {
    
    public function __construct() {
        add_action( 'woocommerce_settings_tabs_wc_stock_list_rename',array( $this ,'stock_status_settings_tab' ));
        add_action( 'woocommerce_update_options_wc_stock_list_rename',array( $this ,'update_settings' ));
    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses $this->get_settings()
     */
    public function update_settings() {
        woocommerce_update_options( $this->stock_status_settings() );
    }

    public function stock_status_settings_tab(){
        global $current_section;

        if ( $current_section == 'stock_status' ) {
            echo '<h2>' . __( 'Custom Stock Status', 'woo-custom-stock-status' ) . '</h2>';

            if(!is_plugin_active( 'woo-custom-stock-status-pro/woo-custom-stock-status-pro.php' )){
                echo '<div style="background: #e4efff;border: #bdd9fe solid 4px;font-size: 17px;padding: 23px;"><a href="https://softound.com/products/woo-custom-stock-status-pro/" target="_blank" style="font-weight: bold;">Get Woo Custom Stock Status Pro</a> to edit stock status using <strong>bulk edit option</strong> with <strong>WPML</strong> compatibility and custom stock status for <strong>Product Categories</strong>.</div>';
                echo '<br>';
            }   

            if (is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')) {
                echo '<div style="background: #e4efff;border: #bdd9fe solid 4px;font-size: 17px;padding: 23px;">You are using the Polylang plugin, which requires you to add stock statuses for each language. Please navigate to the <a href="'.admin_url('admin.php?page=mlang_strings&s&group=woo-custom-stock-status&paged=1').'" style="font-weight: bold;">Polylang Translation page</a> to configure global custom stock status settings. For more details visit <a href="https://softound.com/managing-custom-stock-statuses-with-polylang-plugin/" target="_blank" style="font-weight: bold;">our official page</a></div>';
                echo '<br>';
            }

            echo '<table class="widefat striped form-table woo-custom-stock-status">';
            echo '<thead>
                    <tr>
                        <th scope="row" style="width:20%">' . __( 'Default Stock Status', 'woo-custom-stock-status' ) . '</th>
                        <th scope="row" style="width:42%">' . __( 'Custom Stock Status', 'woo-custom-stock-status' ) . '</th>
                        <th scope="row" style="width:13%">' . __( 'Color', 'woo-custom-stock-status' ) . '</th>
                        <th scope="row" style="width:8%">' . __( 'Font Size', 'woo-custom-stock-status' ) . '</th>
                        <th scope="row" style="width:18%">' . __( 'Show on Cart & Checkout', 'woo-custom-stock-status' ) . '</th>
                    </tr>
                  </thead>';
            echo '<tbody>';
            $status_array = $this->status_array;
            $status_array['grouped_product_stock_status'] = 'Grouped product stock status for category page';
            foreach ($status_array as $status => $label) {
                $text_field_id = 'wc_slr_' . $status;
                $cart_checkout_field = 'wc_slr_show_'.$status.'_in_cart_checkout';
                if($status == 'grouped_product_stock_status'){
                    $text_field_id = 'wc_slr_'.$status.'_for_category_page';
                }
                $color_field_id = 'wc_slr_' . $status . '_color';
                $font_size_field_id = 'wc_slr_' . $status . '_font_size';

                $color_default = isset($this->status_color_array[$status.'_color']['default']) ? $this->status_color_array[$status.'_color']['default'] : '';
                $font_size_default = isset($this->status_font_size_array[$status.'_font_size']['default']) ? $this->status_font_size_array[$status.'_font_size']['default'] : '';
                $cart_checkout_default = get_option($cart_checkout_field,'yes');
                $checked = '';
                if($cart_checkout_default == 'yes'){
                    $checked = 'checked="checked"';
                }

                echo '<tr>';
                echo '<td>' . __( $label, 'woo-custom-stock-status' ) . '</td>';
                echo '<td class="forminp forminp-text"><input type="text" name="' . $text_field_id . '" id="' . $text_field_id . '" value="' . get_option($text_field_id) . '" class="large-text stock-text" /></td>';
                echo '<td class="forminp forminp-color"><span class="colorpickpreview" style="background-color: '. get_option($color_field_id, $color_default) .';">&nbsp;</span>
                    <input type="text" name="' . $color_field_id . '" id="' . $color_field_id . '" value="' . get_option($color_field_id, $color_default) . '" class="colorpick" style="width:6em;" /></td>';
                echo '<td class="forminp forminp-number" style="padding-bottom: 16px!important;"><input type="number" name="' . $font_size_field_id . '" id="' . $font_size_field_id . '" value="' . get_option($font_size_field_id, $font_size_default) . '" style="width:4em;" /> &nbsp;px</td>';
                echo '<td class="forminp forminp-checkbox">';
                if($status != 'grouped_product_stock_status'){
                    echo '<input name="'.$cart_checkout_field.'" id="'.$cart_checkout_field.'" type="checkbox" class="" value="1" '.$checked.'>';
                }
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        }
    }

    public function stock_status_settings() {
        global $current_section;

        $settings = array();

        if ( $current_section == 'stock_status' ) {

            foreach($this->status_array as $status=>$label){
                $settings[$status] =  array(
                    'name' => __( $label, 'woo-custom-stock-status' ),
                    'type' => 'text',
                    'desc'     => '',
                    'id'   => 'wc_slr_'.$status,
                    'class' => 'large-text'
                );
            }

            /**
             * Option for showing grouped product stock status in category page
             */
            $settings['grouped_product_stock_status_for_category_page'] =  array(
                    'name' => __( 'Grouped product stock status for category page', 'woo-custom-stock-status' ),
                    'type' => 'text',
                    'desc'     => '',
                    'id'   => 'wc_slr_grouped_product_stock_status_for_category_page',
                    'class' => 'large-text'
                );

            foreach($this->status_color_array as $status => $options ){
                $settings[$status] =  array(
                    'name'      => __( $options['label'], 'woo-custom-stock-status' ),
                    'desc'      => '',
                    'id'        => 'wc_slr_'.$status,
                    'type'      => 'color',
                    'css'       => 'width:6em;',
                    'default'   => $options['default'],
                    'autoload'  => false,
                    'desc_tip'  => true
                );
            }

            foreach($this->status_font_size_array as $status => $options ){
                $settings[$status] =  array(
                    'name'      => __( $options['label'], 'woo-custom-stock-status' ),
                    'desc'      => '',
                    'suffix'    => ' px',
                    'id'        => 'wc_slr_'.$status,
                    'type'      => 'number',
                    'css'       => 'width:6em;',
                    'default'   => $options['default'],
                    'autoload'  => false,
                    'desc_tip'  => true
                );
            }

            /**
             * Option to hide/show stock status in cart and checkout page
             * @since 1.5.3
             */
            foreach($this->status_array as $status=>$label){
                $settings['show_'.$status.'in_cart_checkout'] =  array(
                    'name' => __( 'Show '.$label.' in Cart & Checkout Page', 'woo-custom-stock-status' ),
                    'type' => 'checkbox',
                    'id'   => 'wc_slr_show_'.$status.'_in_cart_checkout',
                    'default'   =>  'yes',
                    'desc_tip'  => false,
                    'autoload'  => false
                );
            }

            $settings['section_end'] = array(
                'type' => 'sectionend',
                'id' => 'wc_wc_stock_list_rename_section_end'
           );
        }

        return apply_filters( 'wc_wc_stock_status_settings', $settings );
    }

}
