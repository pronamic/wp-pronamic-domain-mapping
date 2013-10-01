<div class="wrap">
	<?php screen_icon(); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<?php 
	
	global $post;

	$query = new WP_Query( array(
		'post_type'  => 'any',
		'nopaging'   => true,
		'meta_query' => array(
			array(
				'key'     => '_pronamic_domain_mapping_host',
				'compare' => 'EXISTS'
			)
		),
		'order'      => 'ASC',
		'orderby'    => 'meta_value',
		'meta_key'   => '_pronamic_domain_mapping_host'
	) );

	?>

	<ul class="subsubsub">
		<li class="all">
			<a class="current" href="">
				<?php _e( 'All', 'pronamic_domain_mapping' ); ?> <span class="count">(<?php echo $query->found_posts; ?>)</span>
			</a>
		</li>
	</ul>

	<table cellspacing="0" class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th scope="col"><?php _e( 'Domain Name', 'pronamic_domain_mapping' ); ?></th>
				<th scope="col"><?php _e( 'Title', 'pronamic_domain_mapping' ); ?></th>
			</tr>
		</thead>
		
		<tbody>
		
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
	
				<tr>
					<td>
						<strong>
							<a href="<?php the_permalink(); ?>">
								<?php echo get_post_meta( $post->ID, '_pronamic_domain_mapping_host', true ); ?>
							</a>
						</strong>

						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo get_edit_post_link( $post->ID ); ?>"><?php _e( 'Edit', 'pronamic_domain_mapping' ); ?></a> | 
							</span>
							
							<span class="view">
								<a href="<?php the_permalink(); ?>"><?php _e( 'View', 'pronamic_domain_mapping' ); ?></a>
							</span>
						</div>
					</td>
					<td>
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</td>
				</tr>
			
			<?php endwhile; ?>

		</tbody>
	</table>
	
	<?php 
	
	wp_reset_postdata();
	
	?>
</div>