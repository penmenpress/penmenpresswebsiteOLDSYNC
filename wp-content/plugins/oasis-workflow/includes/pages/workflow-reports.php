<?php
// phpcs:ignore
$selected_tab = ( isset ( $_GET['tab'] ) && sanitize_text_field( $_GET["tab"] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'userAssignments';
?>
<div class="wrap">
	<?php
	// phpcs:ignore
	$tabs = array(
		'userAssignments'     => esc_html__( 'Current Assignments', "oasisworkflow" ),
		'workflowSubmissions' => esc_html__( 'Workflow Submissions', "oasisworkflow" )
	);
	echo '<div id="icon-themes" class="icon32"><br></div>';
	echo '<h2 class="nav-tab-wrapper">';
	// phpcs:ignore
	foreach ( $tabs as $tab => $name ) {
		$class = ( $tab == $selected_tab ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab" . esc_attr( $class ) . "' href='?page=oasiswf-reports&tab=" . esc_attr( $tab ) . "'>" . esc_attr( $name ) . "</a>";

	}
	echo '</h2>';
	switch ( $selected_tab ) {
		case 'userAssignments' :
			include( OASISWF_PATH . "includes/pages/workflow-assignment-report.php" );
			break;
		case 'workflowSubmissions' :
			include( OASISWF_PATH . "includes/pages/workflow-submission-report.php" );
			break;
	}
	?>
</div>