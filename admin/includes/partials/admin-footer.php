<?php declare( strict_types = 1 );

$message = <<<HTML
<span>
	<a href="https://better-banners.briandady.com" target="_BLANK">
		Better Banners
	</a> was created with love by
	<a href="https://briandady.com" target="_BLANK">
		Brian Dady
	</a>.
	&#129505;
</span>

<hr />
HTML;

echo apply_filters('render_admin_footer_message', $message);