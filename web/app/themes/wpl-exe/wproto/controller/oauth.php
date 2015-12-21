<?php
/**
 *	Manipulate with user accounts class
 **/
class wpl_exe_wp_oauth_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {


	}
	
	/**
	 * Get latest tweets
	 **/
	public function get_latest_tweets( $num ) {
		global $wpl_exe_wp;
		
		$twitter_login = $wpl_exe_wp->get_option( 'twitter_login', 'api' );
		$twitter_oauth_token = $wpl_exe_wp->get_option( 'twitter_oauth_token', 'api' );
		$twitter_oauth_token_secret = $wpl_exe_wp->get_option( 'twitter_oauth_token_secret', 'api' );
		$twitter_cunsumer_key = $wpl_exe_wp->get_option( 'twitter_cunsumer_key', 'api' );
		$twitter_cunsumer_secret = $wpl_exe_wp->get_option( 'twitter_cunsumer_secret', 'api' );
		
		$tweets = wp_cache_get( 'wproto_latest_tweets' );
		
		if ( false === $tweets ) {
				
			require_once WPROTO_THEME_DIR . '/library/twitterAPI/Creare_Twitter.php';
			
			$twitter = new Creare_Twitter();
			$twitter->screen_name = $twitter_login;
			$twitter->not = $num;
			
			$twitter->consumerkey = $twitter_cunsumer_key;
			$twitter->consumersecret = $twitter_cunsumer_secret;
			$twitter->accesstoken = $twitter_oauth_token;
			$twitter->accesstokensecret = $twitter_oauth_token_secret;
			
			$twitter->tags = true;
			$twitter->nofollow = true;
			$twitter->newwindow = true;
			$twitter->hashtags = true;
			$twitter->attags = true;
			
			$tweets = $twitter->getLatestTweets();
				
			wp_cache_set( 'wproto_latest_tweets', $tweets, '', $wpl_exe_wp->get_option( 'twitter_cache_time', 'api' ) );
				
		}
		
		return $tweets;
		
	}

}