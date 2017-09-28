<?php
defined( 'ABSPATH' ) or die; // exit if accessed directly

/**
 * Class used to hook into WordPress and make it use Sermon Manager dates, instead of core dates
 *
 * Can be disabled by `add_filter('sm_dates_wp', '__return_false');`
 *
 * @since 2.6
 */
class SM_Dates_WP extends SM_Dates {
	/**
	 * Filters WordPress internal function `get_the_date()`
	 *
	 * @param string      $the_date The formatted date.
	 * @param string      $d        PHP date format. Defaults to 'date_format' option
	 *                              if not specified.
	 * @param int|WP_Post $post     The post object or ID.
	 *
	 * @return string Preached date
	 */
	public static function get_the_date( $the_date = '', $d = '', $post = null ) {
		$sm_date = SM_Dates::get( $d, $post );

		return $sm_date === false ? $the_date : $sm_date;
	}

	/**
	 * Hooks into WordPress filtering functions
	 *
	 * @since 2.6
	 *
	 * @return void
	 */
	public static function hook() {
		add_action( 'save_post_wpfc_sermon', array( get_class(), 'maybe_update_date' ), 10, 3 );
		add_action( 'pre_post_update', array( get_class(), 'get_original_date' ) );
		add_filter( 'cmb2_override_sermon_date_meta_remove', '__return_true' );

		/**
		 * Exit if disabled
		 */
		if ( apply_filters( 'sm_dates_wp', true ) === false ) {
			return;
		}

		add_filter( 'get_the_date', array( get_class(), 'get_the_date' ), 10, 3 );
	}

	/**
	 * If post that is being updated is sermon, then it saves post date to globals
	 *
	 * @param int $post_ID Post ID.
	 *
	 * @since 2.7
	 */
	public static function get_original_date( $post_ID ) {
		if ( get_post_type( $post_ID ) === 'wpfc_sermon' ) {
			$post                                  = get_post( $post_ID );
			$GLOBALS['sm_original_published_date'] = $post->post_date;
			$GLOBALS['sm_original_sermon_date']    = get_post_meta( $post_ID, 'sermon_date', true );
		}
	}

	/**
	 * Sets/updates date for posts if they are not user-defined
	 *
	 * @param int     $post_ID Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated or not.
	 *
	 * @since 2.7
	 */
	public static function maybe_update_date( $post_ID, $post, $update ) {
		$update_date = $auto = false;

		if ( $update ) {
			// compare sermon date and if user changed it update sermon date and disable auto update
			if ( ! empty( $GLOBALS['sm_original_sermon_date'] ) && ! empty( $_POST['sermon_date'] ) ) {
				$dt = DateTime::createFromFormat( SermonManager::getOption( 'date_format' ) ?: 'm/d/Y', $_POST['sermon_date'] );
				if ( $dt instanceof DateTime && $dt->format( 'U' ) != $GLOBALS['sm_original_sermon_date'] ) {
					update_post_meta( $post_ID, 'sermon_date_auto', 0 );
				}
			}

			// compare published date and if user changed it update sermon date if auto update is set
			if ( ! empty( $GLOBALS['sm_original_published_date'] ) ) {
				if ( $post->post_date !== $GLOBALS['sm_original_published_date'] &&
				     get_post_meta( $post_ID, 'sermon_date_auto', true ) == 1 ) {
					$update_date = true;
				}
			}
		}

		// if sermon date is blank (not set on sermon create or removed later on update), mark
		// this post for auto updating and update date now
		if ( isset( $_POST['sermon_date'] ) && $_POST['sermon_date'] == '' ) {
			$update_date = true;
			$auto        = true;
		}

		// if marked for date updating
		if ( $update_date ) {
			update_post_meta( $post_ID, 'sermon_date', mysql2date( 'U', $post->post_date ) );
			add_filter( 'cmb2_override_sermon_date_meta_save', '__return_true' );
			add_filter( 'cmb2_override_sermon_date_meta_remove', '__return_true' );
		}

		// if we should set it for auto date updating
		if ( $auto ) {
			update_post_meta( $post_ID, 'sermon_date_auto', '1' );
		}
	}
}