<?php
/*
 *	Plugin Name: GUW MetaBoxes
 *	Plugin URI: http://www.getuwired.us
 *	Description: Adds meta-box on posts/pages
 *	Version: 1.0
 *	Author: Ben Redden
 *	Author URI: http://benjaminredden.we.bs
 *	License: GPL2
 *
*/

/**
 * Adds a meta box to the post editing screen
 */
function guw_addMetaBox() {
	// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
	add_meta_box( 'guw_meta', __( 'GUW ToolTip Configuration', 'guw-textdomain' ), 'guw_meta_callback', 'post' );
}
add_action( 'add_meta_boxes', 'guw_addMetaBox' );

/**
 * Outputs the content of the meta box
 */
function guw_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'guw_nonce' );
	$guw_stored_meta = get_post_meta( $post->ID );
	?>

	<p>
		<label for="meta-text" class="prfx-row-title"><?php _e( 'Word to add tool tip to when hovered', 'guw-textdomain' )?></label><br />
		<input type="text" name="meta-text" id="meta-text" value="<?php if ( isset ( $guw_stored_meta['meta-text'] ) ) echo $guw_stored_meta['meta-text'][0]; ?>" />
	</p>

	<p>
		<label for="meta-textarea" class="prfx-row-title"><?php _e('Content inside of tool tip', 'guw-textdomain') ?></label><br />
		<textarea name='meta-textarea' id='meta-textarea'><?php if (isset ( $guw_stored_meta['meta-textarea'] ) ) echo $guw_stored_meta['meta-textarea'][0]; ?></textarea>
	</p>

	<p>
		<label for="meta-color" class="prfx-row-title"><?php _e( 'Desired tool tip background color', 'prfx-textdomain' )?></label><br />
		<input name="meta-color" type="text" value="<?php if ( isset ( $prfx_stored_meta['meta-color'] ) ) echo $prfx_stored_meta['meta-color'][0]; ?>" class="meta-color" />
	</p>

	<p>
		<label for="meta-image" class="prfx-row-title"><?php _e( 'Upload Image', 'guw-textdomain' )?></label><br />
		<input type="text" name="meta-image" id="meta-image" value="<?php if ( isset ( $guw_stored_meta['meta-image'] ) ) echo $guw_stored_meta['meta-image'][0]; ?>" /><br />
		<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'guw-textdomain' )?>" />
	</p>


	<?php
}

/**
 * Saves the custom meta input
 */
function guw_meta_save( $post_id ) {

	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'guw_nonce' ] ) && wp_verify_nonce( $_POST[ 'guw_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-text' ] ) ) {
		update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'meta-text' ] ) );
	}

	// // Checks for input and saves
	// if( isset( $_POST[ 'meta-checkbox' ] ) ) {
	// 	update_post_meta( $post_id, 'meta-checkbox', 'yes' );
	// } else {
	// 	update_post_meta( $post_id, 'meta-checkbox', '' );
	// }

	// // Checks for input and saves
	// if( isset( $_POST[ 'meta-checkbox-two' ] ) ) {
	// 	update_post_meta( $post_id, 'meta-checkbox-two', 'yes' );
	// } else {
	// 	update_post_meta( $post_id, 'meta-checkbox-two', '' );
	// }

	// // Checks for input and saves if needed
	// if( isset( $_POST[ 'meta-radio' ] ) ) {
	// 	update_post_meta( $post_id, 'meta-radio', $_POST[ 'meta-radio' ] );
	// }

	// // Checks for input and saves if needed
	// if( isset( $_POST[ 'meta-select' ] ) ) {
	// 	update_post_meta( $post_id, 'meta-select', $_POST[ 'meta-select' ] );
	// }

	// Checks for input and saves if needed
	if( isset( $_POST[ 'meta-textarea' ] ) ) {
		update_post_meta( $post_id, 'meta-textarea', $_POST[ 'meta-textarea' ] );
	}

	// Checks for input and saves if needed
	if( isset( $_POST[ 'meta-color' ] ) ) {
		update_post_meta( $post_id, 'meta-color', $_POST[ 'meta-color' ] );
	} else {
		update_post_meta( $post_id, 'meta-color', '#fff');
	}

	// Checks for input and saves if needed
	if( isset( $_POST[ 'meta-image' ] ) ) {
		update_post_meta( $post_id, 'meta-image', $_POST[ 'meta-image' ] );
	}

}
add_action( 'save_post', 'guw_meta_save' );

/**
 * Loads the image management javascript
 */
function guw_image_enqueue() {
	global $typenow;
	if( $typenow == 'post' ) {
		wp_enqueue_media();

		// Registers and enqueues the required javascript.
		wp_register_script( 'meta-box-image', plugin_dir_url( __FILE__ ) . 'meta-box-image.js', array( 'jquery' ) );
		wp_localize_script( 'meta-box-image', 'meta_image',
			array(
				'title' => __( 'Choose or Upload an Image', 'guw-textdomain' ),
				'button' => __( 'Use this image', 'guw-textdomain' ),
			)
		);
		wp_enqueue_script( 'meta-box-image' );
	}
}
add_action( 'admin_enqueue_scripts', 'guw_image_enqueue' );

/**
 * Loads the color picker javascript
 */
function guw_color_enqueue() {
	global $typenow;
	if( $typenow == 'post' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'meta-box-color-js', plugin_dir_url( __FILE__ ) . 'meta-box-color.js', array( 'wp-color-picker' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'guw_color_enqueue' );

/**
 * Search and replace when post is made
 */
function searchAndHover( $content ) {

	// initialise $metaColor so it won't be transparent
	if (empty($metaColor)) {
		$metaColor = '#fff';
	} else {
		$metaColor = get_post_meta( get_the_ID(), 'meta-color', true);
	}

	// search for stuff and replace it with other stuff
	$metaPic = get_post_meta( get_the_ID(), 'meta-image', true);
	$metaTextArea = get_post_meta( get_the_ID(), 'meta-textarea', true);
	$metaText = get_post_meta( get_the_ID(), 'meta-text', true );
	$toBeHovered = $metaText;
	if( $metaPic != '' ) {
		$toBeReplaced = "<div class='guwToolTipWrap'><span class='hoverImgWord'>{$toBeHovered}</span><div style='display:none;background:{$metaColor};' class='guwToolTip'><p>{$metaTextArea}</p><div style='content:\"\";position: absolute;border-style: solid;border-width: 0 15px 15px;border-color: {$metaColor} transparent;display: block;width: 0;z-index: 1;top: -15px;left: 43px;'></div><div class='guwToolTipPic'><img src='{$metaPic}'></div></div></div>";

		$content = str_replace( $toBeHovered, $toBeReplaced , $content );
	} else {
		$toBeReplaced = "<div class='guwToolTipWrap'><span class='hoverImgWord'>{$toBeHovered}</span><div style='display:none;background:{$metaColor};' class='guwToolTip'><p>{$metaTextArea}</p><div style='content:\"\";position: absolute;border-style: solid;border-width: 0 15px 15px;border-color: {$metaColor} transparent;display: block;width: 0;z-index: 1;top: -15px;left: 43px;'></div></div></div>";

		$content = str_replace( $toBeHovered, $toBeReplaced , $content );
	}
	
	return $content;
}
add_filter( 'the_content', 'searchAndHover' ); 

/**
 * adds dynamic stylesheet
 */

function my_styles_method() {
	wp_enqueue_style(
		'guw_metaBoxesStyles',
		plugins_url() . '/wp_guwMetaBoxes/guw_metaBoxesStyles.php'
	);  
}
add_action( 'wp_enqueue_scripts', 'my_styles_method' );

?>