<?php
/**
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

/********************** Backgrounds **********************/

	#Header_wrapper {
		background-color: <?php mfn_opts_show( 'background-header', '#000119' ) ?>;
	}
	#Subheader {
		<?php 
			$subheaderB = mfn_opts_get( 'background-subheader', '#F7F7F7' );
			$subheaderA = mfn_opts_get( 'subheader-transparent', 100 );
			$subheaderA = $subheaderA / 100;
			$subheaderA = str_replace( ',', '.', $subheaderA );
		?>
		background-color: <?php hex2rgba( $subheaderB, $subheaderA, true ); ?>;
	}
	.header-classic #Action_bar, .header-plain #Action_bar, .header-stack #Action_bar {
	    background-color: <?php mfn_opts_show( 'background-action-bar', '#2C2C2C' ) ?>;
	}
	
	#Sliding-top {
		background-color: <?php mfn_opts_show( 'background-sliding-top', '#545454' ) ?>;
	}
	#Sliding-top a.sliding-top-control {
		border-right-color: <?php mfn_opts_show( 'background-sliding-top', '#545454' ) ?>;
	}
	#Sliding-top.st-center a.sliding-top-control,
	#Sliding-top.st-left a.sliding-top-control {
		border-top-color: <?php mfn_opts_show( 'background-sliding-top', '#545454' ) ?>;
	}	
	
	#Footer {
		background-color: <?php mfn_opts_show( 'background-footer', '#545454' ) ?>;
	}

/************************ Colors ************************/

/* Content font */
	body, ul.timeline_items, .icon_box a .desc, .icon_box a:hover .desc, .feature_list ul li a, .list_item a, .list_item a:hover,
	.widget_recent_entries ul li a, .flat_box a, .flat_box a:hover, .story_box .desc, .content_slider.carousel  ul li a .title,
	.content_slider.flat.description ul li .desc, .content_slider.flat.description ul li a .desc {
		color: <?php mfn_opts_show( 'color-text', '#626262' ) ?>;
	}
	
/* Theme color */
	.themecolor, .opening_hours .opening_hours_wrapper li span, .fancy_heading_icon .icon_top,
	.fancy_heading_arrows .icon-right-dir, .fancy_heading_arrows .icon-left-dir, .fancy_heading_line .title,
	.button-love a.mfn-love, .format-link .post-title .icon-link, .pager-single > span, .pager-single a:hover,
	.widget_meta ul, .widget_pages ul, .widget_rss ul, .widget_mfn_recent_comments ul li:after, .widget_archive ul, 
	.widget_recent_comments ul li:after, .widget_nav_menu ul, .woocommerce ul.products li.product .price, .shop_slider .shop_slider_ul li .item_wrapper .price, 
	.woocommerce-page ul.products li.product .price, .widget_price_filter .price_label .from, .widget_price_filter .price_label .to,
	.woocommerce ul.product_list_widget li .quantity .amount, .woocommerce .product div.entry-summary .price, .woocommerce .star-rating span,
	#Error_404 .error_pic i, .style-simple #Filters .filters_wrapper ul li a:hover, .style-simple #Filters .filters_wrapper ul li.current-cat a,
	.style-simple .quick_fact .title {
		color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}
	
/* Theme background */
	.themebg, .pager .pages a:hover, .pager .pages a.active, .pager .pages span.page-numbers.current, .pager-single span:after, #comments .commentlist > li .reply a.comment-reply-link,
	.fixed-nav .arrow, #Filters .filters_wrapper ul li a:hover, #Filters .filters_wrapper ul li.current-cat a, .widget_categories ul, .Recent_posts ul li .desc:after, .Recent_posts ul li .photo .c,
	.widget_recent_entries ul li:after, .widget_mfn_menu ul li a:hover, .widget_mfn_menu ul li.current_page_item > a, .widget_product_categories ul, div.jp-interface, #Top_bar a#header_cart span, 
	.testimonials_slider .slider_images, .testimonials_slider .slider_images a:after, .testimonials_slider .slider_images:before,
	.slider_pagination a.selected, .slider_pagination a.selected:after, .tp-bullets.simplebullets.round .bullet.selected, .tp-bullets.simplebullets.round .bullet.selected:after,
	.tparrows.default, .tp-bullets.tp-thumbs .bullet.selected:after, .offer_thumb .slider_pagination a:before, .offer_thumb .slider_pagination a.selected:after,
	.style-simple .accordion .question:after, .style-simple .faq .question:after, .style-simple .icon_box .desc_wrapper h4:before,
	.style-simple #Filters .filters_wrapper ul li a:after, .style-simple .article_box .desc_wrapper p:after, .style-simple .sliding_box .desc_wrapper:after,
	.style-simple .trailer_box:hover .desc {
		background-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}
	
	.Latest_news ul li .photo, .style-simple .opening_hours .opening_hours_wrapper li label,
	.style-simple .timeline_items li:hover h3, .style-simple .timeline_items li:nth-child(even):hover h3, 
	.style-simple .timeline_items li:hover .desc, .style-simple .timeline_items li:nth-child(even):hover {
		border-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}
	
