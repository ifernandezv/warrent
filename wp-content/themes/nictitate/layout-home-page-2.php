<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];

get_header(); ?>
          
<div id="main-content" style="overflow:hidden !important">

 <?php if ( is_active_sidebar($sidebars[0]) ) {?>
    <div class="widget">

        <?php if ( is_active_sidebar($sidebars[0]) )
            dynamic_sidebar( $sidebars[0] );
        ?>
    
    </div>
<?php }?>
    <div class="wrapper" style="margin-bottom:-35px !important;">
        <div class="row-fluid">
            <div class="span12 clearfix">

                <?php // print content of front page 
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();

                        if ( get_the_content() ) {
                            get_template_part( 'content', 'page' );
                        }
                    }
                } ?>
                
                <?php if ( is_active_sidebar($sidebars[1]) )
                    dynamic_sidebar( $sidebars[1] );
                ?>

            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->

 <?php if ( is_active_sidebar($sidebars[2]) ) {?>
    <div>
        <?php if ( is_active_sidebar($sidebars[2]) )
            dynamic_sidebar( $sidebars[2] );
        ?>
    </div>
            
    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12 clearfix">
                <?php if ( is_active_sidebar($sidebars[3]) )
                    dynamic_sidebar( $sidebars[3] );
                ?>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->        

    <div class="wrapper full-width">
        
        <?php if ( is_active_sidebar($sidebars[4]) )
            dynamic_sidebar( $sidebars[4] );
        ?>
        
    </div><!--wrapper-->
    <?php }?>

</div> <!-- #main-content -->

<?php get_footer(); ?>