<?php
/**
 * Register custom meta fields for the Project CPT.
 *
 * Registers post meta on `init` and explicitly exposes them
 * to WPGraphQL via `register_graphql_field()` on `graphql_register_types`.
 */

declare(strict_types=1);

namespace PortfolioCore\Fields;

final class ProjectFields {

	public function __construct() {
		add_action( 'init', [ $this, 'register_meta' ] );
		add_action( 'graphql_register_types', [ $this, 'register_graphql_fields' ] );
	}

	public function register_meta(): void {
		register_post_meta( 'project', 'project_url', [
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => true,
		] );

		register_post_meta( 'project', 'github_url', [
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => true,
		] );

		register_post_meta( 'project', 'featured', [
			'type'              => 'boolean',
			'single'            => true,
			'show_in_rest'      => true,
			'default'           => false,
		] );

		register_post_meta( 'project', 'tech_stack', [
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => [
				'schema' => [
					'type'  => 'array',
					'items' => [ 'type' => 'string' ],
				],
			],
			'sanitize_callback' => [ $this, 'sanitize_tech_stack' ],
			'default'           => [],
		] );
	}

	public function register_graphql_fields(): void {
		register_graphql_field( 'Project', 'projectUrl', [
			'type'        => 'String',
			'description' => __( 'Live project URL', 'portfolio-core' ),
			'resolve'     => fn( $post ) => get_post_meta( $post->databaseId, 'project_url', true ) ?: null,
		] );

		register_graphql_field( 'Project', 'githubUrl', [
			'type'        => 'String',
			'description' => __( 'GitHub repository URL', 'portfolio-core' ),
			'resolve'     => fn( $post ) => get_post_meta( $post->databaseId, 'github_url', true ) ?: null,
		] );

		register_graphql_field( 'Project', 'featured', [
			'type'        => 'Boolean',
			'description' => __( 'Whether this project is featured', 'portfolio-core' ),
			'resolve'     => fn( $post ) => (bool) get_post_meta( $post->databaseId, 'featured', true ),
		] );

		register_graphql_field( 'Project', 'techStack', [
			'type'        => [ 'list_of' => 'String' ],
			'description' => __( 'List of technologies used', 'portfolio-core' ),
			'resolve'     => function ( $post ) {
				$value = get_post_meta( $post->databaseId, 'tech_stack', true );
				return is_array( $value ) && ! empty( $value ) ? $value : null;
			},
		] );
	}

	/**
	 * Sanitize tech_stack: ensure every item is a trimmed string.
	 *
	 * @param mixed $value Raw input.
	 * @return string[]
	 */
	public function sanitize_tech_stack( mixed $value ): array {
		if ( ! is_array( $value ) ) {
			return [];
		}

		return array_values( array_filter( array_map(
			fn( $item ) => sanitize_text_field( (string) $item ),
			$value
		) ) );
	}
}
