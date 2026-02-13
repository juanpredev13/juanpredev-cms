<?php
/**
 * Headless security hardening.
 *
 * - Disables XML-RPC entirely.
 * - Disables comments site-wide.
 * - Redirects frontend (non-admin, non-API) requests to wp-admin.
 * - Strips unnecessary default REST API endpoints.
 */

declare(strict_types=1);

namespace PortfolioCore\Security;

final class Headless {

	public function __construct() {
		// Disable XML-RPC.
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'xmlrpc_methods', [ $this, 'remove_xmlrpc_methods' ] );

		// Disable comments globally.
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );
		add_action( 'admin_menu', [ $this, 'remove_comments_menu' ] );

		// Redirect frontend requests to wp-admin.
		add_action( 'template_redirect', [ $this, 'redirect_frontend' ] );

		// Strip default REST endpoints that are not needed.
		add_filter( 'rest_endpoints', [ $this, 'strip_rest_endpoints' ] );
	}

	/**
	 * Remove all XML-RPC methods to fully disable the service.
	 */
	public function remove_xmlrpc_methods(): array {
		return [];
	}

	/**
	 * Hide the Comments menu item from wp-admin.
	 */
	public function remove_comments_menu(): void {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Redirect any frontend (theme) request to wp-admin.
	 * Allows /wp-admin, /graphql, and /wp-json to pass through.
	 */
	public function redirect_frontend(): void {
		// Allow AJAX, cron, REST, and CLI requests.
		if (
			wp_doing_ajax()
			|| wp_doing_cron()
			|| defined( 'REST_REQUEST' )
			|| ( defined( 'WP_CLI' ) && WP_CLI )
		) {
			return;
		}

		wp_safe_redirect( admin_url(), 302 );
		exit;
	}

	/**
	 * Remove REST endpoints that leak data and are unused in headless mode.
	 *
	 * Keeps: wp/v2 content endpoints and wpgraphql routes.
	 * Removes: users, comments, and settings.
	 *
	 * @param array<string, mixed> $endpoints Registered REST endpoints.
	 * @return array<string, mixed>
	 */
	public function strip_rest_endpoints( array $endpoints ): array {
		$prefixes_to_remove = [
			'/wp/v2/users',
			'/wp/v2/comments',
			'/wp/v2/settings',
		];

		foreach ( $endpoints as $route => $details ) {
			foreach ( $prefixes_to_remove as $prefix ) {
				if ( str_starts_with( $route, $prefix ) ) {
					unset( $endpoints[ $route ] );
					break;
				}
			}
		}

		return $endpoints;
	}
}
