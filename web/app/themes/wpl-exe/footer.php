<?php global $wpl_exe_wp; ?>
	
	<footer id="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php wpl_exe_wp_front::widgetized_footer(); ?>
				</div>
			</div>
		</div>
	</footer>
	
	<?php if( $wpl_exe_wp->get_option('show_bottom_bar', 'appearance') ): ?>
	<footer id="bottom-bar">
		<div class="container">
			<div class="row">
			
				<?php
					$copyright_text = $wpl_exe_wp->get_option('copyright', 'appearance');
					$bottom_bar_content = $wpl_exe_wp->get_option('content_after_copyright', 'appearance');
					
					$left_size_cols = $right_size_cols = '';
					
					if( $copyright_text <> '' && $bottom_bar_content <> '' ) {
						$left_size_cols = 'col-md-4 col-sm-6 col-xs-12';
						$right_size_cols = 'col-md-8 col-sm-6 col-xs-12';
					} elseif( $copyright_text == '' && $bottom_bar_content <> '' ) {
						$right_size_cols = 'col-md-12';
					} elseif( $copyright_text <> '' && $bottom_bar_content == '' ) {
						$left_size_cols = 'col-md-12';
					}

				?>
				
				<?php if( $copyright_text <> '' ): ?>
				<div class="<?php echo esc_attr( $left_size_cols ); ?>">
					<?php echo wp_kses_post( $wpl_exe_wp->get_option('copyright', 'appearance') ); ?>
					<?php if( $wpl_exe_wp->get_option('wplab_copyright', 'appearance') ): ?>
						<?php _e('Developed by', 'wproto'); ?> <a target="_blank" href="http://themeforest.net/user/wplab/?ref=wplab">WPlab</a>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<?php if( $bottom_bar_content <> '' ): ?>
				<div class="<?php echo esc_attr( $right_size_cols ); ?>">
					<div class="right-part">
					
						<?php if( $bottom_bar_content == 'menu' ): ?>
						
							<?php
								wp_nav_menu( array(
									'theme_location' => 'bottom_bar_menu',
									'menu' => '',
									'menu_id' => 'bottom-bar-menu',
									'fallback_cb' => false,
									'menu_class' => '',
									'container' => false						
								));
							?>
						
						<?php elseif( $bottom_bar_content == 'social_icons' ): ?>
						
							<div class="social-icons">
								<?php wpl_exe_wp_front::social_icons( 'global' ); ?>
							</div>
						
						<?php endif; ?>
					
					</div>
				</div>
				<?php endif; ?>
				
			</div>
		</div>
	</footer>
	<?php endif; ?>
	
	</div><!-- // wrap -->
	
	<?php wpl_exe_wp_front::footer(); wp_footer(); ?>
</body>
</html>