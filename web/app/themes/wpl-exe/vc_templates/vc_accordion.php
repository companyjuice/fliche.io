<?php
wp_enqueue_script('jquery-ui-accordion');
$output = $accordion_style = $el_class = '';
$accordion_style = 'style_1';
//
extract(shortcode_atts(array(
    'el_class' => '',
		'accordion_style' => 'style_1',
), $atts));

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . $el_class . ' ', $this->settings['base'], $atts );

// Extract tab titles
preg_match_all( '/vc_accordion_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
$acc_titles = array();
if ( isset( $matches[1] ) ) {
	$acc_titles = $matches[1];
}

$_wpl_wproto_acc_uniqid = uniqid();

$acc_nav = '';
$acc_nav .= '<ul class="resp-tabs-list wproto-accordion-' . $_wpl_wproto_acc_uniqid . '">';
foreach ( $acc_titles as $acc ) {
	$acc_atts = shortcode_parse_atts($acc[0]);
	if(isset($acc_atts['title'])) {
		$acc_nav .= '<li><a href="javascript:;">' . $acc_atts['title'] . '</a></li>';
	}
}
$acc_nav .= '</ul>' . "\n";

$output .= '<div id="wproto-accordion-' . esc_attr( $_wpl_wproto_acc_uniqid ) . '" class="wproto-native-accordion style-' . esc_attr( $accordion_style ) . '" data-type="default">';
$output .= $acc_nav;
$output .= "\n\t" . '<div class="' . esc_attr( $css_class ) . ' resp-tabs-container wproto-accordion-' . esc_attr( $_wpl_wproto_acc_uniqid ) . '">';
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div> ';
$output .= "\n\t".'</div> ';

echo $output;