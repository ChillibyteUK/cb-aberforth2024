<?php

/**
 * Template Name: Restricted Page
 * Description: A page template that restricts access to logged-in users.
 */

get_header(); ?>

<main id="main" class="site-main">

    <?php
    if (is_user_logged_in()) {
        // Content for logged-in users
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_title('<h1 class="entry-title">', '</h1>');
                the_content();
            endwhile;
        else :
            echo '<p>No content found.</p>';
        endif;
    } else {
        // Message for non-logged-in users
        echo '<p>You must be logged in to view this page. <a href="' . wp_login_url(get_permalink()) . '">Click here to log in</a>.</p>';
    }
    ?>

</main><!-- #main -->
<?php

get_footer();
