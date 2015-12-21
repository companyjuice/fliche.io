<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this WPBakeryShortCode_VC_Tta_Accordion|WPBakeryShortCode_VC_Tta_Tabs|WPBakeryShortCode_VC_Tta_Tour
 */
 

$custom_style_css = ' style_1';

if( isset( $atts['wproto_style'] ) ) {
	$custom_style_css = ' ' . esc_attr( $atts['wproto_style'] );
}

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

$this->resetVariables( $atts, $content );
$this->setGlobalTtaInfo();

$this->enqueueTtaScript();

if( $this->getShortcode() == 'vc_tta_tour' ) {
	$this->atts['tab_position'] = 'left';
	$this->atts['autoplay'] = '';
}
if( $this->getShortcode() == 'vc_tta_tabs' ) {
	$this->atts['tab_position'] = 'top';
	$this->atts['autoplay'] = '';
}


// It is required to be before tabs-list-top/left/bottom/right for tabs/tours
$prepareContent = $this->getTemplateVariable( 'content' );

$output = '<div ' . $this->getWrapperAttributes() . '>';
$output .= '<div class="' . esc_attr( $this->getTtaGeneralClasses() . $custom_style_css ) . '">';
$output .= $this->getTemplateVariable( 'tabs-list-top' );
$output .= $this->getTemplateVariable( 'tabs-list-left' );
$output .= '<div class="vc_tta-panels-container">';
$output .= '<div class="vc_tta-panels">';
$output .= $prepareContent;
$output .= '</div>';
$output .= '</div>';
$output .= $this->getTemplateVariable( 'tabs-list-bottom' );
$output .= $this->getTemplateVariable( 'tabs-list-right' );
$output .= '</div>';
$output .= '</div>';

echo $output;