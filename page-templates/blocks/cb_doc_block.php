<section class="doc_block pt-4">
    <div class="container-xl">
        <?php
        if (get_field('title') ?? null) {
            ?>
        <h2><?=get_field('title')?></h2>
            <?php
        }
        ?>
        <table class="table fs-300">
            <tbody>
                <?php
                $docs = get_field('files') ?? null;
                if ($docs) {
                    foreach (get_field('files') as $f) {
                        $file = get_field('file',$f);
                        $attachment_url = wp_get_attachment_url($file);
                        $file_path = get_attached_file($file) ?? null;

                        if ($file_path !== null && file_exists($file_path)) {
                            $file_size = filesize($file_path);
                        } else {
                            $file_size = 0;
                        }

                        $disclaimer = get_field('disclaimer_active', $f);
                        if (!empty($disclaimer) && is_array($disclaimer) && isset($disclaimer[0]) && $disclaimer[0] === 'Yes') {

                            ?>
                <tr data-bs-toggle="modal" data-bs-target="#modal_<?=$f?>" style="cursor: pointer;">
                    <td class="fw-500"><?=get_the_title($f)?></td>
                    <td><?=esc_html(get_the_terms($f, 'doccat')[0]->name ?? '')?></td>
                    <td><?=formatBytes($file_size,0)?></td>
                    <td><?=get_the_date('d M Y',$f); ?></td>
                    <td><span class="icon-download" style="text-decoration: none; color: inherit;"></span></td>
                </tr>
                <div class="modal fade" id="modal_<?=$f?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title"><?=get_field('disclaimer_header',$f)?></h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?=get_field('disclaimer',$f)?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button button-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="button accept-button" onclick="window.open('<?php echo $attachment_url; ?>', '_blank')">Accept</button>
                            </div>
                        </div>
                    </div>
                </div>

                            <?php
                        }
                        else {
                        ?>
                <tr onclick="window.open('<?php echo $attachment_url; ?>', '_blank')" style="cursor: pointer;">
                    <td class="fw-500"><?=get_the_title($f)?></td>
                    <td><?=esc_html(get_the_terms($f, 'doccat')[0]->name ?? '')?></td>
                    <td><?=formatBytes($file_size,0)?></td>
                    <td><?=get_the_date('d M Y',$f); ?></td>
                    <td><a href="<?=$attachment_url?>" download class="icon-download" style="text-decoration: none; color: inherit;"></a></td>
                </tr>
                    <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</section>