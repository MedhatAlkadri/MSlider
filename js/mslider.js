document.addEventListener('DOMContentLoaded', (event) => {
    var swiper = new Swiper('.swiper-container', {
        // Optional parameters
        effect: mslider_params.effect, // Use the effect from the mslider_params object
        // Use the speed and delay from the mslider_params object
        speed: mslider_params.speed,
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: mslider_params.delay,
            disableOnInteraction: false,
        },

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
    });
});