/* Links color */
	a {
		color: <?php mfn_opts_show( 'color-a', '#2991d6' ) ?>;
	}
	
	a:hover {
		color: <?php mfn_opts_show( 'color-a-hover', '#2275ac' ) ?>;
	}
	
/* Selections */
	*::-moz-selection {
		background-color: <?php mfn_opts_show( 'color-a', '#2991d6' ) ?>;
	}
	*::selection {
		background-color: <?php mfn_opts_show( 'color-a', '#2991d6' ) ?>;		
	}
	
/* Grey */
	.blockquote p.author span, .counter .desc_wrapper .title, .article_box .desc_wrapper p, .team .desc_wrapper p.subtitle, 
	.pricing-box .plan-header p.subtitle, .pricing-box .plan-header .price sup.period, .chart_box p, .fancy_heading .inside,
	.fancy_heading_line .slogan, .post-meta, .post-meta a, .post-footer, .post-footer a span.label, .pager .pages a, .button-love a .label,
	.pager-single a, #comments .commentlist > li .comment-author .says, .fixed-nav .desc .date, .filters_buttons li.label, .Recent_posts ul li a .desc .date,
	.widget_recent_entries ul li .post-date, .tp_recent_tweets .twitter_time, .widget_price_filter .price_label, .shop-filters .woocommerce-result-count,
	.woocommerce ul.product_list_widget li .quantity, .widget_shopping_cart ul.product_list_widget li dl, .product_meta .posted_in,
	.woocommerce .shop_table .product-name .variation > dd, .shipping-calculator-button:after,  .shop_slider .shop_slider_ul li .item_wrapper .price del,
	.testimonials_slider .testimonials_slider_ul li .author span, .testimonials_slider .testimonials_slider_ul li .author span a, .Latest_news ul li .desc_footer {
		color: <?php mfn_opts_show( 'color-note', '#a8a8a8' ) ?>;
	}
	
/* Headings font */
	h1, h1 a, h1 a:hover, .text-logo #logo { color: <?php mfn_opts_show( 'color-h1', '#444444' ) ?>; }
	h2, h2 a, h2 a:hover { color: <?php mfn_opts_show( 'color-h2', '#444444' ) ?>; }
	h3, h3 a, h3 a:hover { color: <?php mfn_opts_show( 'color-h3', '#444444' ) ?>; }
	h4, h4 a, h4 a:hover, .style-simple .sliding_box .desc_wrapper h4 { color: <?php mfn_opts_show( 'color-h4', '#444444' ) ?>; }
	h5, h5 a, h5 a:hover { color: <?php mfn_opts_show( 'color-h5', '#444444' ) ?>; }
	h6, h6 a, h6 a:hover, 
	a.content_link .title { color: <?php mfn_opts_show( 'color-h6', '#444444' ) ?>; }		
	
/* Highlight */
	.dropcap, .highlight:not(.highlight_image) {
		background-color: <?php mfn_opts_show( 'background-highlight', '#2991d6' ) ?>;
	}
	
