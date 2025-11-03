<?php

declare(strict_types=1);

namespace BuddyBossTheme\Admin\Mothership;

use BuddyBossTheme\GroundLevel\Container\Container;
use BuddyBossTheme\GroundLevel\Mothership\Manager\AddonsManager;
use BuddyBossTheme\GroundLevel\Mothership\Service as MothershipService;
use BuddyBossTheme\GroundLevel\InProductNotifications\Service as IPNService;

/**
 * Main loader class for BuddyBoss Mothership functionality.
 *
 * This class follows the GroundLevel framework patterns for service registration,
 * container awareness, and hook configuration.
 */
class BB_Theme_Mothership_Loader {

	/**
	 * Container for dependency injection.
	 *
	 * @var Container
	 */
	private $container;

	/**
	 * Plugin connector instance.
	 *
	 * @var \BuddyBossTheme\Admin\Mothership\BB_Theme_Connector
	 */
	private $pluginConnector;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize the mothership functionality.
	 */
	private function init(): void {
		// Create the container.
		$this->container = new Container();

		// Create the plugin connector.
		$this->pluginConnector = new \BuddyBossTheme\Admin\Mothership\BB_Theme_Connector();

		// Initialize the mothership service.
		$this->initMothershipService();

		$this->initIPNService();

		// Set up hooks.
		$this->setupHooks();
	}

	/**
	 * Initialize the mothership service.
	 */
	private function initMothershipService(): void {
		// Create the mothership service.
		$mothershipService = new MothershipService( $this->container, $this->pluginConnector );

		// Load the mothership service dependencies.
		$mothershipService->load( $this->container );

		// Register the mothership service in the container.
		$this->container->addService(
			MothershipService::class,
			function () use ( $mothershipService ) {
				return $mothershipService;
			},
			true // Singleton
		);
	}

	private function initIPNService(): void {
		$plugin_id = $this->pluginConnector->getDynamicPluginId();

		// Set IPN Service parameters.
		$this->container->addParameter( IPNService::PRODUCT_SLUG, $plugin_id );
		$this->container->addParameter( IPNService::PREFIX, sanitize_title( $plugin_id ) );
		$this->container->addParameter( IPNService::MENU_SLUG, 'buddyboss-settings' );

		$this->container->addParameter(
			IPNService::RENDER_HOOK,
			'buddyboss_theme_admin_header_actions'
		);
		$this->container->addParameter(
			IPNService::THEME,
			array(
				'primaryColor'       => '#2271b1',
				'primaryColorDarker' => '#0a4b78',
			)
		);

		$this->container->addService(
			IPNService::class,
			static function ( Container $container ): IPNService {
				return new IPNService( $container );
			},
			true
		);
	}

	/**
	 * Setup WordPress hooks.
	 */
	private function setupHooks(): void {
		if ( is_admin() ) {
			// Register admin pages.
			add_action( 'admin_menu', array( $this, 'registerAdminPages' ), 99 );

			// Register license controller using BuddyBoss Theme custom manager.
			add_action( 'admin_init', array( \BuddyBossTheme\Admin\Mothership\BB_Theme_License_Manager::class, 'controller' ), 20 );

			// Register addons functionality using BuddyBoss Theme custom manager.
			AddonsManager::loadHooks();
		}

		$plugin_id = $this->pluginConnector->getDynamicPluginId();

		// Handle license status changes.
		add_action( $plugin_id . '_license_status_changed', array( $this, 'handleLicenseStatusChange' ), 10, 2 );

		// For local development - disable SSL verification if needed.
		if ( defined( 'BUDDYBOSS_DISABLE_SSL_VERIFY' ) && constant( 'BUDDYBOSS_DISABLE_SSL_VERIFY' ) ) {
			add_filter( 'https_ssl_verify', '__return_false' );
		}

		add_action( 'in_admin_header', array( $this, 'bb_render_admin_header' ), 999 );
	}

	/**
	 * Register admin pages.
	 */
	public function registerAdminPages(): void {
		// Only show to users with manage_options capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Register License page.
		\BuddyBossTheme\Admin\Mothership\BB_Theme_License_Page::register();
	}

	/**
	 * Handle license status changes.
	 *
	 * @param bool  $isActive License active status.
	 * @param mixed $response API response.
	 */
	public function handleLicenseStatusChange( bool $isActive, $response ): void {
		$plugin_id = $this->pluginConnector->getDynamicPluginId();

		if ( ! $isActive ) {
			// License is no longer active.
			$this->pluginConnector->updateLicenseActivationStatus( false );

			// Clear cached data.
			delete_transient( $plugin_id . '-mosh-products' );
			delete_transient( $plugin_id . '-mosh-addons-update-check' );

			// Log the deactivation.
			error_log( 'BuddyBoss license deactivated: ' . print_r( $response, true ) );
		} else {
			// License is active - ensure status is updated.
			$this->pluginConnector->updateLicenseActivationStatus( true );
		}
	}

	/**
	 * Get the container.
	 *
	 * @return Container The container instance.
	 */
	public function getContainer(): Container {
		return $this->container;
	}

	/**
	 * Refresh the plugin connector with updated plugin ID.
	 * This should be called after the plugin ID changes.
	 */
	public function refreshPluginConnector(): void {
		// The plugin connector will automatically use the updated plugin ID
		// from the database option on the next request.
	}

	public function bb_render_admin_header(): void {
		$screen = get_current_screen();

		if (
			(
				! empty( $screen->base ) &&
				(
					false !== strpos( $screen->base, 'buddyboss' ) ||
					false !== strpos( $screen->base, 'bp_' ) ||
					false !== strpos( $screen->base, 'bb_' )
				) &&
				(
					! empty( $screen->id ) &&
					(
						'buddyboss_page_bb-upgrade' !== $screen->id &&
						'buddyboss_page_bb-readylaunch' !== $screen->id
					)
				)
			) ||
			(
				! empty( $screen->post_type ) &&
				'buddyboss_fonts' === $screen->post_type
			)
		) {
			include __DIR__ . '/views/header.php';
		}
	}
}
