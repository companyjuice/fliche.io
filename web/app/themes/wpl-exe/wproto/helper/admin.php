<?php

class wpl_exe_wp_admin_utils {
	
	/**
	 * Clear old transients
	 **/
	public static function purge_transients( $older_than = '7 days', $safemode = true ) {
		global $wpdb;

		$older_than_time = strtotime('-' . $older_than);
		if ($older_than_time > time() || $older_than_time < 1) {
			return false;
		}

		$transients = $wpdb->get_col(
			$wpdb->prepare( "
					SELECT REPLACE(option_name, '_transient_timeout_', '') AS transient_name 
					FROM {$wpdb->options} 
					WHERE option_name LIKE '\_transient\_timeout\__%%'
						AND option_value < %s
			", $older_than_time)
		);
		if ($safemode) {
			foreach($transients as $transient) {
				get_transient($transient);
			}
		} else {
			$options_names = array();
			foreach($transients as $transient) {
				$options_names[] = '_transient_' . $transient;
				$options_names[] = '_transient_timeout_' . $transient;
			}
			if ($options_names) {
				$options_names = array_map(array($wpdb, 'escape'), $options_names);
				$options_names = "'". implode("','", $options_names) ."'";

				$result = $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name IN ({$options_names})" );
				if (!$result) {
					return false;
				}
			}
		}

		return $transients;
	}
	
	/**
	 * Get all CSS classes from FontAwesome Library
 	 **/
	public static function get_icons() {
		global $wpl_exe_wp;
		
		// get results from cache
		//delete_transient('wproto_font_icons');
		$icons = get_transient( 'wproto_font_icons' );
			
		if( $icons == false ) {
			
			$icons = array();
			
			$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
			$subject = file_get_contents( WPROTO_THEME_DIR . '/css/libs/font-awesome/css/font-awesome.css');

			preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER);

			foreach( $matches as $match){
				$icons['font-awesome'][ $match[1]] = $match[2];
			}	
			
			// get IcoMoon icons
			if( $wpl_exe_wp->get_option('icomoon_enabled', 'general') ) {
				$pattern = '/\.(icon-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
				$subject = file_get_contents( WPROTO_THEME_DIR . '/css/libs/icomoon/style.css');

				preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER);

				foreach( $matches as $match){
					$icons['icomoon'][ $match[1]] = $match[2];
				}
			}
				
			// cache results
			set_transient( 'wproto_font_icons', $icons, 60*60*12 );
				
		}

