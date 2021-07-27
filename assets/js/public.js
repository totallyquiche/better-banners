( ( $ ) => {
	$(document).on('ready', function () {
		if ( localizations.displayBannersUsingJavaScript ) {
			$('body'). prepend(localizations.bannersHtml);
		}
	});
} )( jQuery );
