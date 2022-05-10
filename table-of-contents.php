<?php
/**
 * Plugin Name: Table of Contents
 * Description: Creates an accessible table of contents for each post
 * Author: Tim Kaye
 * Author URI: https://timkaye.org
 * Version: 0.3.1
 */

/* USE FILTER TO ADD TOC AND TARGET ANCHORS TO POST CONTENT */
function kts_insert_toc( $content ) {

	# Only run on posts
	if ( ! is_singular( 'post' ) ) {
		return $content;
	}

	# Check if user wants to hide ToC for this post
	$post_id = get_the_ID();
	$post_meta = get_post_meta( $post_id, 'kts_toc_hide', true );
	if ( $post_meta === '1' ) {
		return $content;
	}

	# Parse HTML using PHP's DomDocument
	$dom = new DomDocument();
	libxml_use_internal_errors( true ); // handle HTML5 tags and malformed HTML
	$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
	$dom->preserveWhiteSpace = false;

	# Find all header h2 to h4 nodes
	$finder = new DomXPath( $dom );
	$expression = '( //h2|//h3|//h4	)';
	$nodes = $finder->query( $expression );

	# Don't display empty ToCs
	if ( $nodes->length === 0 ) {
		return $content;
	}

	# Start to build ToC
	$toc = '<details id="toc-container">';
	$toc .= '<summary id="toc-title">Table of Contents</summary>';
	$toc .= '<ul id="toc-list" class="toc-list">';

	# Assign a target ID to each header
	foreach( $nodes as $key => $node ) {

		# Remove HTML entities from anchor
		$anchor = htmlentities( $node->nodeValue, ENT_QUOTES, 'UTF-8' );

		# Convert $anchor to alphanumeric string with dashes and set as ID
		$anchor = sanitize_title_with_dashes( $anchor . '-' . $key );
		$node->setAttribute( 'id', $anchor );

		# Identify which <li> tags to wrap in <ul> tags
		$prefix = '';
		$suffix = '';
		if ( $node->tagName === 'h4' ) {
			if ( $nodes[$key - 1]->tagName !== 'h4' ) {
				$prefix = '<ul class="h4-wrapper">';
			}
			if ( empty( $nodes[$key + 1] ) ) {
				$suffix = '</ul></ul>';
			}
			elseif ( $nodes[$key + 1]->tagName === 'h3' ) {
				$suffix = '</ul>';
			}
			elseif ( $nodes[$key + 1]->tagName === 'h2' ) {
				$suffix = '</ul></ul>';
			}
		}
		elseif ( $node->tagName === 'h3' ) {
			if ( $nodes[$key - 1]->tagName === 'h2' ) {
				$prefix = '<ul class="h3-wrapper">';
			}
			if ( empty( $nodes[$key + 1] ) ) {
				$suffix = '</ul>';
			}
			elseif ( $nodes[$key + 1]->tagName === 'h2' ) {
				$suffix = '</ul>';
			}
		}

		# Render the ToC elements
		$toc .= $prefix . '<li class="' . $node->tagName . '"><a href="#' . $anchor . '">' . sanitize_text_field( $node->nodeValue ) . '</a></li>' . $suffix;
	}

	# Add end tags to ToC
	$toc .= '</ul>';
	$toc .= '</details>';

	# Modify ToC for shortcode
	$new_toc = str_replace( ['<details id="toc-container"><summary id="toc-title">Table of Contents</summary>', '</details>'], ['<nav id="toc-nav-container" class="table-of-contents" aria-labelledby="toc-widget-title">', '</nav>'], $toc );

	# Make shortcode ToC available via JavaScript
	wp_localize_script( 'kts-toc-script', 'TOC', array(
		'toc' => $new_toc
	) );

	# Save DomDocument to variable
	$new_content = $dom->saveHTML( $dom );

	# Prepend ToC to content
	$new_content = $toc . $new_content;

	return $new_content;
}
add_filter( 'the_content', 'kts_insert_toc' );


/* ENABLE SHORTCODES IN WIDGETS */
add_filter( 'widget_text', 'do_shortcode' );


/* SHORTCODE TO CREATE EMPTY DIV TO BE FILLED BY JAVASCRIPT */
function kts_toc_shortcode() {
	return '<div id="kts-toc"></div>';
}
add_shortcode( 'kts_toc', 'kts_toc_shortcode' );


/* ADD ID TO WIDGET TITLE FOR ACCESSIBILITY PURPOSES */
function kts_toc_widget_title( $title, $instance = [], $id_base = '' ) {
	if ( ! empty( $instance['content'] ) && has_shortcode( $instance['content'], 'kts_toc' ) ) {
		$title = '<span id="toc-widget-title">' . $title . '</span>';
	}
	return $title;
}
add_filter( 'widget_title', 'kts_toc_widget_title', 10, 3 );


/* ENQUEUE CSS AND JAVASCRIPT */
function kts_toc_style_script() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	wp_enqueue_style( 'kts-toc-style', plugin_dir_url( __FILE__ ) . 'css/style.css' );
	wp_enqueue_script( 'kts-toc-script', plugin_dir_url( __FILE__ ) . 'js/scripts.js', null, null, true );
}
add_action( 'wp_enqueue_scripts', 'kts_toc_style_script' );


/* ADD META BOX FOR HIDING TOC */
function kts_toc_add_hide_meta_box() {
	add_meta_box(
		'kts_toc_hide_meta_box',
		'Table of Contents',
		'kts_toc_render_hide_meta_box',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'kts_toc_add_hide_meta_box' );


/* RENDER META BOX FOR HIDING TOC */
function kts_toc_render_hide_meta_box( $object, $box ) {
	$meta = get_post_meta( $object->ID, 'kts_toc_hide', true );
	wp_nonce_field( basename( __FILE__ ), 'kts_toc_nonce_hide_meta_box' );
	echo '<p>';
	echo '<input class="widefat" type="checkbox" ' . checked( $meta === '1' ? 1 : 0, 1, false ) . 'name="kts-toc-hide" id="kts-toc-hide" value="1" size="30" /> Do not display';
	echo '</p>';

}


/* SAVE POST META FOR HIDING TOC */
function kts_toc_save_post_meta( $post_id, $post ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
    	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['kts_toc_nonce_hide_meta_box'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['kts_toc_nonce_hide_meta_box'], basename( __FILE__ ) ) ) {
		return;
	}

	$new_meta_value = isset( $_POST['kts-toc-hide'] ) ? sanitize_html_class( $_POST['kts-toc-hide'] ) : '0';

	update_post_meta( $post_id, 'kts_toc_hide', $new_meta_value === '1' ? '1' : '0' );
}
add_action( 'save_post_post', 'kts_toc_save_post_meta', 10, 2 );
