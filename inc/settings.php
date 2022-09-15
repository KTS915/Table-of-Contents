<?php

function kts_toc_settings() {

	$hook = add_submenu_page(
		'options-general.php',
		'Table of Contents',
		'Table of Contents',
		'manage_options',
		'toc',
		'kts_toc_settings_setup'
	);

	//add_action( 'load-' . $hook, 'kts_toc_function' ); just in case!
}
add_action( 'admin_menu', 'kts_toc_settings' );

function kts_toc_settings_init() {

	# Specify name of option for saving and retrieving values
	register_setting( 'kts_toc_settings', 'toc', array( 
		'sanitize_callback' => 'kts_toc_sanitize_options_array',
	) );

	# Status Indicator
	add_settings_section(
		'kts_toc_label_section',
		'',
		'',
		'kts_toc_settings'
	);

	add_settings_field(
		'kts_toc_label',
		'Table of Contents Label',
		'kts_toc_label_render',
		'kts_toc_settings',
		'kts_toc_label_section'
	);

	# Log Storage
	add_settings_section(
		'kts_toc_initial_section',
		'',
		'',
		'kts_toc_settings'
	);

	add_settings_field(
		'kts_toc_initial',
		'Initially Open or Closed?',
		'kts_toc_initially_open_or_closed',
		'kts_toc_settings',
		'kts_toc_initial_section'
	);
}
add_action( 'admin_init', 'kts_toc_settings_init' );

/* SANITIZE OPTION FIELDS */
function kts_toc_sanitize_options_array( $inputs_array ) {

	# Create array for storing the sanitized options
	$output = [];

	# Loop through each of the options inputs
	foreach( $inputs_array as $key => $value ) {

		# Storage should be digits only
		if ( $key === 'storage' ) {
			$output[$key] = absint( $value );
		}

		# Sanitize all other text inputs
		else {
			$output[$key] = sanitize_text_field( $value );
		}
	}

	# Return output array
	return apply_filters( 'kts_toc_options_array', $output, $inputs_array );
}


/* CHOOSE LABEL FOR ToC */
function kts_toc_label_render() {

	# Set default ToC Label
	$toc = get_option( 'toc' );
	$label = 'Table of Contents';
	if ( ! empty( $toc ) && ! empty( $toc['label'] ) ) {
		$label = $toc['label'];
	}
	?>

	<fieldset>
		<label>
			<input type="text" name="toc[label]" value="<?php echo $label; ?>">
			<span class=""><?php _e( 'Type a label for the ToC: default is Table of Contents' ); ?></span>
		</label>
	</fieldset>

<?php
}

/* CHOOSE WHETHER TOC SHOULD INITIALLY BE OPEN OR CLOSED */
function kts_toc_initially_open_or_closed() {

	# Set default state
	$toc = get_option( 'toc' );
	$initial = 'closed';
	if ( ! empty( $toc ) && ! empty( $toc['initial'] ) ) {
		$initial = $toc['initial'];
	}
	?>

	<fieldset>
		<label>
			<input type="radio" name="toc[initial]" value="open" <?php checked( 'open', $initial ); ?>>
			<?php _e( 'Open' ); ?>
		</label>
		<br>
		<label>
			<input type="radio" name="toc[initial]" value="closed" <?php checked( 'closed', $initial ); ?>>
			<?php _e( 'Closed' ); ?>
		</label>
	</fieldset> <?php
}

function kts_toc_settings_setup() { ?>

	<div class="wrap">
		<form action='options.php' method='post'>

			<h1>Table of Contents Settings</h1> <?php

			settings_fields( 'kts_toc_settings' );
			do_settings_sections( 'kts_toc_settings' );
			submit_button(); ?>

		</form>
	</div> <?php
}
