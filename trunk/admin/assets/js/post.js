($ => {
  $(document).on('ready', function () {
    $('#publish').append(
      '<input type="hidden" name="tqbb01-background-color" />'
    );

    $('#tqbb01-background-color').wpColorPicker({
      palettes: true,
      border: false,
      width: 200,
      defaultColor: '#00c9c2',
      change: function (event) {
        updateBackgroundColor();
      },
    });

    const updateBackgroundColor = () => {
      const backgroundColor = $('#tqbb01-background-color').val();

      $('input[name="tqbb01-background-color"]').val(backgroundColor);

      tinyMCE.activeEditor.dom.setStyle(
        tinymce.activeEditor.dom.select('body'),
        'background-color',
        backgroundColor
      );
    };

    setInterval(updateBackgroundColor, 100);
  });
})(jQuery);
