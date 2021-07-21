(($) => {
    $(document).on('ready', function () {
        $('#font-color').wpColorPicker({
            palettes: true,
            border: false,
            width: 200,
            clear: function () {
                $('#font-color').wpColorPicker('color', '#ffffff');
            }
        });

        $('#background-color').wpColorPicker({
            palettes: true,
            border: false,
            width: 200,
            clear: function () {
                $('#background-color').wpColorPicker('color', '#007bff');
            }
        });

        $('#post').on('submit', function (event) {
            if (!$('#background-color').hasClass('iris-error')) {
                const fontColor = $('#font-color').val().replace('#', '');
                const backgroundColor = $('#background-color').val().replace('#', '');

                $(this).append('<input type="hidden" name="font-color" value="' + fontColor + '" />');
                $(this).append('<input type="hidden" name="background-color" value="' + backgroundColor + '" />');
            }
        });
    });
})(jQuery);