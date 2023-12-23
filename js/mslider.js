// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', (event) => {
    // Loop through each slider
    for (var slider_id in mslider_params) {
        // Get the parameters for this slider
        var params = mslider_params[slider_id];

        // Check if the slider element exists
        var sliderElement = document.querySelector('#slider-' + slider_id);
        if (!sliderElement) {
            continue;
        }

        // Initialize the slider with these parameters
        var swiper = new Swiper('#slider-' + slider_id, {
            direction: 'horizontal',
            effect: params.effect,
            speed: parseInt(params.speed),
            autoplay: {
                delay: parseInt(params.delay),
            },
        });
    }
});