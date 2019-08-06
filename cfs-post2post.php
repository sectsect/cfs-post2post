<?php
/**
Plugin Name: CFS Post-2-Post
Plugin URI: https://github.com/sectsect/cfs-post2post
Description: Two way Relationship Fields for Custom Field Suite
Author: SECT INTERACTIVE AGENCY
Version: 1.0.1
Author URI: https://www.ilovesect.com/
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) || ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cfs-post2post-activator.php
 */
function activate_cfs_post2post() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cfs-post2post-activator.php';
	Cfs_Post2post_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cfs-post2post-deactivator.php
 */
function deactivate_cfs_post2post() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cfs-post2post-deactivator.php';
	Cfs_Post2post_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cfs_post2post' );
register_deactivation_hook( __FILE__, 'deactivate_cfs_post2post' );

define( 'CFS_P2P_OVERWRITE_TYPE', get_option( 'cfs_p2p_overwrite_type' ) );

require_once plugin_dir_path( __FILE__ ) . 'functions/admin.php';

if ( ! class_exists( 'CFS_Post2Post' ) ) {
	class CFS_Post2Post {

		function __construct() {
			add_action( 'cfs_pre_save_input', array( $this, 'get_the_old_values' ) );
			add_action( 'cfs_after_save_input', array( $this, 'modify_relationship' ) );
		}

		/*
		==================================================
			Get the values for relationship before saving
		================================================== */
		public function get_the_old_values( $params ) {
			global $old_values;
			$old_values = array();

			$post_id = $params['post_data']['ID'];
			$fields  = CFS()->find_fields(
				array(
					'post_id'    => $post_id,
					'field_type' => 'relationship',
				)
			);
			foreach ( $fields as $field ) {
				$field_name   = $field['name'];
				$relation_ids = CFS()->get( $field_name, $post_id );

				// Set the old valer to global
				$old_values[ $field_name ] = get_post_meta( $post_id, $field_name );
			}
		}

		function save_the_data( $field_data, $field_name, $post_id, $relation_id ) {
			$field_data[ $field_name ][] = (string) $post_id;      // Push to the array
			$post_data                   = array( 'ID' => $relation_id );
			CFS()->save( $field_data, $post_data );
		}

		function remove_the_reverse_data( $field_name, $relation_id, $removed_id ) {
			$removed_reverse_values = CFS()->get( $field_name, $removed_id );
			// Remove the value
			$new_removed_reverse_values = array_diff( $removed_reverse_values, array( $relation_id ) );
			// Reindex array key
			$new_removed_reverse_values        = array_values( $new_removed_reverse_values );
			$reverse_field_data[ $field_name ] = $new_removed_reverse_values;
			$reverse_post_data                 = array( 'ID' => $removed_id );
			CFS()->save( $reverse_field_data, $reverse_post_data );
		}

		/*
		==================================================
			Add & Remove Relations
		================================================== */
		function modify_relationship( $params ) {
			global $old_values;

			$post_id = $params['post_data']['ID'];
			$fields  = CFS()->find_fields(
				array(
					'post_id'    => $post_id,
					'field_type' => 'relationship',
				)
			);

			foreach ( $fields as $field ) {
				$field_name       = $field['name'];
				$relation_ids     = CFS()->get( $field_name, $post_id );
				$old_relation_ids = $old_values[ $field_name ];

				// In case Relation Added
				foreach ( $relation_ids as $relation_id ) {
					// Get the value
					$the_relation_values = CFS()->get( $field_name, $relation_id );

					// Get the count for $the_relation_values
					$count = count( $the_relation_values );

					// Get limit-max for the field
					$props     = CFS()->get_field_info( $field_name, $relation_id );
					$limit_max = $props['options']['limit_max'];

					// Add data to the post
					// if (!in_array($post_id, $the_relation_values) && $count < $limit_max && $post_id != $relation_id) {
					// $key = array($field_name);
					// $field_data = array_fill_keys($key, $the_relation_values);
					// $field_data[$field_name][] = (string)$post_id;		// Push to the array
					// $post_data = array( 'ID' => $relation_id );
					// CFS()->save( $field_data, $post_data );
					// }

					$field_data = array();

					if ( ! in_array( $post_id, $the_relation_values, true ) && $post_id !== $relation_id ) {
						if ( ! empty( $the_relation_values ) ) {
							$field_data[ $field_name ] = $the_relation_values;
						} else {
							$field_data[ $field_name ] = array();
						}

						if ( $limit_max && $count >= $limit_max ) {
							if ( CFS_P2P_OVERWRITE_TYPE === 'first' || CFS_P2P_OVERWRITE_TYPE === 'last' ) {
								if ( CFS_P2P_OVERWRITE_TYPE === 'first' ) {
									$removed_id = array_shift( $field_data[ $field_name ] );        // Remove the first element
								} elseif ( CFS_P2P_OVERWRITE_TYPE === 'last' ) {
									$removed_id = array_pop( $field_data[ $field_name ] );          // Remove the last element
								}
								$this->remove_the_reverse_data( $field_name, $relation_id, $removed_id );     // Remove the Reverse Data
								$this->save_the_data( $field_data, $field_name, $post_id, $relation_id );     // Overwrite
							}
						} else {
							$this->save_the_data( $field_data, $field_name, $post_id, $relation_id );
						}
					}
				}

				// In case Relation Removed
				// Get the removed value
				if ( $relation_ids ) {
					$removed_ids = array_diff( $old_relation_ids, $relation_ids );
				} else {
					$removed_ids = $old_relation_ids;
				}

				if ( ! empty( $removed_ids ) ) {
					foreach ( $removed_ids as $removed_id ) {
						// Get the value
						$the_relation_values = CFS()->get( $field_name, $removed_id );
						// Remove the value
						$the_removed_values = array_diff( $the_relation_values, array( $post_id ) );
						// Reindex array key
						$the_removed_values = array_values( $the_removed_values );

						$field_data[ $field_name ] = $the_removed_values;     // Push to the array
						$post_data                 = array( 'ID' => $removed_id );
						CFS()->save( $field_data, $post_data );
					}
				}
			}

		}
	}
	new CFS_Post2Post();
}