/* Buttons */
	a.button, a.tp-button {
		background-color: <?php mfn_opts_show( 'background-button', '#f7f7f7' ) ?>;
		color: <?php mfn_opts_show( 'color-button', '#747474' ) ?>;
	}
	
	.button-stroke a.button, .button-stroke a.button .button_icon i, .button-stroke a.tp-button {
	    border-color: <?php mfn_opts_show( 'background-button', '#f7f7f7' ) ?>;
	    color: <?php mfn_opts_show( 'color-button', '#747474' ) ?>;
	}
	.button-stroke a:hover.button, .button-stroke a:hover.tp-button {
		background-color: <?php mfn_opts_show( 'background-button', '#f7f7f7' ) ?> !important;
		color: #fff;
	}
	
	/* .button_theme */
	a.button_theme, a.tp-button.button_theme,
	button, input[type="submit"], input[type="reset"], input[type="button"] {
		background-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
		color: #fff;
	}
	
	.button-stroke a.button.button_theme:not(.action_button), .button-stroke a.button.button_theme:not(.action_button),
	.button-stroke a.button.button_theme .button_icon i, .button-stroke a.tp-button.button_theme,
	.button-stroke button, .button-stroke input[type="submit"], .button-stroke input[type="reset"], .button-stroke input[type="button"] {
	    border-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	    color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?> !important;
	}
	.button-stroke a.button.button_theme:hover, .button-stroke a.tp-button.button_theme:hover,
	.button-stroke button:hover, .button-stroke input[type="submit"]:hover, .button-stroke input[type="reset"]:hover, .button-stroke input[type="button"]:hover {
	    background-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?> !important;
		color: #fff !important;
	}
	
	
/* Fancy Link */
	a.mfn-link { 
		color: <?php mfn_opts_show( 'color-fancy-link', '#656B6F' ) ?>; 
	}		
	a.mfn-link-2 span, a:hover.mfn-link-2 span:before, a.hover.mfn-link-2 span:before, a.mfn-link-5 span, a.mfn-link-8:after, a.mfn-link-8:before { 
		background: <?php mfn_opts_show( 'background-fancy-link', '#2195de' ) ?>; 
	}	
	a:hover.mfn-link { 
		color: <?php mfn_opts_show( 'color-fancy-link-hover', '#2991d6' ) ?>;
	}
	a.mfn-link-2 span:before, a:hover.mfn-link-4:before, a:hover.mfn-link-4:after, a.hover.mfn-link-4:before, a.hover.mfn-link-4:after, a.mfn-link-5:before, a.mfn-link-7:after, a.mfn-link-7:before { 
		background: <?php mfn_opts_show( 'background-fancy-link-hover', '#2275ac' ) ?>; 
	}
	a.mfn-link-6:before {
		border-bottom-color: <?php mfn_opts_show( 'background-fancy-link-hover', '#2275ac' ) ?>;
	}
	
/* Shop buttons */
	.woocommerce a.button, .woocommerce .quantity input.plus, .woocommerce .quantity input.minus {
		background-color: <?php mfn_opts_show( 'background-button', '#f7f7f7' ) ?> !important;
		color: <?php mfn_opts_show( 'color-button', '#747474' ) ?> !important;
	}
	
	.woocommerce a.button_theme, .woocommerce a.checkout-button, .woocommerce button.button,  
	.woocommerce .button.add_to_cart_button, .woocommerce .button.product_type_external,
	.woocommerce input[type="submit"], 
	.woocommerce input[type="reset"], 
	.woocommerce input[type="button"] {
		background-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?> !important;
		color: #fff !important;		
	}
	
/* Lists */
	.column_column ul, .column_column ol, .the_content_wrapper ul, .the_content_wrapper ol {
		color: <?php mfn_opts_show( 'color-list', '#737E86' ) ?>;
	}
	
/* Dividers */
	.hr_color, .hr_color hr, .hr_dots span {
		color: <?php mfn_opts_show( 'color-hr', '#2991d6' ) ?>;
		background: <?php mfn_opts_show( 'color-hr', '#2991d6' ) ?>;
	}
	.hr_zigzag i {
		color: <?php mfn_opts_show( 'color-hr', '#2991d6' ) ?>;
	} 
	
/* Highlight section */
	.highlight-left:after,
	.highlight-right:after {
		background: <?php mfn_opts_show( 'background-highlight-section', '#2991d6' ) ?>;
	}
	@media only screen and (max-width: 767px) {
		.highlight-left	.column:first-child,
		.highlight-right .column:last-child {
			background: <?php mfn_opts_show( 'background-highlight-section', '#2991d6' ) ?>;
		}
	}	
	
	
