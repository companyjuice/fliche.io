<?php
$output = $break = $tabs_style = $vc_tab_type_class = $vc_tab_type_attr = $is_tour = $el_class = '';
$tabs_style = 'style_1';
extract( shortcode_atts( array(
	'tabs_style' => 'style_1',
	'el_class' => '',
	'break' => ''
), $atts ) );

$el_class = $this->getExtraClass( $el_class );

// Extract tab titles
preg_match_all( '/vc_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();
/**
 * vc_tabs
 *
 */
if ( isset( $matches[1] ) ) {
	$tab_titles = $matches[1];
}

$_wpl_wproto_tabs_uniqid = uniqid();

$is_tour = $this->settings['base'] == 'vc_tour';

$tabs_nav = '';
$tabs_nav .= '<ul class="resp-tabs-list wproto-tabs-' . esc_attr( $_wpl_wproto_tabs_uniqid ) . '">';
foreach ( $tab_titles as $tab ) {
	$tab_atts = shortcode_parse_atts($tab[0]);
	if(isset($tab_atts['title'])) {
		$tab_icon = isset( $tab_atts['tab_icon'] ) && $tab_atts['tab_icon'] <> '' ? '<i class="' . esc_attr( $tab_atts['tab_icon'] ) . '"></i> ' : '';
		$tabs_nav .= '<li><a href="javascript:;">' . $tab_icon . $tab_atts['title'] . '</a></li>';
	}
}
$tabs_nav .= '</ul>' . "\n";

$vc_tab_type_class = $is_tour ? 'wproto-tour' : 'wproto-tabs-shortcode';
$vc_tab_type_attr = $is_tour ? 'vertical' : 'default';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class, $this->settings['base'], $atts );

$output = '';
$output .= '<div id="wproto-tabs-' . $_wpl_wproto_tabs_uniqid . '" class="wproto-tabs ' . esc_attr( $vc_tab_type_class ) . ' style-' . esc_attr( $tabs_style ) . '" data-type="' . esc_attr( $vc_tab_type_attr ) . '" data-break="' . esc_attr( $break ) . '">';
$output .= $tabs_nav;
$output .= "\n\t" . '<div class="' . esc_attr( $css_class ) . ' resp-tabs-container wproto-tabs-' . $_wpl_wproto_tabs_uniqid . '">';
$output .= "\n\t\t\t" . wpb_js_remove_wpautop( $content );
$output .= "\n\t" . '</div>';
$output .= '</div>';
echo $output;