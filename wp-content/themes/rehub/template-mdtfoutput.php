<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

    /* Template Name: MDTF output page */

?>
<?php get_header(); ?>
<!-- CONTENT -->
<div class="rh-container"> 
    <div class="rh-content-wrap clearfix">
        <!-- Main Side -->
        <div class="main-side page clearfix">
            <article class="post" id="page-<?php the_ID(); ?>">					
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
              <?php the_content(); ?>
            <?php endwhile; endif; ?>               
            </article>
        </div>	
        <!-- /Main Side -->  
        <!-- Sidebar -->
        <aside class="sidebar">					
            <!-- SIDEBAR WIDGET AREA -->
			<?php if ( is_active_sidebar( 'rhcustomsidebar' ) ) : ?>
				<?php dynamic_sidebar( 'rhcustomsidebar' ); ?>
			<?php else : ?>
				<p><?php _e('No widgets added. Add widgets inside Custom sidebar area in Appearance - Widgets', 'rehub_framework'); ?></p>
			<?php endif; ?>                      				
        </aside>
        <!-- /Sidebar -->  
    </div>
</div>
<!-- /CONTENT -->     
<!-- FOOTER -->
<?php get_footer(); ?>