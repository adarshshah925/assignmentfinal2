<?php
/*
Template Name: Front end movie page layout
*/
get_header();
?> 
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<?php
			 echo do_shortcode("[movie_page]");
			?>
		</div>
	</div>
</div>
<?php
get_footer();
?>