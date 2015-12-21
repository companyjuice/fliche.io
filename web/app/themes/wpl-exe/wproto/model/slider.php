<?php
	/**
   * Slides model
   **/
	class wpl_exe_wp_slider extends wpl_exe_wp_database {    
		
		/**
		 * Get Revolution Slider slideshows
		 **/
		function get_revolution_slideshows() {
			
			$table = $this->tables['revslider_sliders'];

			return $this->wpdb->get_results(
				"SELECT *
					FROM $table
					WHERE 1"
			);
			
		}
                
	}