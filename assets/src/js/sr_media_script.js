jQuery(document).ready(function ($) {
    add_path('gold-upload-btn', 'sr_gold_image_option', $);
    add_path('silver-upload-btn', 'sr_silver_image_option', $);
});
//upload image media query
function add_path(button_id, input_id, $) {
    $('#' + button_id).click(function (e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Select Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
            .on('select', function (e) {
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                console.log(uploaded_image);
                var image_url = uploaded_image.toJSON().url;
                // Let's assign the url value to the input field
                $('#' + input_id).val(image_url);
            });
    });
}



