( ( $ ) => {
	const initializeEditor = function () {
		setTimeout(
			() => {
				tinyMCE
					.activeEditor
					.dom
					.setStyle(
						tinymce.activeEditor.dom.select('body'),
						'background-color',
						$('#tqbb01-background-color').val()
					);
			},
			100
		);
	};

	$(document).on('ready', function () {
		$('#publish').append('<input type="hidden" name="tqbb01-background-color" value="" />');

		$('#tqbb01-background-color').wpColorPicker({
			palettes: true,
			border: false,
			width: 200,
			defaultColor: '#007bff',
			change: function (event) {
				initializeEditor();
			}
		});

		initializeEditor();
	});
} )( jQuery );
