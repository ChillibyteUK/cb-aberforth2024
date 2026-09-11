<?php
/**
 * Block usage shortcode for debugging/QA — [block_usage_table].
 *
 * Lists every block file in /blocks against the published content (across
 * all public post types, not just pages/posts) that actually uses it, so
 * you can tell at a glance whether a block is safe to remove with
 * rm_block.sh.
 *
 * @package cb-aberforth2024
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders a table of all blocks and the content that uses them.
 *
 * @return string HTML table of block usage.
 */
function cb_global42026_block_usage_table_shortcode() {
	// This theme registers all its blocks via acf_register_block_type() calls
	// in inc/cb-blocks.php rather than one file per block, and the 'name'
	// passed there doesn't always match its render_template's filename (e.g.
	// 'cb_about_cards' renders cb_three_cards.php), so the registered names
	// are read directly from that file instead of being derived from the
	// filesystem.
	$blocks_file = get_stylesheet_directory() . '/inc/cb-blocks.php';

	if ( ! file_exists( $blocks_file ) ) {
		return '<p>No blocks found.</p>';
	}

	preg_match_all(
		"/acf_register_block_type\s*\(\s*array\s*\(\s*'name'\s*=>\s*'([a-z0-9_-]+)'/",
		file_get_contents( $blocks_file ),
		$registered
	);

	if ( empty( $registered[1] ) ) {
		return '<p>No blocks found.</p>';
	}

	// acf_register_block_type() runs 'name' through acf_slugify(), which
	// replaces underscores with hyphens, so the block comment stored in post
	// content ends up as the hyphenated form (e.g. wp:acf/cb-home-hero).
	$block_names = array();
	foreach ( $registered[1] as $name ) {
		$block_names[] = str_replace( '_', '-', $name );
	}

	$posts = get_posts(
		array(
			'post_type'      => get_post_types( array( 'public' => true ), 'names' ),
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);

	$usage_map = array_fill_keys( $block_names, array() );

	foreach ( $posts as $post ) {
		preg_match_all( '/<!-- wp:acf\/([a-z0-9-]+)\s/', $post->post_content, $matches );

		if ( empty( $matches[1] ) ) {
			continue;
		}

		foreach ( array_unique( $matches[1] ) as $found_block ) {
			if ( isset( $usage_map[ $found_block ] ) ) {
				$usage_map[ $found_block ][] = $post;
			}
		}
	}

	// Inline-styled on purpose — this is a standalone QA utility that should
	// look reasonable on any project regardless of whether it has opted
	// into src/css/tables.css.
	ob_start();
	?>
	<div style="padding: 2rem;">
	<table style="width: 100%; border-collapse: collapse;">
		<thead>
			<tr style="border-bottom: 2px solid #ccc;">
				<th style="text-align: left; padding: 8px; font-weight: bold;">Block Name</th>
				<th style="text-align: left; padding: 8px; font-weight: bold;">Used In</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $usage_map as $block_name => $posts_using_block ) : ?>
				<tr style="border-bottom: 1px solid #eee;">
					<td style="padding: 8px; vertical-align: top;"><?php echo esc_html( $block_name ); ?></td>
					<td style="padding: 8px;">
						<?php if ( empty( $posts_using_block ) ) : ?>
							<em style="color: #999;">Not used</em>
						<?php else : ?>
							<ul style="margin: 0; padding-left: 20px;">
								<?php foreach ( $posts_using_block as $post ) : ?>
									<li>
										<a href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>" target="_blank">
											<?php echo esc_html( $post->post_title ); ?>
										</a>
										<span style="color: #999; font-size: 0.9em;">(<?php echo esc_html( ucfirst( $post->post_type ) ); ?>)</span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'block_usage_table', 'cb_global42026_block_usage_table_shortcode' );
