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
        setTimeout(updatePreview, 10);
      },
    });

    const updateBackgroundColor = () => {
      $('input[name="tqbb01-background-color"]').val(
        $('#tqbb01-background-color').val()
      );
    };

    updateBackgroundColor();

    const updatePreview = () => {
      const content = tinymce.activeEditor.getContent();
      const customInlineCss = $('#tqbb01-custom-inline-css').val();
      const backgroundColor = $('#tqbb01-background-color').val();

      $('#tqbb01-banner-preview').html(content);
      $('#tqbb01-banner-preview').attr('style', customInlineCss);
      $('#tqbb01-banner-preview').css('background-color', backgroundColor);
      $('#tqbb01-banner-preview').css('padding', '0.5em');
    };

    $('#tqbb01-custom-inline-css').on('keyup', updatePreview);

    tinyMCE.activeEditor.on('change', updatePreview);
    tinyMCE.activeEditor.on('keyup', updatePreview);

    updatePreview();
  });
})(jQuery);
