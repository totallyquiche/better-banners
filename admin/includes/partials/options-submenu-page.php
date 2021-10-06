<?php declare( strict_types =1 );

echo <<<HTML
<div id="{$plugin_prefix}-options-page-container" style="font-family: 'Maven Pro', sans-serif !important; margin: 1.5% !important;">
    <h1>Options</h1>
    <form method="post" action="{$_SERVER['REQUEST_URI']}" id="{$plugin_prefix}-options-form">
        <hr />
        <br />
        <label for="{$checkbox_name}" style="vertical-align: top !important; line-height: 1.2em !important;"><b>Display banners using JavaScript</b></label>
        <input type="checkbox" name="{$checkbox_name}" style="vertical-align: middle !important;" {$checked} />
        <br /><br />
        <span>
            <i>
                Some plugins or themes may prevent Better Banners from displaying properly.
                If your banners are not showing up, try toggling this option.
            </i>
        </span>
        <br /><br />
        <hr />
        <br />
        <label for="{$textarea_name}"><b>Custom Inline CSS</b></label>
        <br />
        <textarea cols="40" rows="5" id="{$plugin_prefix}-custom-inline-css-all-banners" name="{$textarea_name}" form="{$plugin_prefix}-options-form">{$custom_inline_css_all_banners}</textarea>
        <br /><br />
        <label for="{$plugin_prefix}-custom-inline-css-all-banners-example"><b>Example</b></label>
        <br />
        <textarea cols="40" rows="5" id="{$plugin_prefix}-custom-inline-css-all-banners-example" disabled="disabled">border: 1px solid black;\nfont-style: italic;\nfont-weight: 700;</textarea>
        <br /><br />
        <span>
            <i>
                CSS declarations entered above will be applied to all banners.
            </i>
        </span>
        <br /><br />
        <hr />
        <br />
        <button type="submit" name="{$submit_button_name}" />Save</button>
    </form>
</div>
HTML;