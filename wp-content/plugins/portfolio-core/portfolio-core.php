<?php
/**
 * Plugin Name: Portfolio Core
 * Description: Core plugin for JuanPreDev headless WordPress portfolio. Registers CPTs, configures GraphQL, and enforces headless security.
 * Version:     1.0.0
 * Author:      JuanPreDev
 * Requires PHP: 8.2
 * Text Domain: portfolio-core
 */

declare(strict_types=1);

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PORTFOLIO_CORE_VERSION', '1.0.0' );
define( 'PORTFOLIO_CORE_PATH', plugin_dir_path( __FILE__ ) );

// Composer autoloader.
$autoloader = PORTFOLIO_CORE_PATH . 'vendor/autoload.php';
if ( file_exists( $autoloader ) ) {
	require_once $autoloader;
}

// Boot the plugin on plugins_loaded to ensure WP + WPGraphQL are ready.
add_action( 'plugins_loaded', static function (): void {
	\PortfolioCore\Plugin::get_instance();
} );
