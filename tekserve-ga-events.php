<?php
/**
 * Plugin Name: Tekserve Google Analytics Events
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Allows you to set certain Google Analytics events to be triggered by a selector.
 * Version: 1.2
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 */
/*
The MIT License (MIT)
Copyright (c) 2014 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/


//create custom post type
add_action( 'init', 'create_post_type_ga_event' );
function create_post_type_ga_event() {

	register_post_type( 'gaevent',
		array(
			'labels' => array(
				'name' => __( 'GA Events' ),
				'singular_name' => __( 'GA Event' ),
				'add_new' => 'Add New',
            	'add_new_item' => 'Add New GA Event',
            	'edit' => 'Edit',
            	'edit_item' => 'Edit GA Event',
            	'new_item' => 'New GA Event',
            	'view' => 'View',
            	'view_item' => 'View GA Event',
            	'search_items' => 'Search GA Events',
            	'not_found' => 'No GA Events found',
            	'not_found_in_trash' => 'No GA Events found in Trash',
            	'parent' => 'Parent GA Events',
			),
			'public' => true,
			'has_archive' => false,
            'supports' => array( '' ),
		)
	);

}	//end create_post_type_ga_event()




//create custom fields for gaevent
add_action( 'admin_init', 'tekserve_ga_event_custom_fields' );
function tekserve_ga_event_custom_fields() {

    add_meta_box( 'tekserve_ga_event_meta_box', 'Google Analytics Event', 'display_tekserve_ga_event_meta_box', 'gaevent', 'normal', 'high' );

}	//end tekserve_ga_event_custom_fields()




// Retrieve current details based on gaevent ID
function display_tekserve_ga_event_meta_box( $gaevent ) {

	wp_nonce_field( 'tekserve_ga_event_meta_box', 'tekserve_gaevent_nonce' );
	$tekserve_gaevent_handler = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_handler', true ) );
    $tekserve_gaevent_selector = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_selector', true ) );
	$tekserve_gaevent_category = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_category', true ) );
	$tekserve_gaevent_action = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_action', true ) );
	$tekserve_gaevent_label = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_label', true ) );
	$tekserve_gaevent_value = intval( get_post_meta( $gaevent->ID, 'tekserve_gaevent_value', true ) );
	?>
    <table>
    	<tr>
            <td style="width: 100%"><h2>Event Trigger Type</h2><p>Select whether event will be triggered by clicking object, submitting a form, or moving the cursor over an object</p>
            <select name="tekserve_gaevent_handler">
				<option value="click" <?php selected( $tekserve_gaevent_handler, 'click' ); ?>>Click</option>
				<option value="submit" <?php selected( $tekserve_gaevent_handler, 'submit' ); ?>>Submit</option>
				<option value="mouseover" <?php selected( $tekserve_gaevent_handler, 'mouseover' ); ?>>Mouse Over</option>
			</select></td>
            <td>&nbsp;</td>
		</tr>
        <tr>
            <td style="width: 100%"><h2>Selector<h2><p>Required. jQuery style selector for the element on the page that you want to trigger this event. No quotes needed. See <a href="http://api.jquery.com/category/selectors/" target="_blank">jQuery documentation</a> for more info.</p></td>
        </tr>
        <tr>
            <td><input type="text" size="30" name="tekserve_gaevent_selector" value="<?php echo $tekserve_gaevent_selector; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%"><h2>Event Category<h2><p>Required. Analytics event category. See <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/events" target="_blank">Analytics documentation</a> for more info on this and other event fields.</p></td>
        </tr>
        <tr>
            <td><input type="text" size="30" name="tekserve_gaevent_category" value="<?php echo $tekserve_gaevent_category; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%"><h2>Event Action<h2><p>Required. Analytics event action.</p></td>
        </tr>
        <tr>
            <td><input type="text" size="30" name="tekserve_gaevent_action" value="<?php echo $tekserve_gaevent_action; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%"><h2>Event Label<h2><p>Optional. Analytics event label.</p></td>
        </tr>
        <tr>
            <td><input type="text" size="30" name="tekserve_gaevent_label" value="<?php echo $tekserve_gaevent_label; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%"><h2>Event Value<h2><p>Optional. Analytics event value.</p></td>
        </tr>
        <tr>
            <td><input type="number" size="10" name="tekserve_gaevent_value" value="<?php echo $tekserve_gaevent_value; ?>" /></td>
        </tr>
    </table>
    <?php
 
}	//end display_tekserve_ga_event_meta_box( $gaevent )




//store custom field data
add_action( 'save_post', 'save_ga_event_custom_fields', 5, 2 );
function save_ga_event_custom_fields( $gaevent_id, $gaevent ) {

	//check nonce
	if( ! isset( $_POST['tekserve_gaevent_nonce'] ) ) {
	
    	return $gaevent_id;
    
    }	//end if( ! isset( $_POST['tekserve_gaevent_nonce'] ) )
    $nonce = $_POST['tekserve_gaevent_nonce'];
	if( ! wp_verify_nonce( $nonce, 'tekserve_ga_event_meta_box' ) ) {
	
	  return $gaevent_id;
	
	}	//end if( ! wp_verify_nonce( $nonce, 'tekserve_ga_event_meta_box' ) )
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	
	  return $gaevent_id;
    
    }	//end if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    // Check post type for 'gaevent'
    if( $gaevent->post_type == 'gaevent' ) {
    
        // Store data in post meta table if present in post data
        if ( isset( $_POST['tekserve_gaevent_handler'] ) ) {
            update_post_meta( $gaevent_id, 'tekserve_gaevent_handler', $_REQUEST['tekserve_gaevent_handler'] );
        }
        if ( isset( $_POST['tekserve_gaevent_selector'] ) && $_POST['tekserve_gaevent_selector'] != '' ) {
            update_post_meta( $gaevent_id, 'tekserve_gaevent_selector', sanitize_text_field( $_REQUEST['tekserve_gaevent_selector'] ) );
        }
        if ( isset( $_POST['tekserve_gaevent_category'] ) && $_POST['tekserve_gaevent_category'] != '' ) {
            update_post_meta( $gaevent_id, 'tekserve_gaevent_category', sanitize_text_field( $_REQUEST['tekserve_gaevent_category'] ) );
    	}
    	if ( isset( $_POST['tekserve_gaevent_action'] ) && $_POST['tekserve_gaevent_action'] != '' ) {
            update_post_meta( $gaevent_id, 'tekserve_gaevent_action', sanitize_text_field( $_REQUEST['tekserve_gaevent_action'] ) );
    	}
    	if ( isset( $_POST['tekserve_gaevent_label'] ) && $_POST['tekserve_gaevent_label'] != '' ) {
            update_post_meta( $gaevent_id, 'tekserve_gaevent_label', sanitize_text_field( $_REQUEST['tekserve_gaevent_label'] ) );
    	}
    	if ( isset( $_POST['tekserve_gaevent_value'] ) && $_POST['tekserve_gaevent_value'] != '' ) {
            update_post_meta( $gaevent_id, 'tekserve_gaevent_value', intval( $_REQUEST['tekserve_gaevent_value'] ) );
    	}
    
    }	//end if( $gaevent->post_type == 'gaevent' )

}	//end save_ga_event_custom_fields( $gaevent_id, $gaevent )




//set title to Selector + Handler + Category + Action + Label
add_action('save_post', 'tekserve_ga_event_set_title', 15, 2 );
function tekserve_ga_event_set_title ( $post_id, $post_content ) {

    if ( $post_id == null || empty($_POST) )
        return;

    if ( !isset( $_POST['post_type'] ) || $_POST['post_type']!='gaevent' )  
        return; 

    if ( wp_is_post_revision( $post_id ) )
        $post_id = wp_is_post_revision( $post_id );

    global $post;  
    if ( empty( $post ) )
        $post = get_post($post_id);

    if ( $_POST['tekserve_gaevent_selector']!='' && $_POST['tekserve_gaevent_category']!='' && $_POST['tekserve_gaevent_action']!='' ) {
        global $wpdb;
        $title = '';
        if( $_POST['tekserve_gaevent_handler'] == 'mouseover' ) {
        	$title .= 'When cursor is over "';
        }
        elseif( $_POST['tekserve_gaevent_handler'] == 'submit' ) {
        	$title .= 'When submitting the form "';
        }
        else {
        	$title .= 'After user clicks "';
        }
        $title .= $_POST['tekserve_gaevent_selector'] . '", trigger event [ ' . $_POST['tekserve_gaevent_category'] . ' - ' . $_POST['tekserve_gaevent_action'];
        if( $_POST['tekserve_gaevent_label'] ) {
        	$title .= ' - ' . $_POST['tekserve_gaevent_selector'];
        }
        $title .= ' ]';
        $where = array( 'ID' => $post_id );
        $wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
    }

}	//end tekserve_ga_event_set_title ( $post_id, $post_content )





add_action( 'wp_enqueue_scripts', 'tekserve_ga_event_script' );
function tekserve_ga_event_script() {
	wp_register_script( 'ga_events', plugins_url().'/tekserve-ga-events/js/ga-events.js' );
	$args = array(
		'post_type'        => 'gaevent',
		'post_status'      => 'publish',
		'numberposts'      => -1
	);
	$ga_events = get_posts($args);
	$ga_events_array = array();
	foreach( $ga_events as $key=>$ga_event ) {
		$ga_events_array[$key] = array(
			'selector'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_selector', true ) ),
			'handler'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_handler', true ) ),
			'category'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_category', true ) ),
			'action'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_action', true ) ),
			'label'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_label', true ) ),
			'value'	=> intval( get_post_meta( $ga_event->ID, 'tekserve_gaevent_value', true ) ),
		);
	}
	wp_localize_script( 'ga_events', 'gaevents', $ga_events_array );
	wp_enqueue_script( 'ga_events', '', '', '', true );
}