/************************ Header ************************/	

	#Header .top_bar_left, .header-classic #Top_bar, .header-plain #Top_bar, .header-stack #Top_bar, .header-split #Top_bar,
	.header-fixed #Top_bar, .header-below #Top_bar, #Header_creative, #Top_bar #menu {
		background-color: <?php mfn_opts_show( 'background-top-left', '#ffffff' ) ?>;
	}
	#Top_bar .top_bar_right:before {
		background-color: <?php mfn_opts_show( 'background-top-middle', '#e3e3e3' ) ?>;
	}
	#Header .top_bar_right {
		background-color: <?php mfn_opts_show( 'background-top-right', '#f5f5f5' ) ?>;
	}
	#Top_bar .top_bar_right a { 
		color: <?php mfn_opts_show( 'color-top-right-a', '#444444' ) ?>;
	}
	
	#Top_bar .menu > li > a { 
		color: <?php mfn_opts_show( 'color-menu-a', '#444444' ) ?>;
	}
	#Top_bar .menu > li.current-menu-item > a,
	#Top_bar .menu > li.current_page_item > a,
	#Top_bar .menu > li.current-menu-ancestor > a,
	#Top_bar .menu > li.current-page-ancestor > a,
	#Top_bar .menu > li.current_page_ancestor > a,
	#Top_bar .menu > li.hover > a { 
		color: <?php mfn_opts_show( 'color-menu-a-active', '#2991d6' ) ?>; 
	}
	#Top_bar .menu > li a:after { 
		background: <?php mfn_opts_show( 'color-menu-a-active', '#2991d6' ) ?>; 
	}

	.menu-highlight #Top_bar #menu > ul > li.current-menu-item > a,
	.menu-highlight #Top_bar #menu > ul > li.current_page_item > a,
	.menu-highlight #Top_bar #menu > ul > li.current-menu-ancestor > a,
	.menu-highlight #Top_bar #menu > ul > li.current-page-ancestor > a,
	.menu-highlight #Top_bar #menu > ul > li.current_page_ancestor > a,
	.menu-highlight #Top_bar #menu > ul > li.hover > a { 
		background: <?php mfn_opts_show( 'background-menu-a-active', '#F2F2F2' ) ?>; 
	}
	
	.menu-arrow-bottom #Top_bar .menu > li > a:after {
   		border-bottom-color: <?php mfn_opts_show( 'color-menu-a-active', '#2991d6' ) ?>;
	}
	.menu-arrow-top #Top_bar .menu > li > a:after {
	    border-top-color: <?php mfn_opts_show( 'color-menu-a-active', '#2991d6' ) ?>;
	}
	
	.header-plain #Top_bar .menu > li.current-menu-item > a,
	.header-plain #Top_bar .menu > li.current_page_item > a,
	.header-plain #Top_bar .menu > li.current-menu-ancestor > a,
	.header-plain #Top_bar .menu > li.current-page-ancestor > a,
	.header-plain #Top_bar .menu > li.current_page_ancestor > a,
	.header-plain #Top_bar .menu > li.hover > a,
	.header-plain #Top_bar a:hover#header_cart,
	.header-plain #Top_bar a:hover#search_button,
	.header-plain #Top_bar .wpml-languages:hover,
	.header-plain #Top_bar .wpml-languages ul.wpml-lang-dropdown {
		background: <?php mfn_opts_show( 'background-menu-a-active', '#F2F2F2' ) ?>; 
		color: <?php mfn_opts_show( 'color-menu-a-active', '#2991d6' ) ?>;
	}
	
	.header-plain #Top_bar,
	.header-plain #Top_bar .menu > li > a span:not(.description),
	.header-plain #Top_bar a#header_cart,
	.header-plain #Top_bar a#search_button,
	.header-plain #Top_bar .wpml-languages,
	.header-plain #Top_bar a.button.action_button {
		border-color: <?php mfn_opts_show( 'border-menu-plain', '#f2f2f2' ) ?>;
	}
	
	#Top_bar .menu > li ul {
		background-color: <?php mfn_opts_show( 'background-submenu', '#F2F2F2' ) ?>;
	}
	#Top_bar .menu > li ul li a {
		color: <?php mfn_opts_show( 'color-submenu-a', '#5f5f5f' ) ?>;
	}
	#Top_bar .menu > li ul li a:hover,
	#Top_bar .menu > li ul li.hover > a {
		color: <?php mfn_opts_show( 'color-submenu-a-hover', '#2e2e2e' ) ?>;
	}
	#Top_bar .search_wrapper { 
		background: <?php mfn_opts_show( 'background-search', '#2991D6' ) ?>; 
	}
	
	#Subheader .title  {
		color: <?php mfn_opts_show( 'color-subheader', '#888888' ) ?>;
	}
	#Subheader ul.breadcrumbs li, #Subheader ul.breadcrumbs li a  {
		color: <?php hex2rgba( mfn_opts_get( 'color-subheader', '#888888' ), .6, true ) ?>;
	}
	
	#Overlay {
		background: <?php hex2rgba( mfn_opts_get( 'background-overlay-menu', '#2991D6' ), .92, true ) ?>;
	}
	#overlay-menu ul li a, .header-overlay .overlay-menu-toggle.focus {
		color: <?php mfn_opts_show( 'background-overlay-menu-a', '#ffffff' ) ?>;
	}
		
	
