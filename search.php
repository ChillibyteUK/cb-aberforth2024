<?php

/**
 * The template for displaying search results pages
 *
 * @package cb-carousel
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

$title = sprintf(esc_html__('Search Results for: %s', 'understrap'), '<span>' . get_search_query() . '</span>');
$theme = 'default';
?>
<main id="main">
	<!-- page_title -->
	<div class="page_title page_title--<?= $theme ?>">
		<div class="overlay"></div>
		<div class="container-xl">
			<h1 class="has-<?= $theme ?>-color lined"><?= $title ?></h1>
			<?php
			if (get_field('breadcrumb')) {
				if (function_exists('yoast_breadcrumb')) {
					yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
				}
			}
			?>
		</div>
	</div>
	<div class="container-xl">
		<?php

		if (have_posts()) {
			while (have_posts()) {
				the_post();
				if (get_post_type() == 'document') {
					$link = get_field('file');
					$attachment_url = wp_get_attachment_url(get_field('file', get_the_ID()));
		?>
					<div class="border-bottom pb-3 mb-2">
						<a href="<?php echo $attachment_url; ?>" class="w-100" download>
							<div class="fs-300"><?= esc_html(get_the_terms(get_the_ID(), 'doccat')[0]->name ?? ''); ?></div>
							<div class="d-flex justify-content-between">
								<h2 class="h4 pt-3"><?php the_title() ?></h2>
								<div class="icon-download"></div>
							</div>
						</a>
					</div>
				<?php
				} else {
				?>
					<div class="border-bottom pb-3 mb-2">
						<a href="<?= esc_url(get_permalink()) ?>" rel="bookmark" class="noline">
							<h2 class="h4 pt-3" style="text-transform:initial"><?= get_the_title() ?></h2>
							<?php the_excerpt() ?>
						</a>
					</div>
			<?php
				}
			}
			?>
			<?php understrap_pagination(); ?>
	</div>
	</div>
<?php
		} else {
?>
	<div class="container py-5">
		<?php
			get_template_part('loop-templates/content', 'none');
		?>
	</div>
<?php
		}

		echo '</div></main>';

		get_footer();
