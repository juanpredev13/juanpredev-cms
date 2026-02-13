<?php
/**
 * GraphQL configuration: CORS headers and debug toggle.
 *
 * CORS origin is read from the GRAPHQL_CORS_ORIGIN environment variable
 * so it can differ between DDEV, staging, and production (Vercel).
 * Falls back to '*' in development if unset.
 */

declare(strict_types=1);

namespace PortfolioCore\GraphQL;

final class GraphQLConfig {

	public function __construct() {
		add_action( 'graphql_response_headers_to_send', [ $this, 'cors_headers' ] );
		add_filter( 'graphql_debug_enabled', [ $this, 'debug_enabled' ] );
	}

	/**
	 * Set CORS headers for the GraphQL endpoint.
	 *
	 * @param array<string, string> $headers Existing headers.
	 * @return array<string, string>
	 */
	public function cors_headers( array $headers ): array {
		$origin = getenv( 'GRAPHQL_CORS_ORIGIN' ) ?: '*';

		$headers['Access-Control-Allow-Origin']  = $origin;
		$headers['Access-Control-Allow-Headers'] = 'Authorization, Content-Type';
		$headers['Access-Control-Allow-Methods'] = 'POST, GET, OPTIONS';

		return $headers;
	}

	/**
	 * Enable GraphQL debug info only in non-production environments.
	 */
	public function debug_enabled(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}
}