/************************ Footer ************************/

	#Footer, #Footer .widget_recent_entries ul li a {
		color: <?php mfn_opts_show( 'color-footer', '#cccccc' ) ?>;
	}
	
	#Footer a {
		color: <?php mfn_opts_show( 'color-footer-a', '#2991d6' ) ?>;
	}
	
	#Footer a:hover {
		color: <?php mfn_opts_show( 'color-footer-a-hover', '#2275ac' ) ?>;
	}
	
	#Footer h1, #Footer h1 a, #Footer h1 a:hover,
	#Footer h2, #Footer h2 a, #Footer h2 a:hover,
	#Footer h3, #Footer h3 a, #Footer h3 a:hover,
	#Footer h4, #Footer h4 a, #Footer h4 a:hover,
	#Footer h5, #Footer h5 a, #Footer h5 a:hover,
	#Footer h6, #Footer h6 a, #Footer h6 a:hover {
		color: <?php mfn_opts_show( 'color-footer-heading', '#ffffff' ) ?>;
	}
	
/* Theme color */
	#Footer .themecolor, #Footer .widget_meta ul, #Footer .widget_pages ul, #Footer .widget_rss ul, #Footer .widget_mfn_recent_comments ul li:after, #Footer .widget_archive ul, 
	#Footer .widget_recent_comments ul li:after, #Footer .widget_nav_menu ul, #Footer .widget_price_filter .price_label .from, #Footer .widget_price_filter .price_label .to,
	#Footer .star-rating span {
		color: <?php mfn_opts_show( 'color-footer-theme', '#2991d6' ) ?>;
	}
	
/* Theme background */
	#Footer .themebg, #Footer .widget_categories ul, #Footer .Recent_posts ul li .desc:after, #Footer .Recent_posts ul li .photo .c,
	#Footer .widget_recent_entries ul li:after, #Footer .widget_mfn_menu ul li a:hover, #Footer .widget_product_categories ul {
		background-color: <?php mfn_opts_show( 'color-footer-theme', '#2991d6' ) ?>;
	}
	
/* Grey */
	#Footer .Recent_posts ul li a .desc .date, #Footer .widget_recent_entries ul li .post-date, #Footer .tp_recent_tweets .twitter_time, 
	#Footer .widget_price_filter .price_label, #Footer .shop-filters .woocommerce-result-count, #Footer ul.product_list_widget li .quantity, 
	#Footer .widget_shopping_cart ul.product_list_widget li dl {
		color: <?php mfn_opts_show( 'color-footer-note', '#a8a8a8' ) ?>;
	}
	
	
/************************ Sliding Top ************************/

	#Sliding-top, #Sliding-top .widget_recent_entries ul li a {
		color: <?php mfn_opts_show( 'color-sliding-top', '#cccccc' ) ?>;
	}
	
	#Sliding-top a {
		color: <?php mfn_opts_show( 'color-sliding-top-a', '#2991d6' ) ?>;
	}
	
	#Sliding-top a:hover {
		color: <?php mfn_opts_show( 'color-sliding-top-a-hover', '#2275ac' ) ?>;
	}
	
	#Sliding-top h1, #Sliding-top h1 a, #Sliding-top h1 a:hover,
	#Sliding-top h2, #Sliding-top h2 a, #Sliding-top h2 a:hover,
	#Sliding-top h3, #Sliding-top h3 a, #Sliding-top h3 a:hover,
	#Sliding-top h4, #Sliding-top h4 a, #Sliding-top h4 a:hover,
	#Sliding-top h5, #Sliding-top h5 a, #Sliding-top h5 a:hover,
	#Sliding-top h6, #Sliding-top h6 a, #Sliding-top h6 a:hover {
		color: <?php mfn_opts_show( 'color-sliding-top-heading', '#ffffff' ) ?>;
	}
	
