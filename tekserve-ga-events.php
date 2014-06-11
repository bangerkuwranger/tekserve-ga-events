<?php
/**
 * Plugin Name: Tekserve Google Analytics Events
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Allows you to set certain Google Analytics events to be triggered by a selector.
 * Version: 1.0
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
}

//create custom fields for gaevent
add_action( 'admin_init', 'tekserve_ga_event_custom_fields' );
function tekserve_ga_event_custom_fields() {
    add_meta_box( 'tekserve_ga_event_meta_box', 'Google Analytics Event', 'display_tekserve_ga_event_meta_box', 'gaevent', 'normal', 'high' );
}

// Retrieve current details based on gaevent ID
function display_tekserve_ga_event_meta_box( $gaevent ) {
    $tekserve_gaevent_selector = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_selector', true ) );
	$tekserve_gaevent_category = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_category', true ) );
	$tekserve_gaevent_action = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_action', true ) );
	$tekserve_gaevent_label = esc_html( get_post_meta( $gaevent->ID, 'tekserve_gaevent_label', true ) );
	$tekserve_gaevent_value = intval( get_post_meta( $gaevent->ID, 'tekserve_gaevent_value', true ) );
	?>
    <table>
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
}

//store custom field data
add_action( 'save_post', 'save_ga_event_custom_fields', 5, 2 );
function save_ga_event_custom_fields( $gaevent_id, $gaevent ) {
    // Check post type for 'tekserve_testimonial'
    if ( $gaevent->post_type == 'gaevent' ) {
        // Store data in post meta table if present in post data
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
    }
}

//set title to quote+name+organization+id
function tekserve_ga_event_set_title ($post_id, $post_content) {
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
        $title = 'Click on "' . $_POST['tekserve_gaevent_selector'] . '" triggers event ' . $_POST['tekserve_gaevent_category'] . ' - ' . $_POST['tekserve_gaevent_action'];
        $where = array( 'ID' => $post_id );
        $wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
    }
}
add_action('save_post', 'tekserve_ga_event_set_title', 15, 2 );

add_action( 'wp_enqueue_scripts', 'tekserve_ga_event_script' );
function tekserve_ga_event_script() {
	wp_register_script( 'ga_events', plugins_url().'/tekserve-ga-events/js/ga-events.js' );
	$args = array(
		'post_type'        => 'gaevent',
		'post_status'      => 'publish'
	);
	$ga_events = get_posts($args);
	$ga_events_array = array();
	foreach( $ga_events as $key=>$ga_event ) {
		$ga_events_array[$key] = array(
			'selector'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_selector', true ) ),
			'category'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_category', true ) ),
			'action'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_action', true ) ),
			'label'	=> esc_html( get_post_meta( $ga_event->ID, 'tekserve_gaevent_label', true ) ),
			'value'	=> intval( get_post_meta( $ga_event->ID, 'tekserve_gaevent_value', true ) ),
		);
	}
	wp_localize_script( 'ga_events', 'gaevents', $ga_events_array );
	wp_enqueue_script( 'ga_events', '', '', '', true );
}