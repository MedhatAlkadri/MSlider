<?php
/*
Plugin Name: MSlider
Description: A simple image slider plugin
Version: 1.4
Author: Medhat Alkadri
Author URI: https://medhatalkadry.com
*/

function mslider_post_type() {
    register_post_type('mslider',
        array(
            'supports' => array('title'),
            'labels'      => array(
                'name'          => __('Sliders', 'textdomain'),
                'singular_name' => __('Slider', 'textdomain'),
            ),
                'public'      => true,
                'has_archive' => false,
        )
    );
}
add_action('init', 'mslider_post_type');

function mslide_post_type() {
    register_post_type('mslide',
        array(
            'supports' => array('title', 'thumbnail'),
            'labels'      => array(
                'name'          => __('Slides', 'textdomain'),
                'singular_name' => __('Slide', 'textdomain'),
            ),
                'public'      => true,
                'has_archive' => false,
        )
    );
}
add_action('init', 'mslide_post_type');


function mslide_meta_box() {
    add_meta_box('mslide_meta', 'Slider ID', 'mslide_meta_callback', 'mslide', 'side', 'default');
}
add_action('add_meta_boxes', 'mslide_meta_box');

function mslide_meta_callback($post) {
    wp_nonce_field(basename(__FILE__), 'mslide_nonce');
    $mslide_slider_id = get_post_meta($post->ID, '_mslide_slider_id', true);
    echo '<input type="text" id="mslide_slider_id" name="mslide_slider_id" value="' . esc_attr($mslide_slider_id) . '" />';

}


// Add meta boxes for the slide image width and slider effect when editing a slider post
function mslider_add_meta_boxes() {
    // Meta box for Slide Image Width
    add_meta_box(
        'mslider_image_width', // ID
        'Slide Image Width', // Title
        'mslider_image_width_meta_box_callback', // Callback
        'mslider', // Post type
        'side', // Context
        'default' // Priority
    );

    // Meta box for Slider Effect
    add_meta_box(
        'mslider_effect', // Unique ID
        'Slider Effect', // Box title
        'mslider_meta_box_html', // Content callback
        'mslider', // Post type
        'side', // Context
        'default' // Priority
    );
}
add_action('add_meta_boxes', 'mslider_add_meta_boxes');


//Display the Custom Field (Field slider effect)
function mslider_meta_box_html($post) {
    $effect_value = get_post_meta($post->ID, '_mslider_effect', true);
    $speed_value = get_post_meta($post->ID, '_mslider_speed', true);
    $delay_value = get_post_meta($post->ID, '_mslider_delay', true);
    $value = get_post_meta($post->ID, '_mslider_effect', true);

    echo '<div class= "mslider_effect_container">';
    echo '<label for="mslider_effect">Effect:</label>';
    echo '<select id="mslider_effect" name="mslider_effect">';
    echo '<option value="slide"' . selected($value, 'slide', false) . '>Slide</option>';
    echo '<option value="fade"' . selected($value, 'fade', false) . '>Fade</option>';
    echo '<option value="cube"' . selected($value, 'cube', false) . '>cube</option>';
    echo '<option value="coverflow"' . selected($value, 'coverflow', false) . '>coverflow</option>';
    echo '<option value="flip"' . selected($value, 'flip', false) . '>flip</option>';
    echo '</select>';
    echo '</div>';
    
    // New speed field
    echo '<div class= "mslider_effect_container">';
    echo '<label for="mslider_speed">Speed:</label>';
    echo '<input type="number" id="mslider_speed" name="mslider_speed" value="' . esc_attr($speed_value) . '">';
    echo '</div>';

    
    // New delay field
    echo '<div class= "mslider_effect_container">';
    echo '<label for="mslider_delay">Delay:</label>';
    echo '<input type="number" id="mslider_delay" name="mslider_delay" value="' . esc_attr($delay_value) . '">';
    echo '</div>';
}


// Output the HTML for the slide image width meta box
function mslider_image_width_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'mslider_nonce');
    $slide_image_width = get_post_meta($post->ID, '_mslider_image_width', true);
    echo '<input type="number" id="mslider_image_width" name="mslider_image_width" value="' . esc_attr($slide_image_width) . '" />';
}


