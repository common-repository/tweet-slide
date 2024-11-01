jQuery(document).ready(function() {
    jQuery('body').on('click', '.tweetslide_upload', function(e) {
        e.preventDefault();

        var custom_uploader = wp.media({
            title: 'Custom Image',
            button: {
                text: 'Upload Image'
            },
            multiple: false  // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('.tweetslide_url').val(attachment.url);

        })
        .open();
    });
});
