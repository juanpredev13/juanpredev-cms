<?php
/**
 * Plugin singleton orchestrator.
 *
 * Boots every module on plugins_loaded. Each module hooks into the
 * appropriate WordPress lifecycle action internally.
 */

declare(strict_types=1);

namespace PortfolioCore;

use PortfolioCore\PostTypes\ProjectPostType;
use PortfolioCore\Fields\ProjectFields;
use PortfolioCore\Admin\ProjectMetaBox;
use PortfolioCore\GraphQL\GraphQLConfig;
use PortfolioCore\Security\Headless;

final class Plugin {

	private static ?self $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->init_modules();
	}

	/**
	 * Instantiate every module. Each one registers its own hooks.
	 */
	private function init_modules(): void {
		new ProjectPostType();
		new ProjectFields();
		new ProjectMetaBox();
		new GraphQLConfig();
		new Headless();
	}
}
