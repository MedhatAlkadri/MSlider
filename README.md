# MSlider

MSlider is a WordPress plugin that allows you to create beautiful, responsive sliders using the Swiper.js library.

## Features

- Create sliders with customizable settings in the slider post
- Customize the slider effect, speed, and delay
- Responsive design works on all devices

## Installation

1. Upload the `mslider` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the `[mslider]` shortcode in your posts or pages to display a slider

## Usage

To display a slider, use the `[mslider]` shortcode in your post or page. The settings for the slider (effect, speed, delay) can be customized in the slider post.

- `effect`: The transition effect. Can be `slide`, `fade`, `cube`, `coverflow`, or `flip`.
- `speed`: The transition speed in milliseconds.
- `delay`: The delay between transitions in milliseconds.

Example:

```php
[mslider]

