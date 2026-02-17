<?php
/**
 * Meta box for Project custom fields in wp-admin.
 *
 * Renders inputs for project URL, GitHub URL, and tech stack,
 * and handles saving with nonce verification.
 */

declare(strict_types=1);

namespace PortfolioCore\Admin;

final class ProjectMetaBox {

	private const NONCE_ACTION = 'portfolio_project_fields';
	private const NONCE_FIELD  = '_portfolio_project_nonce';

	public function __construct() {
		add_action( 'add_meta_boxes_project', [ $this, 'add_meta_box' ] );
		add_action( 'save_post_project', [ $this, 'save' ] );
	}

	public function add_meta_box(): void {
		add_meta_box(
			'portfolio_project_fields',
			__( 'Project Details', 'portfolio-core' ),
			[ $this, 'render' ],
			'project',
			'normal',
			'high'
		);
	}

	public function render( \WP_Post $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );

		$project_url = get_post_meta( $post->ID, 'project_url', true );
		$github_url  = get_post_meta( $post->ID, 'github_url', true );
		$tech_stack  = get_post_meta( $post->ID, 'tech_stack', true );
		$tech_csv    = is_array( $tech_stack ) ? implode( ', ', $tech_stack ) : '';
		$featured    = (bool) get_post_meta( $post->ID, 'featured', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="featured"><?php esc_html_e( 'Featured', 'portfolio-core' ); ?></label></th>
				<td>
					<input type="checkbox" id="featured" name="featured" value="1"
						   <?php checked( $featured ); ?> />
					<label for="featured"><?php esc_html_e( 'Show on homepage', 'portfolio-core' ); ?></label>
				</td>
			</tr>
			<tr>
				<th><label for="project_url"><?php esc_html_e( 'Project URL', 'portfolio-core' ); ?></label></th>
				<td>
					<input type="url" id="project_url" name="project_url"
						   value="<?php echo esc_url( $project_url ); ?>"
						   class="regular-text" placeholder="https://example.com" />
				</td>
			</tr>
			<tr>
				<th><label for="github_url"><?php esc_html_e( 'GitHub URL', 'portfolio-core' ); ?></label></th>
				<td>
					<input type="url" id="github_url" name="github_url"
						   value="<?php echo esc_url( $github_url ); ?>"
						   class="regular-text" placeholder="https://github.com/user/repo" />
				</td>
			</tr>
			<tr>
				<th><label for="tech_stack"><?php esc_html_e( 'Tech Stack', 'portfolio-core' ); ?></label></th>
				<td>
					<input type="text" id="tech_stack" name="tech_stack"
						   value="<?php echo esc_attr( $tech_csv ); ?>"
						   class="regular-text" placeholder="React, TypeScript, Node.js" />
					<p class="description"><?php esc_html_e( 'Comma-separated list of technologies.', 'portfolio-core' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	public function save( int $post_id ): void {
		if ( ! isset( $_POST[ self::NONCE_FIELD ] )
			|| ! wp_verify_nonce( $_POST[ self::NONCE_FIELD ], self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Featured.
		update_post_meta( $post_id, 'featured', ! empty( $_POST['featured'] ) );

		// Project URL.
		if ( isset( $_POST['project_url'] ) ) {
			update_post_meta( $post_id, 'project_url', esc_url_raw( $_POST['project_url'] ) );
		}

		// GitHub URL.
		if ( isset( $_POST['github_url'] ) ) {
			update_post_meta( $post_id, 'github_url', esc_url_raw( $_POST['github_url'] ) );
		}

		// Tech stack — convert CSV to array.
		if ( isset( $_POST['tech_stack'] ) ) {
			$raw   = sanitize_text_field( $_POST['tech_stack'] );
			$items = array_values( array_filter( array_map( 'trim', explode( ',', $raw ) ) ) );
			update_post_meta( $post_id, 'tech_stack', $items );
		}
	}
}
