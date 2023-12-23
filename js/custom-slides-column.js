// JavaScript code to add a custom column to the slides listing table in the WordPress admin area

(function ($) {
    // Add custom column to the slides listing table
    function customSlidesColumn(columns) {
        var newColumns = {
            'slider_id': 'Slider ID'
        };
        return Object.assign({}, columns, newColumns);
    }

    // Display the slider ID in the custom column
    function customSlidesColumnContent(column, post_id) {
        if (column === 'slider_id') {
            var slider_id = $('.column-slider_id[data-id="' + post_id + '"]').data('slider-id');
            return slider_id;
        }
        return '';
    }

    // Make the custom column sortable
    function customSlidesColumnSortable(columns) {
        columns.slider_id = 'slider_id';
        return columns;
    }

    // Allow quick editing of the slider ID
    function customSlidesQuickEdit(column_name, post_type) {
        if (column_name !== 'slider_id') {
            return;
        }
        var template = '<div class="inline-edit-col">' +
            '<label>' +
            '<span class="title">Slider ID</span>' +
            '<input type="text" name="slider_id" class="slider-id" value="">' +
            '</label>' +
            '</div>';

        $('.inline-edit-col-right').append(template);
    }

    // Save the slider ID when quick editing
    function customSaveQuickEditData(post_id) {
        var $slider_id = $('.inline-edit-col .slider-id');
        var slider_id = $slider_id.val();
        if (slider_id !== '') {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'custom_save_quick_edit_data',
                    post_id: post_id,
                    slider_id: slider_id
                },
                success: function (response) {
                    // Handle success response if needed
                },
                error: function (xhr, status, error) {
                    // Handle error if needed
                }
            });
        }
    }

    $(document).ready(function () {
        // Add custom column
        var columns = document.getElementById('posts-filter').getElementsByTagName('thead')[0].getElementsByTagName('tr')[0];
        columns.appendChild(document.createElement('th')).textContent = 'Slider ID';

        // Add data attribute to each row
        var rows = document.getElementById('posts-filter').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        Array.prototype.forEach.call(rows, function (row) {
            var post_id = row.getAttribute('id').replace('post-', '');
            row.getElementsByClassName('column-slider_id')[0].setAttribute('data-id', post_id);
        });

        // Bind functions to appropriate hooks
        document.addEventListener('manage-slides-posts-columns', function (event) {
            event.detail.columns = customSlidesColumn(event.detail.columns);
        });
        document.addEventListener('manage-slides-posts-custom-column-content', function (event) {
            event.detail.content = customSlidesColumnContent(event.detail.column, event.detail.post_id);
        });
        document.addEventListener('manage-edit-slides-sortable-columns', function (event) {
            event.detail.columns = customSlidesColumnSortable(event.detail.columns);
        });
        document.addEventListener('quick-edit-custom-box', function (event) {
            customSlidesQuickEdit(event.detail.column_name, event.detail.post_type);
        });
        document.addEventListener('save-post-slides', function (event) {
            customSaveQuickEditData(event.detail.post_id);
        });
    });

})(jQuery);