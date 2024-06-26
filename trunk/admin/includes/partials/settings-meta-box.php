<?php

declare(strict_types=1);

echo <<<HTML
<div id="{$plugin_prefix}-color-picker-container">
	<label for="{$plugin_prefix}-background-color">Background Color</label>
	<input role="button" id="{$plugin_prefix}-background-color" class="{$plugin_prefix}-color-picker" type="text" value="{$background_color}" />
</div>
<br />
<div id="{$plugin_prefix}-custom-inline-css-container">
	<label for="{$plugin_prefix}-custom-inline-css">Custom Inline CSS</label>
	<br />
	<textarea rows="5" id="{$plugin_prefix}-custom-inline-css" name="{$plugin_prefix}-custom-inline-css" form="post">{$custom_inline_css}</textarea>
</div>
HTML;
