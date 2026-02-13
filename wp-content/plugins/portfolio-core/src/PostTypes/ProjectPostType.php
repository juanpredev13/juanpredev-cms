<?php
/**
 * Project custom post type.
 *
 * Represents portfolio projects displayed on the Next.js frontend.
 * Exposed in GraphQL as "Project" / "Projects".
 */

declare(strict_types=1);

namespace PortfolioCore\PostTypes;

final class ProjectPostType extends AbstractPostType {

	protected function get_slug(): string {
		return 'project';
	}

	protected function get_graphql_single_name(): string {
		return 'Project';
	}

	protected function get_graphql_plural_name(): string {
		return 'Projects';
	}

	protected function get_labels(): array {
		return [
			'name'               => __( 'Projects', 'portfolio-core' ),
			'singular_name'      => __( 'Project', 'portfolio-core' ),
			'add_new'            => __( 'Add New', 'portfolio-core' ),
			'add_new_item'       => __( 'Add New Project', 'portfolio-core' ),
			'edit_item'          => __( 'Edit Project', 'portfolio-core' ),
			'new_item'           => __( 'New Project', 'portfolio-core' ),
			'view_item'          => __( 'View Project', 'portfolio-core' ),
			'search_items'       => __( 'Search Projects', 'portfolio-core' ),
			'not_found'          => __( 'No projects found', 'portfolio-core' ),
			'not_found_in_trash' => __( 'No projects found in Trash', 'portfolio-core' ),
			'menu_name'          => __( 'Projects', 'portfolio-core' ),
		];
	}

	protected function get_extra_args(): array {
		return [
			'menu_icon' => 'dashicons-portfolio',
		];
	}
}
