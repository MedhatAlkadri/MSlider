<?php

//admin related code

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

// add CSS to Admin
function mslider_admin_scripts() {
    wp_enqueue_style('mslider', plugin_dir_url(__FILE__) . 'css/mslider.css');
}
add_action('admin_enqueue_scripts', 'mslider_admin_scripts');