<?php 

/**
 * Backend template for LQC
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class BRC_Frontend
 */
class BRC_Frontend {

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof BRC_Frontend ) ) {
            self::$instance = new self;

            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * Define hooks
     */
    private function hooks() {

        add_action( 'wp_enqueue_scripts', [ $this, 'brc_Frontend_scripts' ] );
        add_filter( 'bp_nouveau_get_activity_entry_buttons', [ $this, 'brc_filter_like_comment_btn_text'], 10, 2 );
        add_action( 'wp_ajax_brc_update_reaction_count', [ $this, 'brc_update_reaction_count' ] );
    }

    /**
     * Update reaction count
     */
    public function brc_update_reaction_count() {

        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'brc_ajax_nonce')) {
            wp_send_json_error(['message' => 'Security check failed.'], 403);
        }

        $activity_id = isset( $_POST['activity_id'] ) ? intval( $_POST['activity_id'] ) : 0;
        $fav_count = self::brb_reaction_count_for_activity( $activity_id );

        wp_send_json_success( 
            [
                'success'   => 'true',
                'count'     => $fav_count
            ],
            200
        );
    }
    
    /**
	 * Filter reaction action buttons
	 * 
	 * @param $buttons
	 * @param $activity_id
	 */
	public function brc_filter_like_comment_btn_text( $buttons, $activity_id ) {

		$brc_likes_count = get_option( 'brc_likes_count' );
        $brc_comments_count = get_option( 'brc_comments_count' );

		$fav_count = self::brb_reaction_count_for_activity($activity_id);
		if( empty( $fav_count ) ) {
			$fav_count = 0;
		}

		if( 'on' == $brc_likes_count ) {
			$buttons['activity_favorite']['link_text'] .= ' <span style="color: #000;" class="brb-like-count"> ('.$fav_count.')</span>';
		}

		return $buttons;
	}

    /**
     * Get the reaction count for a specific activity
     *
     * @param int $activity_id The ID of the activity
     * @return int The reaction count
     */
    public function brb_reaction_count_for_activity($activity_id) {

        $total = 0;
        $reactions = bb_get_activity_most_reactions( $activity_id, 'activity' );

        foreach ( $reactions as $reaction ) {
            $total += isset( $reaction['count'] ) ? $reaction['count'] : 0;
        }

        return $total;
    }

    /**
     *  Backend Enqueue script
     */
    public function brc_Frontend_scripts() {

    	$brc_likes_count = get_option( 'brc_likes_count' );
        $brc_comments_count = get_option( 'brc_comments_count' );

        $random_number = rand( 329423, 39284932 );

        wp_enqueue_script( 'BRC_Frontend', BRC_ASSETS_URL.'js/frontend.js', [ 'jquery' ], $random_number, true );

        wp_localize_script( 'BRC_Frontend', 'BRC', 
            [
                'ajax_url' 		=> admin_url( 'admin-ajax.php' ),
                'nonce'         => wp_create_nonce('brc_ajax_nonce'),
                'likes_count'	=> $brc_likes_count,
                'comments_count'=> $brc_comments_count
            ] 
        );
    }
}

BRC_Frontend::instance();