		return $icons;
	}
	
	/**
	 * Make post sticky
	 **/
	public static function make_post_sticky( $post_id, $make_sticky = true ) {
		$sticky_posts = get_option( 'sticky_posts' );
			
		if( $make_sticky ) {
			// make post sticky
			$sticky_posts[] = $post_id;
			update_option( 'sticky_posts', $sticky_posts );
			
		} else {
			// make post default
			$new_array = array();
			foreach( $sticky_posts as $k=>$v ) {
				if( $v == $post_id ) continue;
				$new_array[] = $v;
			}
			update_option( 'sticky_posts', $new_array );
		}
	}
	
	/**
	 * Image picker
	 **/
	public static function image_picker( $input_env, $input_name ) {
		global $wpl_exe_wp;
		
		$input_env = esc_attr( $input_env );
		$input_name = esc_attr( $input_name );
		
		$all_files = scandir( get_template_directory() . '/images/patterns/' );
		
		$images = array();
		$images_2x = array();
		
		if( count( $all_files ) > 0 ) {
			foreach( $all_files as $file ) {
				if( $file == '.' || $file == '..' ) continue;
				
				if( strpos( $file, '@2x') !== false ) {
					$images_2x[] = $file;
				} else {
					$images[] = $file;
				}
				
			}
		}
		
		if( count( $images ) > 0 ):
			$uniqid = uniqid();
			
			// get values
			$image = $wpl_exe_wp->get_option( $input_name, $input_env );
			$image = $image != NULL ? $image : '';
			$image = esc_attr( $image );
			
			$repeat = $wpl_exe_wp->get_option( $input_name . '_repeat', $input_env );
			$repeat = $repeat != NULL ? $repeat : '';
			$repeat = esc_attr( $repeat );
			
			$pos = $wpl_exe_wp->get_option( $input_name . '_pos', $input_env );
			$pos = $pos != NULL ? $pos : '';
			$pos = esc_attr( $pos );
			
		?>
		
		<div class="wproto-background-picker">
		
			<div class="items">
				<?php $i=0; foreach( $images as $img ): ?>
				<?php $full_img = get_template_directory_uri() . '/images/patterns/' . $img; ?>
				<a href="javascript:;" class="item<?php echo $full_img == $image ? ' selected' : ''; ?>" data-image="<?php echo $full_img; ?>" data-image-2x="<?php echo isset( $images_2x[ $i ] ) ? get_template_directory_uri() . '/images/patterns/' . $images_2x[ $i ] : ''; ?>" style="background: url(<?php echo get_template_directory_uri(); ?>/images/patterns/<?php echo $img; ?>);"></a>
				<?php $i++; endforeach; ?>
			</div>
		
			<p><?php _e('Image', 'wproto'); ?>:</p>
			<input class="wproto-image-picker-input" id="wproto-image-picker-<?php echo $uniqid; ?>" type="text" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>]" value="<?php echo $image; ?>" />
			<a href="javascript:;" class="button wproto-image-selector" data-url-input="#wproto-image-picker-<?php echo $uniqid; ?>"><?php _e( 'Upload', 'wproto' ); ?></a>
			<a href="javascript:;" class="button wproto-image-remover" data-url-input="#wproto-image-picker-<?php echo $uniqid; ?>"><?php _e( 'Remove', 'wproto' ); ?></a><br />
			
			<p><?php _e('Background image position', 'wproto'); ?>:</p>
			<select name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_repeat]">
				<option value=""><?php _e('Choose background repeat...', 'wproto'); ?></option>
				<option value="no-repeat"><?php _e('no repeat', 'wproto'); ?></option>
				<option <?php echo $repeat == 'repeat-x' ? 'selected="selected"' : ''; ?> value="repeat-x"><?php _e('repeat horizontal', 'wproto'); ?></option>
				<option <?php echo $repeat == 'repeat-y' ? 'selected="selected"' : ''; ?> value="repeat-y"><?php _e('repeat vertical', 'wproto'); ?></option>
				<option <?php echo $repeat == 'repeat' ? 'selected="selected"' : ''; ?> value="repeat"><?php _e('repeat all', 'wproto'); ?></option>
			</select>
			
			<select name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_pos]">
				<option value=""><?php _e('Choose background position...', 'wproto'); ?></option>
				<option <?php echo $pos == 'left top' ? 'selected="selected"' : ''; ?> value="left top"><?php _e('left top', 'wproto'); ?></option>
				<option <?php echo $pos == 'left center' ? 'selected="selected"' : ''; ?> value="left center"><?php _e('left center', 'wproto'); ?></option>
				<option <?php echo $pos == 'left bottom' ? 'selected="selected"' : ''; ?> value="left bottom"><?php _e('left bottom', 'wproto'); ?></option>
				<option <?php echo $pos == 'right top' ? 'selected="selected"' : ''; ?> value="right top"><?php _e('right top', 'wproto'); ?></option>
				<option <?php echo $pos == 'right center' ? 'selected="selected"' : ''; ?> value="right center"><?php _e('right center', 'wproto'); ?></option>
				<option <?php echo $pos == 'right bottom' ? 'selected="selected"' : ''; ?> value="right bottom"><?php _e('right bottom', 'wproto'); ?></option>
				<option <?php echo $pos == 'center top' ? 'selected="selected"' : ''; ?> value="center top"><?php _e('center top', 'wproto'); ?></option>
				<option <?php echo $pos == 'center center' ? 'selected="selected"' : ''; ?> value="center center"><?php _e('center center', 'wproto'); ?></option>
				<option <?php echo $pos == 'center bottom' ? 'selected="selected"' : ''; ?> value="center bottom"><?php _e('center bottom', 'wproto'); ?></option>
			</select>
			
		</div>
		
		<?php
		endif;
		
	}
	
	/**
	 * Switch picker
	 **/
	public static function switcher( $value, $toggle_element, $env, $name, $arrval = false ) {
		?>
		
			<div class="field switch">
				<label title="<?php _e('Enabled', 'wproto'); ?>" class="cb-enable <?php echo $value ? 'selected' : ''; ?>"><span><i class="fa fa-check"></i></span></label>
				<label title="<?php _e('Disabled', 'wproto'); ?>" class="cb-disable <?php echo ! $value ? 'selected' : ''; ?>"><span><i class="fa fa-times"></i></span></label>
				<input <?php if( is_string( $toggle_element ) ): ?>data-toggle-element="<?php echo esc_attr( $toggle_element ); ?>"<?php endif; ?> name="<?php echo esc_attr( $env ); ?><?php if( is_string( $name ) ): ?>[<?php echo esc_attr( $name ); ?>]<?php endif; ?><?php if( is_string( $arrval ) ): ?>[<?php echo esc_attr( $arrval ); ?>]<?php endif; ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" />
				<div class="clear"></div>
			</div>
		
		<?php
	}
	
	
	/**
	 * Font picker
	 **/
	public static function font_picker( $input_env, $input_name, $font_family_only = false ) {
		global $wpl_exe_wp;
		
		$input_env = esc_attr( $input_env );
		$input_name = esc_attr( $input_name );
		
		$fonts = wpl_exe_wp_utils::get_google_fonts();
		
		$fonts_custom = new WP_Query( array(
			'post_type' => 'wproto_custom_font', 
			'post_status' => 'publish',
			'posts_per_page' => -1
		));
		
		// get value
		$font_source = $wpl_exe_wp->get_option( $input_name . '_font_source', $input_env );
		$font_source = $font_source != NULL ? $font_source : '';
		
		$font_value = $wpl_exe_wp->get_option( $input_name . '_font', $input_env );
		$font_value = $font_value != NULL ? $font_value : '';
		
		$font_custom_id = $wpl_exe_wp->get_option( $input_name . '_font_custom_id', $input_env );
		$font_custom_id = $font_custom_id != NULL ? $font_custom_id : 0;
		
		$font_size = $wpl_exe_wp->get_option( $input_name . '_font_size', $input_env );
		$font_size = $font_size != NULL ? $font_size : '';
		
		$font_size_mobile = $wpl_exe_wp->get_option( $input_name . '_font_size_mobile', $input_env );
		$font_size_mobile = $font_size_mobile != NULL ? $font_size_mobile : '';
		
		$font_weight = $wpl_exe_wp->get_option( $input_name . '_font_weight', $input_env );
		$font_weight = $font_weight != NULL ? $font_weight : '';
		
		$font_style = $wpl_exe_wp->get_option( $input_name . '_font_style', $input_env );
		$font_style = $font_style != NULL ? $font_style : '';
		
		$line_height = $wpl_exe_wp->get_option( $input_name . '_line_height', $input_env );
		$line_height = $line_height != NULL ? $line_height : '';

		$line_height_mobile = $wpl_exe_wp->get_option( $input_name . '_line_height_mobile', $input_env );
		$line_height_mobile = $line_height_mobile != NULL ? $line_height_mobile : '';
		
		$all_caps = $wpl_exe_wp->get_option( $input_name . '_all_caps', $input_env );
		$all_caps = $all_caps != NULL ? $all_caps : false;
		
		?>
		<div class="wproto-font-picker-holder">
		
			<dl>
				<?php if( ! $font_family_only ): ?>
				<dt><?php _e('Font size', 'wproto'); ?>:</dt>
				<dd><input min="0.1" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_font_size]" type="number" value="<?php echo $font_size; ?>" step="0.1" />rem</dd>
				
				<dt><?php _e('Font size (for mobile devices)', 'wproto'); ?>:</dt>
				<dd><input min="0.1" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_font_size_mobile]" type="number" value="<?php echo $font_size_mobile; ?>" step="0.1" />rem</dd>
				
				<dt><?php _e('Line height', 'wproto'); ?>:</dt>
				<dd><input class="wpl-line-height" min="1" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_line_height]" type="number" value="<?php echo $line_height; ?>" />px</dd>
				
				<dt><?php _e('Line height (for mobile devices)', 'wproto'); ?>:</dt>
				<dd><input min="1" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_line_height_mobile]" type="number" value="<?php echo $line_height_mobile; ?>" />px</dd>
				
				<dt><?php _e('All caps', 'wproto'); ?>:</dt>
				<dd>
					
						<div class="field switch">
							<label title="<?php _e('Enabled', 'wproto'); ?>" class="cb-enable <?php echo $all_caps ? 'selected' : ''; ?>"><span><i class="fa fa-check"></i></span></label>
							<label title="<?php _e('Disabled', 'wproto'); ?>" class="cb-disable <?php echo ! $all_caps ? 'selected' : ''; ?>"><span><i class="fa fa-times"></i></span></label>
							<input class="wpl-all-caps" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_font_transform]" type="hidden" value="<?php echo $all_caps; ?>" />
							<div class="clear"></div>
						</div>
					
				</dd>
				<?php endif; ?>
				
				<?php
					$font_picker_input_name = $input_env . '[' . $input_name . '_font]';
				?>
			
				<dt><?php _e('Font source', 'wproto'); ?>:</dt>
				<dd>
					<select data-name="<?php echo $font_picker_input_name; ?>" class="wproto-font-source-switcer" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_font_source]">
						<option value="google"><?php _e('Google Fonts', 'wproto'); ?></option>
						<option <?php echo $font_source == 'custom' ? 'selected="selected"' : ''; ?> value="custom"><?php _e('Your Custom Fonts', 'wproto'); ?></option>
					</select>
				</dd>
			
				<dt class="wproto-font-family-custom" <?php echo $font_source == 'custom' ? '' : 'style="display: none"'; ?>><?php _e('Font family', 'wproto'); ?>:</dt>
				<dd class="wproto-font-family-custom" <?php echo $font_source == 'custom' ? '' : 'style="display: none"'; ?>>
				
					<select class="wproto-custom-font-selecter" name="<?php echo $font_source == 'custom' ? $font_picker_input_name : ''; ?>">
						<option value=""><?php _e('-- Please select --', 'wproto'); ?></option>
						<?php while ( $fonts_custom->have_posts() ): $fonts_custom->the_post(); $family = get_post_meta( get_the_ID(), 'font_family', true ); ?>
						<option data-id="<?php the_ID(); ?>" <?php echo $font_value == $family ? 'selected="selected"' : ''; ?> value="<?php echo $family; ?>"><?php the_title(); ?></option>
						<?php endwhile; ?>
					</select>
					
					<input class="wproto-hidden-custom-font-id" type="hidden" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_font_custom_id]" value="<?php echo $font_custom_id; ?>" />
				
				</dd>
			
				<dt class="wproto-font-family-google" <?php echo $font_source == '' || $font_source == 'google' ? '' : 'style="display: none"'; ?>><?php _e('Font family', 'wproto'); ?>:</dt>
				<dd class="wproto-font-family-google" <?php echo $font_source == '' || $font_source == 'google' ? '' : 'style="display: none"'; ?>>
				
					<select class="wproto-font-picker wproto-change-font-family" name="<?php echo $font_source == '' || $font_source == 'google' ? $font_picker_input_name : '';; ?>">
						<option value=""><?php _e('Default', 'wproto'); ?></option>
						<?php if( is_array( $fonts ) && count( $fonts ) > 0 ): ?>
							<?php foreach( $fonts as $k=>$font ): ?>
							<option <?php echo $font_value == $font ? 'selected="selected"' : ''; ?> value="<?php echo $font; ?>"><?php echo $font; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				
				</dd>
				
				<?php if( ! $font_family_only ): ?>
				<dt><?php _e('Font weight', 'wproto'); ?>:</dt>
				<dd>
				
					<select class="wproto-font-picker wpl-font-weight" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_font_weight]">
						<option value=""><?php _e('Default', 'wproto'); ?></option>
						<option <?php echo $font_weight == 'normal' ? 'selected="selected"' : ''; ?> value="normal"><?php _e('Normal', 'wproto'); ?></option>
						<option <?php echo $font_weight == '300' ? 'selected="selected"' : ''; ?> value="300">300</option>
						<option <?php echo $font_weight == '400' ? 'selected="selected"' : ''; ?> value="400">400</option>
						<option <?php echo $font_weight == '800' ? 'selected="selected"' : ''; ?> value="800">800</option>
					</select>
				
				</dd>
				<dt><?php _e('Font style', 'wproto'); ?>:</dt>
				<dd>
				
					<select class="wproto-font-picker wpl-font-style" name="<?php echo $input_env; ?>[<?php echo $input_name; ?>_font_style]">
						<option value=""><?php _e('Default', 'wproto'); ?></option>
						<option <?php echo $font_style == 'normal' ? 'selected="selected"' : ''; ?> value="normal"><?php _e('Normal', 'wproto'); ?></option>
						<option <?php echo $font_style == 'italic' ? 'selected="selected"' : ''; ?> value="italic"><?php _e('Italic', 'wproto'); ?></option>
					</select>
				
				</dd>
				<?php endif; ?>
			</dl>

			<?php if( $font_value <> '' ): ?>
			<link href="http://fonts.googleapis.com/css?family=<?php echo urlencode( $font_value ); ?>" rel="stylesheet" type="text/css">
			<?php endif; ?>
		
			<div class="wproto-font-preview-block"<?php echo $font_value == '' ? ' style="display: none"' : ''; ?> <?php echo $font_source == 'custom' ? 'style="display: none"' : ''; ?>>
				<p><?php _e('Font family preview', 'wproto'); ?>:</p>
				<p style="font-family: <?php echo $font_value; ?>;" class="wproto-font-preview">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tristique elit ac eros porttitor, nec ullamcorper tortor posuere. Nunc ipsum elit, volutpat sodales nisi sed, fermentum dignissim elit.</p>
			</div>
		</div>
		
		<?php
		
	}
	
	/**
	 * Social icons form
	 **/
	public static function social_icons_form( $form = 'settings', $user_id = 0, $post_id = 0 ) {
		global $wpl_exe_wp;
		$social_icons = $wpl_exe_wp->system_config['social_icons'];
		foreach( $social_icons as $icon ):
			if( $form == 'settings' ) {
				$value = $wpl_exe_wp->get_option( $icon['name'], 'api' );
			} elseif( $form == 'user' ) {
				$value = get_the_author_meta( $icon['name'], $user_id );
			} elseif( $form == 'post' ) {
				$value = get_post_meta( $post_id, $icon['name'], true );
			} 
		?>
		<tr>
			<td style="width: 140px">
				<i class="<?php echo esc_attr( $icon['icon'] ); ?>"></i> <?php echo esc_html( $icon['title'] ); ?>
			</td>
			<td>
				<input name="<?php if( $form == 'settings' ): ?>api[<?php endif; ?><?php echo esc_attr( $icon['name'] ); ?><?php if( $form == 'settings' ): ?>]<?php endif; ?>" placeholder="<?php printf( __( 'Paste %s URL here', 'wproto' ), $icon['title'] ); ?>" type="text" value="<?php echo esc_attr( @$value ); ?>" class="regular-text text" />
			</td>
		</tr>
		<?php
		endforeach;
	}
	
}