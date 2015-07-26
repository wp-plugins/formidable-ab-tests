<?php

function inmab_adds_to_admin_head() {
	wp_register_style( 'admin-inmab-css', plugins_url( 'formidable-ab-tests/css/inmab-admin-style.css' ) );
	wp_enqueue_style( 'admin-inmab-css' );
}
add_action( 'admin_enqueue_scripts', 'inmab_adds_to_admin_head' );

function inmab_add_admin_menu(  ) { 
	
	add_submenu_page( 'formidable', 'Formidable A/B Test Results', 'A/B Test', 'manage_options', 'inmab_test', 'inmab_options_page' );

}
add_action( 'admin_menu', 'inmab_add_admin_menu', 1000 );


function inmab_options_page(  ) { 
?>
	<h3><?php _e( 'Easily create A/B tests of your Formidable forms.', 'wp_admin_style' ); ?></h3>

	<div class="wrap">

		<div id="icon-options-general" class="icon32"></div>
		<h2><?php esc_attr_e( 'Formidable A/B Tests', 'wp_admin_style' ); ?></h2>

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<!-- main content -->
				<div id="post-body-content">

					<div class="meta-box-sortables ui-sortable">

						<div class="postbox">

							<h3><span><?php esc_attr_e( 'Directions', 'wp_admin_style' ); ?></span></h3>

							<div class="inside">
								<p>
									<ul>
										<li><?php esc_attr_e('Create the forms you would like to A/B test.', 'wp_admin_style'); ?></li>
										<li><?php esc_attr_e('Add this shortcode to the page or post where you want the forms to appear:', 'wp_admin_style'); ?></li>
											<ul>
												<li><pre>[inm_frm_ab_test forms="25, 26"]</pre></li>
											</ul>
										<li><?php esc_attr_e('Replace 25 & 26 with the ID\'s of your forms.', 'wp_admin_style'); ?></li>
										<li><strong><?php esc_attr_e('Optional Parameters:', 'wp_admin_style'); ?></strong></li>
										<li>title=true (defalut: false)</li>
										<li>description=true (defalut: false)</li>
										<li>minimize=1 (defalut: 0)</li>
									</ul>
								</p>
							</div>
							<!-- .inside -->

						</div>
						<!-- .postbox -->

					</div>
					<!-- .meta-box-sortables .ui-sortable -->

					<div class="meta-box-sortables ui-sortable">

						<div class="postbox">

							<h3><span><?php esc_attr_e( 'Results', 'wp_admin_style' ); ?></span></h3>

							<div class="inside">
								<div class="inmab-results-wrap">
									<?php inmab_get_test_results(); ?>
								</div>
							</div>
							<!-- .inside -->

						</div>
						<!-- .postbox -->

					</div>
					<!-- .meta-box-sortables .ui-sortable -->
				
				<!-- Reset Button -->
				<div class="inmab-reset-button">
					<form id="delete_all" action="#" method="post" enctype="multipart/form-data">
						<input type="submit" id="reset_data" name="delete_all" value="Reset Test Data"/>
					</form> 
					<script language="JavaScript">
						jQuery(function() {
							jQuery("#reset_data").click(function(){
								if (confirm("WARNING!!! You are about to reset ALL test data!")){
									$('form#delete_all').submit();
								}else{
									event.preventDefault();
								}
							});
						});
					</script>
				</div>
				<!-- .inmab-reset-button -->

				</div>
				<!-- post-body-content -->
				
				<!-- sidebar -->
				<div id="postbox-container-1" class="postbox-container">

					<div class="meta-box-sortables">

						<div class="postbox">
							<div class="inside">
								<div class="inmab-details">
									<div class="inmab-logo">
										<?php echo '<img class="" alt="Formidable A/B Tests" title="Formidable A/B Tests" src="' . plugins_url( 'formidable-ab-tests/images/inmab-logo.jpg' ) . '" />';?>
										<div style="clear:both;"></div>
									</div>
									<div class="inmab-ref">
										<a class="" href="//imnotmarvin.com/formidable-ab-tests/" target="_blank">Formidable A/B Tests Page</a><br /><br /><br /><br />
										<div style="clear:both;"></div>
									</div>
									<div class="inm-logo">
										<a href="//imnotmarvin.com" target="_blank">
											<?php echo '<img class="" src="' . plugins_url( 'formidable-ab-tests/images/Logo2_200.png' ) . '" />';?>
										</a>
										<div style="clear:both;"></div>
									</div>
									<div class="inmab-ref">
										<h2 style="text-align: center;">Other Products</h2>
										<a class="" href="//imnotmarvin.com/wordpress-total-defense/" target="_blank">WP Total Defense</a>
										<div style="clear:both;"></div>
										<p style="">Managed security, backups and updates for WordPress sites.</p>
									</div>
								</div>
							</div>
							<!-- .inside -->

						</div>
						<!-- .postbox -->

					</div>
					<!-- .meta-box-sortables -->

				</div>
				<!-- #postbox-container-1 .postbox-container -->

			</div>
			<!-- #post-body .metabox-holder .columns-2 -->

			<br class="clear">
		</div>
		<!-- #poststuff -->

	</div> <!-- .wrap -->
	
	<?php
}

function cmp($a, $b) {
    return strcmp($a["percent"], $b["percent"]);
}

function build_sorter($key) {
    return function ($a, $b) use ($key) {
        return strnatcmp($a[$key], $b[$key]);
    };
}

function inmab_get_test_results() {
	global $wpdb;
	//global $table_name;
	$table_name = $wpdb->prefix . TABLE_NAME;
	
	$form_ids = $wpdb->get_results( 'SELECT DISTINCT form_id FROM '. $table_name . ' ORDER BY form_id ASC' );
	
	$total_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	
	?>
	<div class="inmab-results" style="clear:both;">
		<div class="inmab-results-title"><h3>Forms viewed <?php echo $total_count ?> times total.</h3></div>
	<?php
	
	$tots = array();
	$tcount = 0;
	
	foreach( $form_ids as $fid ) {
	
		$show_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE form_id = $fid->form_id" );
		$submit_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE form_id = $fid->form_id AND form_submit = 1" );
		
		$tots[$tcount]["form_id"] = $fid->form_id;
		$tots[$tcount]["shown"] = $show_count;
		$tots[$tcount]["submits"] = $submit_count;
		$tots[$tcount]["percent"] = ($submit_count / $show_count) * 100;
		
		$tcount ++;
	
	}

	usort($tots, build_sorter('percent'));
	
	$tots = array_reverse( $tots );
	$highsub = $tots[0]["submits"];
	
	foreach( $tots as $tot ) {
		if( $highsub >> 0 ) {
			$chartpos = ( $tot["submits"] / $highsub ) * 100;
		} else {
			$chartpos = 0;
		}
		?>
		<div class="inmab-result" style="width:100px;display:table-cell;">
			<div class="inmab-result-chart-wrap" style="background:white;height:250px;width:100px;display:table-cell;vertical-align:bottom;">
				<div class="inmab-result-chart-bar" style="background:blue;height: <?php echo $chartpos; ?>%;display:block;width:100px;">
				</div>
			</div>
			<div class="inmab-result-foot" style="background:white;color:blue;text-align:center;width:100px;">
				<p>Form ID: <?php echo $tot["form_id"]; ?></p>
				<p><?php echo $tot["shown"]; ?> views<p/>
				<p><?php echo $tot["submits"]; ?> submits</p>
			</div>
		</div>
		<div style="display:table-cell;width:30px;"></div>
		<?php
	}
	?>
	</div>
	<?php
}
?>