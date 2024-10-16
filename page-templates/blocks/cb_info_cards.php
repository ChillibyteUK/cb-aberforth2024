<?php
$library_link = get_field('library_link');
$invest_link = get_field('invest_link');
?>
<section class="info_cards py-5">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="info_cards__card">
                    <?=wp_get_attachment_image(get_field('library_icon'),'full',false,array('class' => 'info_cards__icon'))?>
                    <div class="info_cards__inner">
                        <h3>Literature Library</h3>
                        <p><?=get_field('library_intro')?></p>
                        <a href="<?=$library_link['url']?>" target="<?=$library_link['target']?>" class="button button-secondary align-self-start"><?=$library_link['title']?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info_cards__card">
                    <?=wp_get_attachment_image(get_field('invest_icon'),'full',false,array('class' => 'info_cards__icon'))?>
                    <div class="info_cards__inner">
                        <h3>How to invest</h3>
                        <p><?=get_field('invest_intro')?></p>
                        <a href="<?=$invest_link['url']?>" target="<?=$invest_link['target']?>" class="button button-secondary align-self-start"><?=$invest_link['title']?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>