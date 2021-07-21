(($) => {
    $(document).on('ready', function () {
        $('#background-color').wpColorPicker({
            palettes: true,
            border: false,
            width: 200,
            clear: function () {
                $('#background-color').wpColorPicker('color', '#81d742');
            }
        });

        $('#post').on('submit', function (event) {
            if (!$('#background-color').hasClass('iris-error')) {
                const backgroundColor = $('#background-color').val().replace('#', '');

                $(this).append('<input type="hidden" name="background-color" value="' + backgroundColor + '" />');
            }
        });
    });
})(jQuery);