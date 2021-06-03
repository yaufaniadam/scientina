<?php

/* Template Name: Beli */


get_header(); ?>

<div id="primary" <?php generate_do_element_classes('content'); ?>>
  <main id="main" <?php generate_do_element_classes('main'); ?>>
    <div class="inside-article">
      <?php do_action('generate_before_main_content');  ?>

       
        
      <?php do_action('generate_after_main_content'); ?>
    </div>
  </main>
</div>
<?php get_footer(); ?>