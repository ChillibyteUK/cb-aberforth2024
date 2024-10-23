<section class="doc_block pt-4">
    <div class="container-xl">
        <table class="table fs-300">
            <tbody>
                <?php
                foreach (get_field('files') as $f) {
                    $attachment_url = wp_get_attachment_url($f);
                    $file_path = get_attached_file($f);
                    $file_size = filesize($file_path);
                    ?>
                    <tr onclick="window.location.href='<?=$attachment_url?>'" style="cursor: pointer;">
                        <td class="fw-500"><?=get_the_title($f)?></td>
                        <td><?=esc_html(get_the_terms($f, 'doccat')[0]->name ?? '')?></td>
                        <td><?=formatBytes($file_size,0)?></td>
                        <td><?=get_the_date('d M Y',$f); ?></td>
                        <td><a href="<?=$attachment_url?>" download class="icon-download" style="text-decoration: none; color: inherit;"></a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</section>