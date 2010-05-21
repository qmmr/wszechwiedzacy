<?php
    // Include Wordpress
	define('WP_USE_THEMES', false);
	
	// comment out $path that is not being used atm
//	$path = '/home/wszechwi/public_html/blog';
	$path = 'C:\wamp\www\wordpress';
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
	
	require_once('wp-load.php');					
	query_posts('showposts=1');
	// echo get_include_path();
		
	require_once("includes/initialize.php");
	
?>
<?php require_once(LIB_PATH.DS."header.php"); ?>
    <div id="mainContent">
		
		<div id="slider">
			<a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/slides/slide_1.jpg" alt="http://wszechwiedzacy.pl" /></a>
			<a href="<?php echo SITE_URL; ?>gra"><img src="<?php echo SITE_URL; ?>images/slides/slide_2.png" alt="Cechy gry wszechwiedzacy.pl" /></a>
			<a href="<?php echo SITE_URL; ?>gra"><img src="<?php echo SITE_URL; ?>images/slides/slide_3.png" alt="Zacznij grać już teraz!" /></a>
			<a href="<?php echo SITE_URL; ?>ranking"><img src="<?php echo SITE_URL; ?>images/slides/slide_4.png" alt="Sprawdź ranking!" /></a>
		</div>
		
		<!--
<hr class="main" />
        
        <div class="content">
            <h3>Pytanie dnia</h3>
            <h4>Kiedy urodził się Fryderyk Chopin, wielki kompozytor, nazywany romantykiem fortepianu?</h4>
            <h5><a href="#">Znasz odpowiedź?</a></h5>
        </div>
-->        
        
        <hr class="main" />
		
	    <div class="content">
	    
	    	<div class="blog_excerpt">
				<h2><a href="<?php echo $nla['blog']; ?>">Blog</a></h2>
				<?php while (have_posts()) : the_post(); ?>
				
				<div class="post" id="post-<?php the_ID(); ?>">
	
					<div class="heading">
						<div class="date">
							<span class="post-date"><?php the_time('j F, Y'); ?></span>
						</div>
						<h3><a href="<?php the_permalink() ?>" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</div><!-- /heading -->

					<div class="comments">
						<?php comments_popup_link('0', '%', '%'); ?>
					</div><!-- /meta -->

				    <div class="entry">
						<?php the_content('<span class="more_img"></span> Czytaj resztę ...'); ?>
				    </div><!-- /entry -->
	
				</div><!-- /post -->
					
				<?php endwhile; ?>
			</div><!-- .blog_excerpt -->
				
	    </div><!-- end .content -->

    </div><!-- end #mainContent -->
<?php require_once(LIB_PATH.DS."footer.php"); ?>