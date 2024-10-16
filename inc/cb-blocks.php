<?php
function acf_blocks()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type(array(
            'name'				=> 'cb_home_hero',
            'title'				=> __('CB Home Hero'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/cb_home_hero.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'cb_trusts_funds',
            'title'				=> __('CB Trusts & Funds'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/cb_trusts_funds.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'cb_about_cards',
            'title'				=> __('CB About Cards'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/cb_about_cards.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'cb_info_cards',
            'title'				=> __('CB Info Cards'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/cb_info_cards.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'cb_wide_cta',
            'title'				=> __('CB Wide CTA'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/cb_wide_cta.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
    }
}
add_action('acf/init', 'acf_blocks');
    
// Gutenburg core modifications
add_filter('register_block_type_args', 'core_image_block_type_args', 10, 3);
function core_image_block_type_args($args, $name)
{
    if ($name == 'core/paragraph') {
        $args['render_callback'] = 'modify_core_add_container';
    }
    if ($name == 'core/heading') {
        $args['render_callback'] = 'modify_core_add_container';
    }
    if ($name == 'core/list') {
        $args['render_callback'] = 'modify_core_add_container';
    }

    return $args;
}

function modify_core_add_container($attributes, $content)
{
    ob_start();
    // $class = $block['className'];
    ?>
<div class="container-xl">
    <?=$content?>
</div>
<?php
    $content = ob_get_clean();
    return $content;
}