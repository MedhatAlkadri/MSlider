<?php
/*
Plugin Name: MSlider
Description: A simple image slider plugin
Version: 1.0
Author: Medhat Alkadri
Author URI: https://medhatalkadry.com
*/

function my_slider_post_type() {
    register_post_type('my_slider',
        array(
            'labels'      => array(
                'name'          => __('Sliders', 'textdomain'),
                'singular_name' => __('Slider', 'textdomain'),
            ),
                'public'      => true,
                'has_archive' => false,
        )
    );
}
add_action('init', 'my_slider_post_type');

function my_slide_post_type() {
    register_post_type('my_slide',
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
add_action('init', 'my_slide_post_type');


function my_slide_meta_box() {
    add_meta_box('my_slide_meta', 'Slider ID', 'my_slide_meta_callback', 'my_slide', 'side', 'default');
}
add_action('add_meta_boxes', 'my_slide_meta_box');

function my_slide_meta_callback($post) {
    $slider_id = get_post_meta($post->ID, '_my_slide_slider_id', true);
    echo '<input type="number" name="my_slide_slider_id" value="' . esc_attr($slider_id) . '">';
}

// Add a meta box for the slide image width when editing a slider post
function mslider_add_meta_boxes() {
    add_meta_box(
        'mslider_image_width', // ID
        'Slide Image Width', // Title
        'mslider_image_width_meta_box_callback', // Callback
        'my_slider', // Post type
        'side', // Context
        'default' // Priority
    );
}
add_action('add_meta_boxes', 'mslider_add_meta_boxes');

// Output the HTML for the slide image width meta box
function mslider_image_width_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'mslider_nonce');
    $slide_image_width = get_post_meta($post->ID, '_mslider_image_width', true);
    echo '<input type="number" id="mslider_image_width" name="mslider_image_width" value="' . esc_attr($slide_image_width) . '" />';
}

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
}
add_action('save_post', 'mslider_save_postdata');

function my_slider_shortcode($atts) {
    $slider_id = $atts['id'];
    $slide_image_width = get_post_meta($slider_id, '_mslider_image_width', true);
    $args = array(
        'post_type' => 'my_slide',
        'meta_query' => array(
            array(
                'key' => '_my_slide_slider_id',
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
        echo '<div class="swiper-container">';
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
add_shortcode('my_slider', 'my_slider_shortcode');

function my_slider_scripts() {
    // Enqueue the swiperjs
    wp_enqueue_style('swiper-style', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper-script', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), false, true);

    wp_enqueue_script(
        'my-slider', // Unique handle for your script
        plugin_dir_url(__FILE__) . '/js/mslider.js', // Assuming the script is in the root of your theme directory
        array('jquery', 'swiper-script'), // Dependencies, in this case, jQuery and swiper-script
        '1.0', // Version number
        true // Load in footer
    );

    // Enqueue the CSS file
    wp_enqueue_style(
        'my-slider-css', // Unique handle for your stylesheet
        plugin_dir_url(__FILE__) . 'css/mslider.css', // Assuming the stylesheet is in a 'css' directory
        array(), // Dependencies, in this case, none
        '1.0' // Version number
    );
}
add_action('wp_enqueue_scripts', 'my_slider_scripts');