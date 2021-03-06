<?php
/**
 * @package CARES
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_singular( get_post_type() ) ) : ?>

		<header class="entry-header">
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">
				<?php if ( has_post_thumbnail() )
					cares_responsive_thumbnail();
				?>
				<div class="entry-header-text">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<?php if ( 'post' == get_post_type() ) : ?>
					<div class="entry-meta">
						<?php cares_posted_on(); ?>
					</div><!-- .entry-meta -->
					<?php endif; ?>
				</div>
			</a>

		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cares' ) ); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'cares' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-footer">
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
				<?php
					/* translators: used between list items, there is a space after the comma */
					$categories_list = get_the_category_list( __( ', ', 'cares' ) );
					if ( $categories_list && cares_categorized_blog() ) :
				?>
				<span class="cat-links">
					<?php printf( __( 'Posted in %1$s', 'cares' ), $categories_list ); ?>
				</span>
				<?php endif; // End if categories ?>

				<?php
					/* translators: used between list items, there is a space after the comma */
					$tags_list = get_the_tag_list( '', __( ', ', 'cares' ) );
					if ( $tags_list ) :
				?>
				<span class="tags-links">
					<?php printf( __( 'Tagged %1$s', 'cares' ), $tags_list ); ?>
				</span>
				<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>

			<?php if ( ! post_password_required() && is_single() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'cares' ), __( '1 Comment', 'cares' ), __( '% Comments', 'cares' ) ); ?></span>
			<?php endif; ?>

			<?php //edit_post_link( __( 'Edit', 'cares' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-footer -->
	
	<?php else: // not a single view, provide short form ?>
	
		<header class="entry-header">

			<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">
				<?php if ( has_post_thumbnail() )
					cares_responsive_thumbnail( );
				?>
				<div class="entry-header-text">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</div>
			</a>
			
		</header><!-- .entry-header -->

		<div class="entry-content">
		
			<?php the_excerpt(); ?>
			
		</div><!-- .entry-content -->
			
	<?php endif; ?>
	
</article><!-- #post-## -->
