<div id="sidebar">
	<div class="padder">
	
	<div id="categories-apps" class="widget widget_categories">
	
		<h3 class="widgettitle">Kategorien</h3>
			<?php 
			global $post, $cgt;
            
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
            
            $orderby = 'name';
			$show_count = 0; // 1 for yes, 0 for no
			$pad_counts = 0; // 1 for yes, 0 for no
			$hierarchical = 1; // 1 for yes, 0 for no
			$taxonomy = $post->post_type . '_category';
			$title = '';
			$hide_empty = 0;
			$show_count = 1;
			
			$args = array(
				'orderby' => $orderby,
				'show_count' => $show_count,
				'pad_counts' => $pad_counts,
				'hierarchical' => $hierarchical,
				'taxonomy' => $taxonomy,
				'title_li' => $title,
				'hide_empty' => $hide_empty,
				'show_count' => $show_count
			);
			?>
			<ul>
			<?php
			wp_list_categories($args);
			?>
			</ul>
	</div> 
	
	</div><!-- #padder -->	
</div><!-- #sidebar -->