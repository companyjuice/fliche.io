<form action="<?php echo site_url(); ?>" class="search-form" method="get">

	<input type="text" class="form-input" name="s" placeholder="<?php _e('Type and hit Enter', 'wproto'); ?>..." value="<?php echo esc_attr( get_query_var('s') ); ?>" />
	<button type="submit"><i class="fa fa-search"></i></button>

</form>