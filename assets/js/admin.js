( ( $ ) => {
	const updateEditorBackgroundColor = function () {
		tinyMCE
			.activeEditor
			.dom
			.addStyle('body { background-color:' + $('#tqbb01-background-color').val() +
				' !important; }')

		tinyMCE
			.activeEditor
			.dom
			.addStyle('p { margin: 0 !important; }');
	}

	$(document).on('ready', function () {
		$('#tqbb01-background-color').wpColorPicker({
			palettes: true,
			border: false,
			width: 200,
			clear: function () {
				$('#tqbb01-background-color').wpColorPicker('color', '#007bff');
			},
			change: function (event) {
				updateEditorBackgroundColor();
			}
		});

		$('#post').on('submit', function (event) {
			if (! $('#tqbb01-background-color').hasClass('iris-error')) {
				$(this).append('<input type="hidden" name="tqbb01-background-color" value="' +
					$('#tqbb01-background-color').val() + '" />');
			}
		});

		const initializeEditor = function () {
			if (typeof tinyMCE !== 'undefined') {
				updateEditorBackgroundColor();
			}
		};

		setInterval(initializeEditor, 0)

		clearInterval(initializeEditor);
	});
} )( jQuery );
