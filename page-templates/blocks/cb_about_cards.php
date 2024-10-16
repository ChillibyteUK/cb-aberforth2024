<?php
$partner_link = get_field('partnership_link');
$philosophy_link = get_field('philosophy_link');
$team_link = get_field('team_link');
?>
<section class="about_cards py-5">
    <div class="container-xl">
        <h2 class="text-center mb-4">About Aberforth</h2>
        <div class="text-center w-constrained mx-auto mb-5"><?=get_field('intro')?></div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="about_cards__card">
                    <?=wp_get_attachment_image(get_field('partnership_icon'),'full',false,array('class' => 'about_cards__icon'))?>
                    <h3>The Partnership</h3>
                    <p><?=get_field('partnership_intro')?></p>
                    <a href="<?=$partner_link['url']?>" target="<?=$partner_link['target']?>" class="button button-secondary"><?=$partner_link['title']?></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about_cards__card">
                    <?=wp_get_attachment_image(get_field('philosophy_icon'),'full',false,array('class' => 'about_cards__icon'))?>
                    <h3>Our Philosophy</h3>
                    <p><?=get_field('philosophy_intro')?></p>
                    <a href="<?=$philosophy_link['url']?>" target="<?=$philosophy_link['target']?>" class="button button-secondary"><?=$philosophy_link['title']?></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about_cards__card">
                    <?=wp_get_attachment_image(get_field('team_icon'),'full',false,array('class' => 'about_cards__icon'))?>
                    <h3>The Team</h3>
                    <p><?=get_field('team_intro')?></p>
                    <a href="<?=$team_link['url']?>" target="<?=$team_link['target']?>" class="button button-secondary"><?=$team_link['title']?></a>
                </div>
            </div>
        </div>
    </div>
</section>