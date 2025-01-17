<section class="fund_accordion pt-4 pb-5">
    <div class="container-xl">

        <div class="accordion" id="fundAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ascot" aria-expanded="true" aria-controls="ascot">
                        Aberforth Smaller Companies Trust plc
                    </button>
                </h2>
                <div id="ascot" class="accordion-collapse collapse" data-bs-parent="#fundAccordion">
                    <div class="accordion-body">
                        <div class="nav nav-tabs">
                            <button class="nav-link active" id="ascot-investing-tab" data-bs-toggle="tab" data-bs-target="#ascot-investing" type="button" role="tab" aria-controls="ascot-investing" aria-selected="true">Investing Information</button>
                            <button class="nav-link" id="ascot-price-tab" data-bs-toggle="tab" data-bs-target="#ascot-price" type="button" role="tab" aria-controls="ascot-price" aria-selected="false">Price Ordinary Shares</button>
                        </div>
                        <div class="tab-content" id="ascot-content">
                            <div class="tab-pane fade show active" id="ascot-investing" aria-labelledby="ascot-investing-tab">
                                <?php
                                if (get_field('ascot_invest_intro') ?? null) {
                                    ?>
                                <div class="p-4"><?=get_field('ascot_invest_intro')?></div>
                                <?php
                                }
                                else {
                                    ?>
                                    <div class="p-2"></div>
                                    <?php
                                }
                                $ascot_cards = [
                                    get_field('ascot_invest_card_1'),
                                    get_field('ascot_invest_card_2'),
                                    get_field('ascot_invest_card_3'),
                                    get_field('ascot_invest_card_4')
                                ];
                                ?>
                                <div class="row g-4">
                                    <?php
                                    foreach ($ascot_cards as $ac) {
                                        ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="fund_accordion__card d-grid">
                                            <div>
                                                <h3 class="mb-4"><?=$ac['card_title']?></h3>
                                                <p><?=$ac['card_content']?></p>
                                            </div>
                                            <?php
                                            if ($ac['card_button'] ?? null) {
                                                $l = $ac['card_button'];
                                                ?>
                                            <a href="<?=$l['url']?>" target="<?=$l['target']?>" class="button button-secondary align-self-end"><?=$l['title']?></a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade px-0" id="ascot-price" aria-labelledby="ascot-price-tab">
                                <iframe src="https://irs.tools.investis.com/Clients/uk/aberforth/SM8/Default.aspx?culture=en-GB" width="100%" class="fund_accordion__iframe" height="700px" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#agvit" aria-expanded="false" aria-controls="agvit">
                        Aberforth Geared Value &amp; Income Trust plc
                    </button>
                </h2>
                <div id="agvit" class="accordion-collapse collapse" data-bs-parent="#fundAccordion">
                    <div class="accordion-body">
                        <div class="nav nav-tabs">
                            <button class="nav-link active" id="agvit-investing-tab" data-bs-toggle="tab" data-bs-target="#agvit-investing" type="button" role="tab" aria-controls="agvit-investing" aria-selected="true">Investing Information</button>
                            <button class="nav-link" id="agvit-price-tab" data-bs-toggle="tab" data-bs-target="#agvit-price" type="button" role="tab" aria-controls="agvit-price" aria-selected="false">Price Ordinary Shares</button>
                            <button class="nav-link" id="agvit-zero-tab" data-bs-toggle="tab" data-bs-target="#agvit-zero" type="button" role="tab" aria-controls="agvit-zero" aria-selected="false">Price Zero Dividend Preference Shares</button>
                        </div>
                        <div class="tab-content" id="agvit-content">
                            <div class="tab-pane fade show active" id="agvit-investing" aria-labelledby="agvit-investing-tab">
                            <?php
                                if (get_field('agvit_invest_intro') ?? null) {
                                    ?>
                                <div class="p-4"><?=get_field('agvit_invest_intro')?></div>
                                <?php
                                }
                                else {
                                    ?>
                                    <div class="p-2"></div>
                                    <?php
                                }
                                $agvit_cards = [
                                    get_field('agvit_invest_card_1'),
                                    get_field('agvit_invest_card_2'),
                                    get_field('agvit_invest_card_3'),
                                    get_field('agvit_invest_card_4')
                                ];
                                ?>
                                <div class="row g-4">
                                    <?php
                                    foreach ($agvit_cards as $ac) {
                                        ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="fund_accordion__card d-grid">
                                            <div>
                                                <h3 class="mb-4"><?=$ac['card_title']?></h3>
                                                <p><?=$ac['card_content']?></p>
                                            </div>
                                            <?php
                                            if ($ac['card_button'] ?? null) {
                                                $l = $ac['card_button'];
                                                ?>
                                            <a href="<?=$l['url']?>" target="<?=$l['target']?>" class="button button-secondary align-self-end"><?=$l['title']?></a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade px-0" id="agvit-price" aria-labelledby="agvit-price-tab">
                                <iframe src="https://irs.tools.investis.com/Clients/uk/aberforth_geared_value/SM8/Default.aspx?culture=en-GB" width="100%" class="fund_accordion__iframe" height="700px" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
                            </div>
                            <div class="tab-pane fade px-0" id="agvit-zero" aria-labelledby="agvit-zero-tab">
                                <iframe src="https://irs.tools.investis.com/Clients/uk/aberforth_geared_value/SM8/Default1.aspx?culture=en-GB" width="100%" class="fund_accordion__iframe" height="700px" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#afund" aria-expanded="false" aria-controls="afund">
                        Aberforth UK Small Companies Fund
                    </button>
                </h2>
                <div id="afund" class="accordion-collapse collapse" data-bs-parent="#fundAccordion">
                    <div class="accordion-body">
                        <div class="nav nav-tabs">
                            <button class="nav-link active" id="afund-investing-tab" data-bs-toggle="tab" data-bs-target="#afund-investing" type="button" role="tab" aria-controls="afund-investing" aria-selected="true">Investing Information</button>
                            <button class="nav-link" id="afund-dealing-tab" data-bs-toggle="tab" data-bs-target="#afund-dealing" type="button" role="tab" aria-controls="afund-dealing" aria-selected="false">Dealing</button>
                            <button class="nav-link" id="afund-docs-tab" data-bs-toggle="tab" data-bs-target="#afund-docs" type="button" role="tab" aria-controls="afund-docs" aria-selected="false">Documents</button>
                        </div>
                        <div class="tab-content" id="afund-content">
                            <div class="tab-pane fade show active" id="afund-investing" aria-labelledby="afund-investing-tab">
                                <?php
                                if (get_field('afund_invest_intro') ?? null) {
                                    ?>
                                <div class="p-4"><?=get_field('afund_invest_intro')?></div>
                                    <?php
                                }
                                else {
                                    ?>
                                    <div class="p-2"></div>
                                    <?php
                                }
                                $afund_cards = [
                                    get_field('afund_invest_card_1'),
                                    get_field('afund_invest_card_2'),
                                    get_field('afund_invest_card_3'),
                                    get_field('afund_invest_card_4')
                                ];
                                ?>
                                <div class="row g-4">
                                    <?php
                                    foreach ($afund_cards as $ac) {
                                        ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="fund_accordion__card d-grid">
                                            <div>
                                                <h3 class="mb-4"><?=$ac['card_title']?></h3>
                                                <p><?=$ac['card_content']?></p>
                                            </div>
                                            <?php
                                            if ($ac['card_button'] ?? null) {
                                                $l = $ac['card_button'];
                                                ?>
                                            <a href="<?=$l['url']?>" target="<?=$l['target']?>" class="button button-secondary align-self-end"><?=$l['title']?></a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade px-0" id="afund-dealing" aria-labelledby="afund-dealing-tab">
                                <div class="p-4"><?=get_field('afund_dealing_intro')?></div>
                            </div>
                            <div class="tab-pane fade px-0" id="afund-docs" aria-labelledby="afund-docs-tab">
                                <table class="table fs-300">
                                    <tbody>
                                        <?php
                                        $docs = get_field('afund_docs') ?? null;
                                        if ($docs) {
                                            $documents = [];

                                            foreach (get_field('afund_docs') as $f) {
                                                $file = get_field('file', $f);
                                                if (!$file) continue; // Skip if no file is found
                                            
                                                $attachment_url = wp_get_attachment_url($file);
                                                $file_path = get_attached_file($file);
                                                $file_size = file_exists($file_path) ? filesize($file_path) : 0;

                                                $category = get_the_terms($f, 'doccat');
                                                $category_name = !empty($category) && !is_wp_error($category) ? $category[0]->name : 'Uncategorised';

                                                $documents[] = [
                                                    'id' => $f,
                                                    'title' => get_the_title($f),
                                                    'category' => $category_name,
                                                    'size' => $file_size,
                                                    'formatted_size' => $file_size > 0 ? formatBytes($file_size, 0) : 'Unknown',
                                                    'date' => get_the_date('d M Y', $f),
                                                    'url' => esc_url($attachment_url),
                                                ];
                                            }

                                            usort($documents, function ($a, $b) {
                                                return strcasecmp($a['title'], $b['title']);
                                            });
                                            
                                            foreach ($documents as $doc) {
                                                ?>
                                        <tr onclick="window.location.href='<?= $doc['url'] ?>'" style="cursor: pointer;">
                                            <td class="fw-500"><?= esc_html($doc['title']) ?></td>
                                            <td><?= esc_html($doc['category']) ?></td>
                                            <td><?= esc_html($doc['formatted_size']) ?></td>
                                            <td><?= esc_html($doc['date']) ?></td>
                                            <td><a href="<?= $doc['url'] ?>" download class="icon-download" style="text-decoration: none; color: inherit;"></a></td>
                                        </tr>
                                                <?php
                                            }
                                        }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>