/* Theme color */
	#Sliding-top .themecolor, #Sliding-top .widget_meta ul, #Sliding-top .widget_pages ul, #Sliding-top .widget_rss ul, #Sliding-top .widget_mfn_recent_comments ul li:after, #Sliding-top .widget_archive ul, 
	#Sliding-top .widget_recent_comments ul li:after, #Sliding-top .widget_nav_menu ul, #Sliding-top .widget_price_filter .price_label .from, #Sliding-top .widget_price_filter .price_label .to,
	#Sliding-top .star-rating span {
		color: <?php mfn_opts_show( 'color-sliding-top-theme', '#2991d6' ) ?>;
	}
	
/* Theme background */
	#Sliding-top .themebg, #Sliding-top .widget_categories ul, #Sliding-top .Recent_posts ul li .desc:after, #Sliding-top .Recent_posts ul li .photo .c,
	#Sliding-top .widget_recent_entries ul li:after, #Sliding-top .widget_mfn_menu ul li a:hover, #Sliding-top .widget_product_categories ul {
		background-color: <?php mfn_opts_show( 'color-sliding-top-theme', '#2991d6' ) ?>;
	}
	
/* Grey */
	#Sliding-top .Recent_posts ul li a .desc .date, #Sliding-top .widget_recent_entries ul li .post-date, #Sliding-top .tp_recent_tweets .twitter_time, 
	#Sliding-top .widget_price_filter .price_label, #Sliding-top .shop-filters .woocommerce-result-count, #Sliding-top ul.product_list_widget li .quantity, 
	#Sliding-top .widget_shopping_cart ul.product_list_widget li dl {
		color: <?php mfn_opts_show( 'color-sliding-top-note', '#a8a8a8' ) ?>;
	}
	
	
/************************ Shortcodes ************************/

/* Blockquote */
	blockquote, blockquote a, blockquote a:hover {
		color: <?php mfn_opts_show( 'color-blockquote', '#444444' ) ?>;
	}
	
/* Image frames & Google maps & Icon bar */
	.image_frame .image_wrapper .image_links,
	.portfolio_group.masonry-hover .portfolio-item .masonry-hover-wrapper .hover-desc { 
		background: <?php hex2rgba( mfn_opts_get( 'background-imageframe-link', '#2991d6' ), 0.8, true ) ?>;
	}
	
	.masonry.tiles .post-item .post-desc-wrapper .post-desc .post-title:after, .masonry.tiles .post-item.no-img, .masonry.tiles .post-item.format-quote {
		background: <?php mfn_opts_show( 'background-imageframe-link', '#2991d6' ) ?>;
	}
       
	.image_frame .image_wrapper .image_links a {
		color: <?php mfn_opts_show( 'color-imageframe-link', '#ffffff' ) ?>;
	}
	.image_frame .image_wrapper .image_links a:hover {
		background: <?php mfn_opts_show( 'color-imageframe-link', '#ffffff' ) ?>;
		color: <?php mfn_opts_show( 'background-imageframe-link', '#2991d6' ) ?>;
	}	
	
/* Sliding box */
	.sliding_box .desc_wrapper {
		background: <?php mfn_opts_show( 'background-slidingbox-title', '#2991d6' ) ?>;
	}
	.sliding_box .desc_wrapper:after {
		border-bottom-color: <?php mfn_opts_show( 'background-slidingbox-title', '#2991d6' ) ?>;
	}
	
/* Counter & Chart */
	.counter .icon_wrapper i {
		color: <?php mfn_opts_show( 'color-counter', '#2991d6' ) ?>;
	}

/* Quick facts */
	.quick_fact .number-wrapper {
		color: <?php mfn_opts_show( 'color-quickfact-number', '#2991d6' ) ?>;
	}
	
/* Progress bar */
	.progress_bars .bars_list li .bar .progress { 
		background-color: <?php mfn_opts_show( 'background-progressbar', '#2991d6' ) ?>;
	}
	
/* Icon bar */
	a:hover.icon_bar {
		color: <?php mfn_opts_show( 'color-iconbar', '#2991d6' ) ?> !important;
	}
	
