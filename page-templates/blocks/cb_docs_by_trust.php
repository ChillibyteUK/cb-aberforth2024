<?php
$classes = $block['className'] ?? null;
?>
<section class="docs_by_fund pt-4 pb-5 <?= $classes ?>">
    <div class="container-xl">
        <?php
        $fund = get_field('fund');
        $fund_obj = get_term($fund);
        $fund_slug = $fund_obj->slug;

        $documents = get_posts(array(
            'post_type' => 'document',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'doccat',
                    'field'    => 'term_id',
                    'terms'    => $fund,
                ),
            ),
        ));

        if (empty($documents)) {
            echo "No documents found for the given doccat term ID.";
            return;
        }

        // Prepare an array to group documents by doctype
        $documents_by_doctype = array();

        // Iterate through each document and group them by doctype
        foreach ($documents as $document) {
            // Get the doctype terms associated with the document
            $doctype_terms = wp_get_post_terms($document->ID, 'doctype');

            foreach ($doctype_terms as $doctype_term) {
                $doctype_name = $doctype_term->name;

                // Group documents by doctype term
                if (!isset($documents_by_doctype[$doctype_name])) {
                    $documents_by_doctype[$doctype_name] = array();
                }
                $documents_by_doctype[$doctype_name][] = $document;
            }
        }

        // Output the Bootstrap nav-tabs for each unique doctype
        ?>
        <ul class="nav nav-tabs" id="documentTabs" role="tablist">
            <?php
            $first = true;
            foreach (array_keys($documents_by_doctype) as $doctype) {
                $doctype_slug = sanitize_title($doctype); // Generate a slug for the tab ID
                $active_class = $first ? 'active' : '';
            ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $active_class ?>" id="<?= $doctype_slug ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $doctype_slug ?>" type="button" role="tab" aria-controls="<?= $doctype_slug ?>" aria-selected="<?= $first ? 'true' : 'false' ?>">
                        <?= esc_html($doctype) ?>
                    </button>
                </li>
            <?php
                $first = false;
            }
            ?>
        </ul>

        <div class="tab-content" id="documentTabsContent">
            <?php
            $first = true;

            $all_disclaimers = get_field('disclaimers', 'option');

            foreach ($documents_by_doctype as $doctype => $doctype_documents) {
                $doctype_slug = sanitize_title($doctype); // Generate a slug for the tab pane ID
                $is_active = $first ? 'show active' : '';
            ?>
                <div class="tab-pane fade <?= $is_active ?>" id="<?= $doctype_slug ?>" role="tabpanel" aria-labelledby="<?= $doctype_slug ?>-tab">
                    <table class="table fs-300">
                        <tbody>
                            <?php
                            $row_count = 0;
                            foreach ($doctype_documents as $doc) {
                                // Get the attachment ID from the ACF field
                                $f = $doc->ID;
                                $attachment_id = get_field('file', $f);
                                $attachment_url = wp_get_attachment_url($attachment_id);
                                $file_path = get_attached_file($attachment_id);
                                $file_size = filesize($file_path);
                                $hidden_class = ($row_count >= 10) ? ' class="hidden-row" style="display:none;"' : '';

                                $disclaimers = get_field('disclaimers_selection', $f) ?? null;


                                if (!empty($disclaimers) && is_array($disclaimers) && isset($disclaimers[0])) {
                                    $id = esc_attr($f);
                            ?>
                                    <tr <?= $hidden_class ?> data-bs-toggle="modal" data-bs-target="#modal_<?= $f ?>" style="cursor: pointer;">
                                        <td class="fw-500 column1"><?= esc_html($doc->post_title) ?></td>
                                        <td class="column2"><?= formatBytes($file_size, 0) ?></td>
                                        <td class="column3"><span class="icon-download" style="text-decoration: none; color: inherit;"></span></td>
                                    </tr>
                                    <div class="modal fade" id="modal_<?= $f ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div id="disclaimer-list-<?= $id ?>" class="disclaimer-list">
                                                        <?php
                                                        foreach ($disclaimers as $index => $disclaimer_name) {

                                                            foreach ($all_disclaimers as $disclaimer) {
                                                                if ($disclaimer['disclaimer_name'] === $disclaimer_name) {
                                                        ?>
                                                                    <div class="disclaimer-container" id="disclaimer-<?= $id ?>">
                                                                        <label for="disclaimer-<?= $id ?>-<?= $index ?>" class="switch-label">
                                                                            <?= $disclaimer['disclaimer_content'] ?>
                                                                        </label>
                                                                        <div class="switch-container">
                                                                            <input type="checkbox" class="disclaimer-checkbox" id="disclaimer-<?= $id ?>-<?= $index ?>">
                                                                            <label class="switch" for="disclaimer-<?= $id ?>-<?= $index ?>"></label>
                                                                        </div>
                                                                    </div>
                                                        <?php
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="button button-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="button accept-button" id="accept-button-<?= $id ?>" onclick="window.open('<?= esc_url($attachment_url) ?>', '_blank')" disabled>Accept</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <tr <?= $hidden_class ?> onclick="window.open('<?php echo $attachment_url; ?>', '_blank')" style="cursor: pointer;">
                                        <td class="fw-500 column1"><?= esc_html($doc->post_title) ?></td>
                                        <td class="column2"><?= formatBytes($file_size, 0) ?></td>
                                        <td class="column3"><a href="<?= esc_url($attachment_url) ?>" download class="icon-download" style="text-decoration: none; color: inherit;"></a></td>
                                    </tr>
                            <?php
                                }
                                $row_count++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if ($row_count > 10) { ?>
                        <a href="#" id="showFullTable-<?= $doctype_slug ?>" class="show-full-table">Show Full Table (<?= $row_count - 10 ?> Rows)</a>
                    <?php } ?>
                </div>
            <?php
                $first = false;
            }
            ?>
        </div>
        <div class="text-center">
            <a href="/literature-library/?doccat%5B0%5D=<?= $fund_slug ?>" class="button button-secondary">View all documents in Literature Library</a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners for each "Show Full Table" link
        document.querySelectorAll('.show-full-table').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const doctypeSlug = this.id.replace('showFullTable-', '');
                const hiddenRows = document.querySelectorAll('#' + doctypeSlug + ' .hidden-row');
                hiddenRows.forEach(function(row) {
                    row.style.display = 'table-row';
                });
                this.style.display = 'none';
            });
        });
    });
</script>