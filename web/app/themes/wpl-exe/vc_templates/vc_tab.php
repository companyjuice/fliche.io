<?php
/** @var $this WPBakeryShortCode_VC_Tab */
$output = $title = $tab_id = '';

$tab_icon = ''; 

extract(shortcode_atts($this->predefined_atts, $atts));

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', $this->settings['base'], $atts );

$output .= "\n\t\t\t" . '<div class="'.esc_attr( $css_class ).'">';
$output .= ($content=='' || $content==' ') ? __("Empty tab. Edit page to add content here.", "wproto") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
$output .= "\n\t\t\t" . '</div> ';

echo $output;