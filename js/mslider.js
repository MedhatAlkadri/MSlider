document.addEventListener('DOMContentLoaded', (event) => {
    // Loop through each slider
    for (var slider_id in mslider_params) {
        // Get the parameters for this slider
        var params = mslider_params[slider_id];
        console.log(mslider_params);
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