//save slider id in slide post type
function mslide_save_postdata($post_id) {
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verify nonce
    if (!isset($_POST['mslide_nonce']) || !wp_verify_nonce($_POST['mslide_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Save slider id in slide post type
    if (array_key_exists('mslide_slider_id', $_POST)) {
        update_post_meta(
            $post_id,
            '_mslide_slider_id',
            $_POST['mslide_slider_id']
        );
    }
}
add_action('save_post', 'mslide_save_postdata');

// Save the slide image width when the post is saved
function mslider_save_postdata($post_id) {
    // Verify nonce
    if (!isset($_POST['mslider_nonce']) || !wp_verify_nonce($_POST['mslider_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // Check if our custom field is being saved
    if (isset($_POST['mslider_image_width'])) {
        update_post_meta(
            $post_id,
            '_mslider_image_width',
            $_POST['mslider_image_width']
        );
    }
//Save the Custom Field Value (Field slider effect)
if (array_key_exists('mslider_effect', $_POST)) {
    update_post_meta(
        $post_id,
        '_mslider_effect',
        $_POST['mslider_effect']
    );
}

// Save the slider speed
if (isset($_POST['mslider_speed'])) {
    update_post_meta(
        $post_id,
        '_mslider_speed',
        $_POST['mslider_speed']
    );
}

// Save the slider delay
if (isset($_POST['mslider_delay'])) {
    update_post_meta(
        $post_id,
        '_mslider_delay',
        $_POST['mslider_delay']
    );
}
}
add_action('save_post', 'mslider_save_postdata');

function mslider_enqueue_scripts() {
    global $post;
    
    // Array to store the slider IDs
    $slider_ids = array();
 
    if ($post && has_shortcode($post->post_content, 'mslider')) {
        // Extract the slider IDs from the shortcodes
        preg_match_all('/mslider id="(\d+(?:,\d+)*)"/', $post->post_content, $matches);
        
        // Loop through all matches and add all slider IDs to the array
        foreach ($matches[1] as $match) {
            $ids = explode(',', $match);
            $slider_ids = array_merge($slider_ids, $ids);
        }
    }

    // Check if $slider_ids is an array before the foreach loop
    if (is_array($slider_ids)) {
        // Loop through each slider ID
        foreach ($slider_ids as $slider_id) {
            // Query for all slides that have this slider ID in their meta box
            $args = array(
                'post_type' => 'slide', // Adjust this to match your slide post type
                'meta_query' => array(
                    array(
                        'key' => 'slider_id', // Adjust this to match your meta box key
                        'value' => $slider_id,
                        'compare' => 'LIKE', // This will search for the slider ID in the slide's meta box
                    ),
                ),
            );
            $slides = get_posts($args);

            // Loop through each slide
            foreach ($slides as $slide) {
                $slider_effect = get_post_meta($slide->ID, '_mslider_effect', true);
                $slider_speed = get_post_meta($slide->ID, '_mslider_speed', true);
                $slider_delay = get_post_meta($slide->ID, '_mslider_delay', true);
 
                 // Enqueue your script
    wp_enqueue_script('mslider-script', plugins_url('js/mslider.js', __FILE__), array('jquery'), '1.0', true);

    // Localize the script with your data
    $slider_params = array(
        'effect' => $slider_effect,
        'speed' => $slider_speed,
        'delay' => $slider_delay,
    );
    wp_localize_script('mslider-script', 'mslider_params', $slider_params);

            }
        }
    }
}
add_action('wp_enqueue_scripts', 'mslider_enqueue_scripts');


function mslider_shortcode($atts) {
   
   
        $atts = shortcode_atts(array(
            'id' => '',
        ), $atts);
        $slider_id = $atts['id'];
        $slider_effect = get_post_meta($slider_id, '_mslider_effect', true);
        $slider_speed = get_post_meta($slider_id, '_mslider_speed', true); // Retrieve speed
        $slider_delay = get_post_meta($slider_id, '_mslider_delay', true); // Retrieve delay
        $slide_image_width = get_post_meta($slider_id, '_mslider_image_width', true);
    
    $args = array(
        'post_type' => 'mslide',
        'meta_query' => array(
            array(
                'key' => '_mslide_slider_id',
                'value' => $slider_id,
                'compare' => '=',
            )
        )
    );

    $slides = new WP_Query($args);



    // Start output buffering
    ob_start();

    // Check if the query returns any posts
    if ($slides->have_posts()) {
        // Start a list
          // Output the slider container with the ID
    echo '<div id="slider-' . esc_attr($slider_id) . '" class="swiper-container">';;
        echo '<div class="swiper-wrapper">';

        // Loop through the posts
        while ($slides->have_posts()) {
            $slides->the_post();

            // Get the slide image URL
            $slide_image_url = get_the_post_thumbnail_url();

            // Display the slide image with the specified width and the post content
            echo '<div class="swiper-slide">';
            echo '<img style="width: ' . esc_attr($slide_image_width) . 'px;" src="' . esc_url($slide_image_url) . '" />';
            echo '<div class="slide-content">' . get_the_content() . '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }

    // Return the buffered output
    return ob_get_clean();
}
add_shortcode('mslider', 'mslider_shortcode');

function mslider_scripts() {
    // Check if the current post/page contains the mslider shortcode
    global $post;
    if ($post && has_shortcode($post->post_content, 'mslider')) {
        // Enqueue the swiperjs
        wp_enqueue_style('swiper-style', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        wp_enqueue_script('swiper-script', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), false, true);

        wp_enqueue_script(
            'mslider', // Unique handle for your script
            plugin_dir_url(__FILE__) . 'js/mslider.js', // Assuming the script is in the root of your theme directory
            array('jquery', 'swiper-script'), // Dependencies, in this case, jQuery and swiper-script
            '1.0', // Version number
            true // Load in footer
        );

        // Get the slider IDs from the shortcode
        preg_match_all('/mslider id="(\d+(?:,\d+)*)"/', $post->post_content, $matches);
        $slider_ids = array();
        foreach ($matches[1] as $match) {
            $ids = explode(',', $match);
            $slider_ids = array_merge($slider_ids, $ids);
        }

        // Initialize an array to hold all slider parameters
        $all_slider_params = array();

        // Loop through each slider ID
        foreach ($slider_ids as $slider_id) {
            $slider_effect = get_post_meta($slider_id, '_mslider_effect', true);
            $slider_speed = get_post_meta($slider_id, '_mslider_speed', true);
            $slider_delay = get_post_meta($slider_id, '_mslider_delay', true);

            // Add this slider's parameters to the array
            $all_slider_params[$slider_id] = array(
                'effect' => $slider_effect,
                'speed' => $slider_speed,
                'delay' => $slider_delay,
            );
        }

        // Localize the mslider_params object to your script
        wp_localize_script('mslider', 'mslider_params', $all_slider_params);

        // Enqueue the CSS file
        wp_enqueue_style(
            'mslider-css', // Unique handle for your stylesheet
            plugin_dir_url(__FILE__) . 'css/mslider.css', // Assuming the stylesheet is in a 'css' directory
            array(), // Dependencies, in this case, none
            '1.0' // Version number
        );
    }
}
add_action('wp_enqueue_scripts', 'mslider_scripts');
// add CSS to Admin
function mslider_admin_scripts() {
    wp_enqueue_style('mslider', plugin_dir_url(__FILE__) . 'css/mslider.css');
}
add_action('admin_enqueue_scripts', 'mslider_admin_scripts');



//admin slides list

// Modify the columns in the slides list table
function mslide_edit_columns($columns) {
    $columns['slider_id'] = 'Slider ID';
    return $columns;
}
add_filter('manage_mslide_posts_columns', 'mslide_edit_columns');

// Populate the custom columns
function mslide_custom_column($column_name, $post_id) {
    if ($column_name === 'slider_id') {
        // Get the slider ID
        $slider_id = get_post_meta($post_id, '_mslide_slider_id', true);

        // If the slide has a slider ID, display it
        if ($slider_id) {
            echo $slider_id;
        } else {
            echo 'No slider ID';
        }
    } elseif ($column_name === 'featured_image') {
        // Get the featured image
        $featured_image = get_the_post_thumbnail($post_id, 'thumbnail');

        // If the slide has a featured image, display it
        if ($featured_image) {
            echo $featured_image;
        } else {
            echo 'No featured image';
        }
    }
}
add_action('manage_mslide_posts_custom_column', 'mslide_custom_column', 10, 2);

// Add custom input field for slider ID in quick edit form
function mslide_quick_edit_custom_fields($column_name, $post_type) {
    if ($column_name !== 'slider_id' || $post_type !== 'mslide') {
        return;
    }
    ?>
    <fieldset class="inline-edit-col-left">
        <div class="inline-edit-col">
            <label>
                <span class="title">Slider ID</span>
                <span class="input-text-wrap">
                    <input type="text" name="mslide_slider_id" class="mslide-slider-id" value="" id="mslide-slider-id-input">
                </span>
            </label>
        </div>
    </fieldset>
    <script>
        // When the quick edit button is clicked
        jQuery(document).on('click', '.editinline', function() {
            // Get the post ID from the row that the button was clicked in
            var post_id = jQuery(this).closest('tr').attr('id').replace('post-', '');

            // Get the slider ID from the table cell
            var slider_id = jQuery('#post-' + post_id + ' .column-slider_id').text();

            // Set the placeholder of the input field to the current slider ID
            jQuery('#mslide-slider-id-input').attr('placeholder', slider_id);
        });
    </script>
    <?php
}
add_action('quick_edit_custom_box', 'mslide_quick_edit_custom_fields', 10, 2);

// Save the slider ID from quick edit
function mslide_save_quick_edit_data($post_id) {
    if (isset($_REQUEST['mslide_slider_id'])) {
        $slider_id = sanitize_text_field($_REQUEST['mslide_slider_id']);

        // Check if the entered value is a single, valid ID
        if (preg_match('/^\d+$/', $slider_id)) {
            update_post_meta($post_id, '_mslide_slider_id', $slider_id);
        } else {
            // If the entered value is not a valid ID, return early without updating the post meta
            return;
        }
    }
}
add_action('save_post_mslide', 'mslide_save_quick_edit_data');


//add images to admin slides list

// Add a new column for the featured image
function mslide_add_image_column($columns) {
    $columns['featured_image'] = 'Featured Image';
    return $columns;
}
add_filter('manage_mslide_posts_columns', 'mslide_add_image_column');

