(($) => {
    $(document).on('ready', function () {
        $('#background-color').wpColorPicker({
            hide: false,
            palettes: true,
            border: false,
            width: 300,
            clear: function () {
                $('#background-color').wpColorPicker('color', '#007bff');
            }
        });

        $('#post').on('submit', function (event) {
            event.preventDefault();

            if (!$('#background-color').hasClass('iris-error')) {
                const backgroundColor = $('#background-color').val().replace('#', '');

                $(this).append('<input type="hidden" name="background-color" value="' + backgroundColor + '" />');

                this.submit();
            }
        });
    });
})(jQuery);