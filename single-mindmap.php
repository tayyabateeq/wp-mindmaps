<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// single-mindmap.php

get_header();

// Check if it's a single mindmap post
if (is_singular('mindmap')) {
    echo '<div class="container">';
    // Display the single mindmap post
    while (have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" 
            <?php post_class(); ?>>
            <div class="single-mindmap-title d-flex align-items-center ">
                <p><strong>Mindmap Title:</strong></p>
                <p class="p-2"><?php the_title(); ?></p>
            </div>
            <?php
            // Retrieve saved JSON object data
            $mindmap_json_data = get_post_meta( get_the_ID(), '_mindmap_json_data', true );
            echo '<div class="single-mindmap-content" id="single-mindmap-content" style="display: none;">';
            echo $mindmap_json_data;
            echo '</div>';
            ?>
            <div class="single-mindmap-description d-flex align-items-center ">
                <p><strong>Mindmap Description:</strong></p>
                <p class="p-2"><?php the_content(); ?></p>
            </div>
            <div class="single-mindmap-categories d-flex align-items-center ">
                <p><strong>Categories:</strong></p>
                <p class="p-2">
                <?php
                $mindmap_categories = get_the_terms(get_the_ID(), 'mindmap_category');
                if ($mindmap_categories && !is_wp_error($mindmap_categories)) {
                    $category_html = '';
                    foreach ($mindmap_categories as $category) {
                        $category_html .= esc_html($category->name) . ', ';
                    }
                    echo rtrim($category_html, ', '); // Remove trailing comma
                } else {
                    echo 'No categories found or error retrieving categories.';
                }
                ?>
                </p>
            </div>
            <div class="single-mindmap-tags d-flex align-items-center ">
                <p><strong>Tags:</strong></p>
                <p class="p-2">
                <?php
                // Get tags and display their names
                $tags = get_the_terms(get_the_ID(), 'post_tag');
                if (!empty($tags) && !is_wp_error($tags)) {
                    $tag_names = array_map(function($tag) {
                        return $tag->name;
                    }, $tags);

                    echo implode(', ', $tag_names);
                } else {
                    echo 'No tags found.';
                }
                ?>
                </p>
            </div>
        </div>
    <?php endwhile;
    echo "<h3><strong>Mindmap</strong></h3>";
    require plugin_dir_path( __FILE__ ) . 'templates/app-edit.php';
    // If no posts found
    if (!have_posts()) {
        echo 'No mindmaps found.';
    }
    echo '</div>';
}
// Footer
get_footer();
?>
