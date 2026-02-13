<?php
/**
 * Base class for every custom post type.
 *
 * Enforces GraphQL exposure and common defaults so child classes
 * only need to define their unique properties.
 */

declare(strict_types=1);

namespace PortfolioCore\PostTypes;

abstract class AbstractPostType {

	public function __construct() {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Post type slug (e.g. "project").
	 */
	abstract protected function get_slug(): string;

	/**
	 * Human-readable labels array.
	 */
	abstract protected function get_labels(): array;

	/**
	 * GraphQL singular name (PascalCase, e.g. "Project").
	 */
	abstract protected function get_graphql_single_name(): string;

	/**
	 * GraphQL plural name (PascalCase, e.g. "Projects").
	 */
	abstract protected function get_graphql_plural_name(): string;

	/**
	 * Override in child classes to add or change args.
	 */
	protected function get_extra_args(): array {
		return [];
	}

	/**
	 * Register the CPT with WordPress. Shared defaults live here.
	 */
	public function register(): void {
		$defaults = [
			'labels'              => $this->get_labels(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_rest'        => false, // REST disabled; we use GraphQL.
			'show_in_graphql'     => true,
			'graphql_single_name' => $this->get_graphql_single_name(),
			'graphql_plural_name' => $this->get_graphql_plural_name(),
			'supports'            => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
			'has_archive'         => false,
			'rewrite'             => [ 'slug' => $this->get_slug() ],
			'menu_position'       => 20,
		];

		$args = array_merge( $defaults, $this->get_extra_args() );

		register_post_type( $this->get_slug(), $args );
	}
}