/* Content links */
	a.content_link, a:hover.content_link {
		color: <?php mfn_opts_show( 'color-contentlink', '#2991d6' ) ?>;
	}
	a.content_link:before {
		border-bottom-color: <?php mfn_opts_show( 'color-contentlink', '#2991d6' ) ?>;
	}
	a.content_link:after {
		border-color: <?php mfn_opts_show( 'color-contentlink', '#2991d6' ) ?>;
	}
	
/* Get in touch & Infobox */
	.get_in_touch, .infobox {
		background-color: <?php mfn_opts_show( 'background-getintouch', '#2991d6' ) ?>;
	}
	.column_map .google-map-contact-wrapper .get_in_touch:after {
		border-top-color: <?php mfn_opts_show( 'background-getintouch', '#2991d6' ) ?>;
	}
	
/* Timeline & Post timeline */
	.timeline_items li h3:before,
	.timeline_items:after,
	.timeline .post-item:before { 
		border-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}
	
/* How it works */
	.how_it_works .image .number { 
		background: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}
	
/* Trailer box */
	.trailer_box .desc .subtitle {
		background-color: <?php mfn_opts_show( 'background-trailer-subtitle', '#2991d6' ) ?>;
	}
	
/* Icon box */
	.icon_box .icon_wrapper, .icon_box a .icon_wrapper,
	.style-simple .icon_box:hover .icon_wrapper {
		color: <?php mfn_opts_show( 'color-iconbox', '#2991d6' ) ?>;
	}
	.icon_box:hover .icon_wrapper:before, 
	.icon_box a:hover .icon_wrapper:before { 
		background-color: <?php mfn_opts_show( 'color-iconbox', '#2991d6' ) ?>;
	}	
	
/* Clients */	
	ul.clients.clients_tiles li .client_wrapper:hover:before { 
		background: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}
	ul.clients.clients_tiles li .client_wrapper:after { 
		border-bottom-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}	
	
/* List */
	.list_item.lists_1 .list_left {
		background-color: <?php mfn_opts_show( 'color-list-icon', '#2991d6' ) ?>;
	}
	.list_item .list_left {
		color: <?php mfn_opts_show( 'color-list-icon', '#2991d6' ) ?>;
	}
	
/* Features list */
	.feature_list ul li .icon i { 
		color: <?php mfn_opts_show( 'color-list-icon', '#2991d6' ) ?>;
	}
	.feature_list ul li:hover,
	.feature_list ul li:hover a {
		background: <?php mfn_opts_show( 'color-list-icon', '#2991d6' ) ?>;
	}	
	
/* Tabs, Accordion, Toggle, Table, Faq */
	.ui-tabs .ui-tabs-nav li.ui-state-active a,
	.accordion .question.active .title > .acc-icon-plus,
	.accordion .question.active .title > .acc-icon-minus,
	.faq .question.active .title > .acc-icon-plus,
	.faq .question.active .title,
	.accordion .question.active .title {
		color: <?php mfn_opts_show( 'color-tab-title', '#2991d6' ) ?>;
	}
	.ui-tabs .ui-tabs-nav li.ui-state-active a:after {
		background: <?php mfn_opts_show( 'color-tab-title', '#2991d6' ) ?>;
	}
	table tr:hover td {
		background: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?>;
	}

/* Pricing */
	.pricing-box .plan-header .price sup.currency,
	.pricing-box .plan-header .price > span {
		color: <?php mfn_opts_show( 'color-pricing-price', '#2991d6' ) ?>;
	}
	.pricing-box .plan-inside ul li .yes { 
		background: <?php mfn_opts_show( 'color-pricing-price', '#2991d6' ) ?>;
	}
	.pricing-box-box.pricing-box-featured {
		background: <?php mfn_opts_show( 'background-pricing-featured', '#2991d6' ) ?>;
	}
	

/************************ Shop ************************/
	.woocommerce span.onsale, .shop_slider .shop_slider_ul li .item_wrapper span.onsale {
		border-top-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?> !important;
	}
	.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
		border-color: <?php mfn_opts_show( 'color-theme', '#2991d6' ) ?> !important;
	}	
	
	
/************************ Responsive ************************/
	<?php if( mfn_opts_get('responsive') ): ?>
		@media only screen and (max-width: 767px){
			#Top_bar, #Action_bar { background: <?php mfn_opts_show( 'background-top-left', '#ffffff' ) ?> !important;}
		}
	<?php endif; ?>

