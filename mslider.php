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

function my_slide_save_postdata($post_id) {
    if (array_key_exists('my_slide_slider_id', $_POST)) {
        update_post_meta(
            $post_id,
            '_my_slide_slider_id',
            $_POST['my_slide_slider_id']
        );
    }
}
add_action('save_post', 'my_slide_save_postdata');

function my_slider_shortcode($atts) {
    $slider_id = $atts['id'];
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

        // Display the post title, content, and featured image
        echo '<div class="swiper-slide">';
        echo '<div class="slide-content">' . get_the_content() . '</div>';
        if (has_post_thumbnail()) {
            echo '<div class="slide-image">' . get_the_post_thumbnail() . '</div>';
        }
        echo '</div>';
    }

    // End the list
    echo '</div>'; // End .swiper-wrapper
    echo '<div class="swiper-pagination"></div>';
    echo '<div class="swiper-button-next"></div>';
    echo '<div class="swiper-button-prev"></div>';
    echo '</div>'; // End .swiper-container
}

    // Reset post data
    wp_reset_postdata();

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