document.addEventListener('DOMContentLoaded', (event) => {
    var swiper = new Swiper('.swiper-container', {
        // Optional parameters
        effect: mslider_params.effect, // Use the effect from the mslider_params object
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: 2500,
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