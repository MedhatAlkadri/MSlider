# MSlider V2

MSlider is a simple, easy-to-use WordPress plugin that allows you to create and manage image sliders on your website.

## Requirements

- PHP 7.0 or higher

## Features

- **Custom Post Types**: MSlider introduces two new custom post types - 'mslider' and 'mslide'. The 'mslider' post type is used to create and manage different sliders, while the 'mslide' post type is used to create and manage the individual slides within each slider.
- **Support for Titles and Thumbnails**: The 'mslide' post type supports both titles and thumbnails, allowing you to give each slide a name and an image.
- **Meta Box for Slider ID**: MSlider adds a meta box to the 'mslide' post type edit screen, where you can enter the ID of the slider that the slide belongs to.
- **Slider Effects**: MSlider supports various transition effects for the slides. You can choose from fade, slide, cube, flip, and more to give your sliders a unique look and feel.

## Usage

1. **Create a Slider**: Go to the WordPress admin area, click on 'Sliders' in the left-hand menu, and then click on 'Add New'. Give your slider a title and then click 'Publish'.
2. **Get the Slider ID**: After publishing the slider, you can find the ID of the slider in the URL of the edit page. The URL will look something like this: `http://yourwebsite.com/wp-admin/post.php?post=123&action=edit`. In this case, '123' is the ID of the slider.
3. **Create Slides**: Click on 'Slides' in the left-hand menu, and then click on 'Add New'. Give your slide a title, set a featured image (this will be the image for the slide), and enter the ID of the slider you created in the 'Slider ID' meta box. Click 'Publish' to create the slide. Repeat this step for each slide you want to add to the slider.
4. **Add the Slider to a Page**: To add the slider to a page, you need to use the `[mslider id="x"]` shortcode, where 'x' is the ID of the slider. You can add this shortcode to the content of any page or post, or you can add it to a text widget in a widget area.

## Author

MSlider is developed by [Medhat Alkadri](https://medhatalkadry.com).