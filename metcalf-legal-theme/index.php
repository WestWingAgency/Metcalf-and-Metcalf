
<?php
/**
 * The main template file for Metcalf Legal Theme
 * 
 * This is the most generic template file in a WordPress theme and
 * is used to display a page when nothing more specific matches.
 * For a legal platform using Elementor Pro, this serves as a
 * minimal fallback template.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        
        <?php if (have_posts()) : ?>
            
            <div class="posts-container">
                
                <?php
                // Start the loop
                while (have_posts()) :
                    the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-entry'); ?>>
                    
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;
                        
                        if ('post' === get_post_type()) :
                        ?>
                        <div class="entry-meta">
                            <span class="posted-on">
                                <?php
                                printf(
                                    '<time class="entry-date published" datetime="%1$s">%2$s</time>',
                                    esc_attr(get_the_date('c')),
                                    esc_html(get_the_date())
                                );
                                ?>
                            </span>
                            
                            <span class="byline">
                                <?php
                                printf(
                                    'by <span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
                                    esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                                    esc_html(get_the_author())
                                );
                                ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </header>
                    
                    <?php if (has_post_thumbnail() && !is_singular()) : ?>
                    <div class="entry-thumbnail">
                        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                            <?php
                            the_post_thumbnail('medium', array(
                                'alt' => the_title_attribute(array('echo' => false)),
                            ));
                            ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="entry-content">
                        <?php
                        if (is_singular()) {
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'metcalf-legal-theme'),
                                'after'  => '</div>',
                            ));
                        } else {
                            the_excerpt();
                        }
                        ?>
                    </div>
                    
                    <?php if (is_singular()) : ?>
                    <footer class="entry-footer">
                        <?php
                        $categories_list = get_the_category_list(', ');
                        if ($categories_list) {
                            printf('<span class="cat-links">Categories: %s</span>', $categories_list);
                        }
                        
                        $tags_list = get_the_tag_list('', ', ');
                        if ($tags_list) {
                            printf('<span class="tags-links">Tags: %s</span>', $tags_list);
                        }
                        ?>
                    </footer>
                    <?php endif; ?>
                    
                </article>
                
                <?php
                endwhile;
                ?>
                
            </div>
            
            <?php
            // Show pagination for multiple posts
            if (!is_singular()) {
                the_posts_pagination(array(
                    'prev_text' => '&laquo; Previous',
                    'next_text' => 'Next &raquo;',
                ));
            }
            
            // Show comments on single posts/pages
            if (is_singular() && (comments_open() || get_comments_number())) {
                comments_template();
            }
            ?>
            
        <?php else : ?>
            
            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Nothing here', 'metcalf-legal-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        <p>
                            <?php
                            printf(
                                wp_kses(
                                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'metcalf-legal-theme'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>
                    <?php elseif (is_search()) : ?>
                        <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'metcalf-legal-theme'); ?></p>
                        <?php get_search_form(); ?>
                    <?php else : ?>
                        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'metcalf-legal-theme'); ?></p>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>
            </section>
            
        <?php endif; ?>
        
    </div>
</main>

<?php
get_sidebar();
get_footer();
