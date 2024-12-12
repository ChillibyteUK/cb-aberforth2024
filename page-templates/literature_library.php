<?php

/**
 * Template Name: Literature Library
 */

get_header();

// Handle the custom search and pagination manually.
$paged = (get_query_var('paged')) ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);

// Setup base arguments for WP_Query.
$args = array(
    'post_type'      => 'document',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'tax_query'      => array(
        'relation' => 'AND',
    ),
);

// Apply search parameter manually (handled via GET).
if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
    $args['s'] = sanitize_text_field($_GET['search_term']);
}

// Apply filters for doccat and doctype if set.
if (isset($_GET['doccat']) && is_array($_GET['doccat']) && !empty($_GET['doccat'])) {
    $args['tax_query'][] = array(
        'taxonomy' => 'doccat',
        'field'    => 'slug',
        'terms'    => array_map('sanitize_text_field', $_GET['doccat']),
        'operator' => 'IN',
    );
}

if (isset($_GET['doctype']) && is_array($_GET['doctype']) && !empty($_GET['doctype'])) {
    $args['tax_query'][] = array(
        'taxonomy' => 'doctype',
        'field'    => 'slug',
        'terms'    => array_map('sanitize_text_field', $_GET['doctype']),
        'operator' => 'IN',
    );
}

// Execute the query.
$query = new WP_Query($args);

$total_posts = $query->found_posts;
$start_post = ($paged - 1) * $args['posts_per_page'] + 1;
$end_post = min($paged * $args['posts_per_page'], $total_posts);

