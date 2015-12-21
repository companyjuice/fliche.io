<?php
/**
 * Do stuff common to all model classes
 * that extend this database class
 **/
class wpl_exe_wp_database {
	/**
	 * Class vars
	 **/
	protected $wpdb = null;

	/**
	 * Make Wordpress dbase object and other
	 * models available to all model classes.
	 * Also, define database tables.
	 **/
	function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;

		$this->tables = array(
			'revslider_sliders' => $this->wpdb->prefix . "revslider_sliders",
			'revslider_slides' => $this->wpdb->prefix . "revslider_slides",
		);
	}
}
