( ( $ ) => {
	$(document).on('ready', function () {
		$('#publish').append('<input type="hidden" name="tqbb01-background-color" />');

		$('#tqbb01-background-color').wpColorPicker({
			palettes: true,
			border: false,
			width: 200,
			defaultColor: '#007bff',
			change: function (event) {
				setTimeout(initializeEditor, 10);
			}
		});

		const initializeEditor = function () {
			if (
				typeof tinyMCE.activeEditor.dom !== 'undefined' &&
				$('#tqbb01-background-color').val()
			) {
				tinyMCE
					.activeEditor
					.dom
					.setStyle(
						tinymce.activeEditor.dom.select('body'),
						'background-color',
						$('#tqbb01-background-color').val()
					);

				$('input[name="tqbb01-background-color"]').val($('#tqbb01-background-color').val());

				clearInterval(initializer);
			}
		};

		const initializer = setInterval(initializeEditor, 100);
	});
} )( jQuery );
