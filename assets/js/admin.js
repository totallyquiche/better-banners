( ( $ ) => {
	$(document).on('ready', function () {
		$('#publish').append('<input type="hidden" name="tqbb01-background-color" />');

		$('#tqbb01-background-color').wpColorPicker({
			palettes: true,
			border: false,
			width: 200,
			defaultColor: '#00c9c2',
			change: function (event) {
				setTimeout(initializeEditor, 10);
			}
		});

		const initializeEditor = function () {
			const backgroundColor = $('#tqbb01-background-color').val();

			if (
				typeof tinyMCE.activeEditor.dom !== 'undefined' &&
				backgroundColor
			) {
				tinyMCE
					.activeEditor
					.dom
					.setStyle(
						tinymce.activeEditor.dom.select('body'),
						'background-color',
						backgroundColor
					);

				$('input[name="tqbb01-background-color"]').val(backgroundColor);

				clearInterval(initializer);
			}
		};

		const initializer = setInterval(initializeEditor, 100);
	});
} )( jQuery );