?>
<main id="main" class="literature_library">
    <div class="container-xl pb-4">
        <h1>Literature Library</h1>
        <p class="mb-4">All of the documents pertaining to Aberforth Partners and each of the trusts we manage can be found in our library. Find a document by search, filter and or sorting. If you have any difficulties locating or downloading the document you require, please <a href="/contact-us/">contact us</a>.</p>
        <!-- Search form -->
        <form class="library-search method=" get" action="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="paged" value="1">
            <div class="row">
                <div class="col-md-10">
                    <div class="mb-1">Search</div>
                    <input type="text" class="form-control" name="search_term" placeholder="Enter keywords or trust names" value="<?php echo isset($_GET['search_term']) ? esc_attr($_GET['search_term']) : ''; ?>">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="button">Search</button>
                </div>
            </div>
        </form>
    </div>

    <section class="library has-blue-200-background-color">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-3 py-5 pe-4">
                    <!-- Filters -->
                    <form method="get" action="<?php echo esc_url(get_permalink()); ?>">
                        <h3 class="mb-4">Filter</h3>
                        <div class="filter-group mb-4">
                            <div class="mb-4"><strong>By Category</strong></div>
                            <div class="filter-group__filters">
                                <?php
                                $doccat_terms = get_terms(array(
                                    'taxonomy' => 'doccat',
                                    'hide_empty' => false,
                                ));
                                foreach ($doccat_terms as $term) {
                                ?>
                                    <label for="doccat_<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></label>
                                    <input class="form-check" type="checkbox" id="doccat_<?php echo esc_attr($term->slug); ?>" name="doccat[]" value="<?php echo esc_attr($term->slug); ?>" <?php if (isset($_GET['doccat']) && is_array($_GET['doccat']) && in_array($term->slug, $_GET['doccat'])) echo 'checked'; ?>>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="filter-group mb-5">
                            <div class="mb-4"><strong>By Type:</strong></div>
                            <div class="filter-group__filters">
                                <?php
                                $doctype_terms = get_terms(array(
                                    'taxonomy' => 'doctype',
                                    'hide_empty' => false,
                                ));
                                foreach ($doctype_terms as $term) {
                                ?>
                                    <label for="doctype_<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></label>
                                    <input class="form-check" type="checkbox" id="doctype_<?php echo esc_attr($term->slug); ?>" name="doctype[]" value="<?php echo esc_attr($term->slug); ?>" <?php if (isset($_GET['doctype']) && is_array($_GET['doctype']) && in_array($term->slug, $_GET['doctype'])) echo 'checked'; ?>>
                                <?php } ?>
                            </div>
                        </div>
                        <input type="hidden" name="paged" value="1">
                        <div class="filter-buttons">
                            <a class="button button-secondary" href="/literature-library">Clear filters</a>
                            <button type="submit" class="button">Apply filter</button>
                        </div>
                    </form>
                </div>
                <?php if ($query->have_posts()) { ?>
                    <div class="col-md-9 has-light-background-color px-0">
                        <div class="results px-4 py-3">
                            Showing <?= $start_post ?> - <?= $end_post ?> of <?= $total_posts ?> results
                        </div>
                        <!-- Document List -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Date Added</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($query->have_posts()) {
                                    $query->the_post();
                                    $attachment_url = wp_get_attachment_url( get_field('file', get_the_ID()) );
                                ?>
                                    <tr onclick="window.open('<?php echo $attachment_url; ?>', '_blank')" style="cursor: pointer;">
                                        <td><?php echo esc_html(get_the_terms(get_the_ID(), 'doccat')[0]->name ?? ''); ?></td>
                                        <td><?php echo esc_html(get_the_terms(get_the_ID(), 'doctype')[0]->name ?? ''); ?></td>
                                        <td><?php the_title(); ?></td>
                                        <td><?php echo get_the_date('d M Y'); ?></td>
                                        <td><a href="<?php echo $attachment_url; ?>" download class="icon-download" style="text-decoration: none; color: inherit;"></a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="col-md-8 bg-white p-4">
                        No documents found. Please adjust your search or filter criteria.
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <div class="container-xl">
        <div class="row">
            <div class="col-12 d-flex justify-content-around py-4">
                <!-- Pagination -->
                <?php
                $big = 999999999; // need an unlikely integer
                $base = str_replace($big, '%#%', esc_url(get_pagenum_link($big)));

                // Build the pagination with the correct add_args
                $pagination_args = array(
                    'base'    => $base,
                    'format'  => '?paged=%#%',
                    'current' => max(1, $paged),
                    'total'   => $query->max_num_pages,
                    'prev_text' => '<div class="icon-prev"></div>',
                    'next_text' => '<div class="icon-next"></div>',
                );

                // Collect query args to pass to add_args to ensure all filters and search terms are preserved
                $query_args = array();
                if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
                    $query_args['search_term'] = sanitize_text_field($_GET['search_term']);
                }
                if (isset($_GET['doccat']) && is_array($_GET['doccat']) && !empty($_GET['doccat'])) {
                    $query_args['doccat'] = array_map('sanitize_text_field', $_GET['doccat']);
                }
                if (isset($_GET['doctype']) && is_array($_GET['doctype']) && !empty($_GET['doctype'])) {
                    $query_args['doctype'] = array_map('sanitize_text_field', $_GET['doctype']);
                }

                // Add query args to pagination links if present
                if (!empty($query_args)) {
                    $pagination_args['add_args'] = $query_args;
                }

                $pagination_links = paginate_links($pagination_args);

                // Add first and last page links
                $first_page_link = ($paged > 1) ? '<a href="' . esc_url(get_pagenum_link(1)) . '" class="icon-first"></a>' : '';
                $last_page_link = ($paged < $query->max_num_pages) ? '<a href="' . esc_url(get_pagenum_link($query->max_num_pages)) . '" class="icon-last"></a>' : '';

                // Display pagination with first and last links included
                echo '<div class="pagination">';
                echo $first_page_link;
                echo $pagination_links;
                echo $last_page_link;
                echo '</div>';
                // echo paginate_links($pagination_args);
                ?>
            </div>

            <?php wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
    $l = get_field('cta_link','option');
    ?>
    <section class="wide_cta py-5">
        <div class="container-xl py-4 text-center">
            <h2><?=get_field('cta_title','option')?></h2>
            <div class="w-constrained mx-auto pb-4"><?=get_field('cta_content','option')?></div>
            <a href="<?=$l['url']?>" target="<?=$l['target']?>" class="button"><?=$l['title']?></a>
        </div>
    </section>
</main>

<?php get_footer(); ?>