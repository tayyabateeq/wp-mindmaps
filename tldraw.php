<?php
/**
 * Plugin Name:       Mindmaps
 * Description:       Effortlessly create dynamic mind maps directly within the WordPress environment, allowing you to brainstorm, plan projects, and visualize complex concepts with ease.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Synavos
 * Author URI:        https://synavos.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       Mindmaps
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Flush permalinks on plugin activation
register_activation_hook( __FILE__, 'flush_plugin_permalinks' );
function flush_plugin_permalinks() {
    flush_rewrite_rules();
    // Set custom post type permalink structure
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
}

// Flush permalinks on plugin deactivation
register_deactivation_hook( __FILE__, 'flush_plugin_permalinks_deactivation' );
function flush_plugin_permalinks_deactivation() {
    flush_rewrite_rules();
}

add_action( 'admin_menu', 'mindmap_init_menu' );

/**
 * Init Admin Menu.
*
* @return void
*/
function mindmap_init_menu() {
    
    add_menu_page(
        'Mindmap Settings',
        'Mindmaps',
        'manage_options',
        'mindmap_settings',
        'mindmap_overview',
    );
    add_submenu_page(
        'mindmap_settings',
        'Add New Mindmap',
        'Add New Mindmap',
        'manage_options',
        'add_new_mindmap',
        'mindmap_add_new',
    );
}
function mindmap_overview() {
    ?>
    <div class="mindmap_form">
        <h2>Mindmaps</h2>
        <h3>What is it?</h3>
        <p>This advanced WordPress plugin allows you to transform your raw-drawn mindmap ideas into colourful, customised mindmaps.</p>
        <p>Creating infinite canvas experiences</p>
        <p>It's the software behind the digital whiteboard</p>
        <h3>How to use it:</h3>
        <ul>
            <li><b>Create New Mindmaps: </b>Click the <a href="admin.php?page=add_new_mindmap"><b>Add New Mindmap</b></a> and a blank canvas opens up. Give it a title, description, tags, and categories - then start mapping out your ideas in whatever format works best.</li>

            <li><b>View Your Mindmaps: </b> All your created mindmaps are conveniently listed in one central place. Scan through the visual thumbnails to find the one you want to work on.</li>

            <li><b>Edit Mindmaps: </b>See a mindmap you want to update? Just click the <a href="admin.php?page=mindmap_listing">Edit Mindmap</a> link and the canvas reopens, ready for you to revise and expand upon your original ideas.</li>

            <li><b>Embed Mindmaps: </b>Copy the unique shortcode provided for each mindmap and paste it into the post, page, or widget area of your choice. The interactive mindmap will display right on that page.</li>

            <li><b>Sell Mindmaps: </b>Monetize your mindmaps by embedding the shortcodes into WooCommerce product description areas. Now you can offer your visual brainstorming sessions and workflows as premium content for sale.</li>

            <li><b>Export Mindmaps: </b>You can export the mindmaps in the png, svg or json formats.</li>
        </ul>
    </div>
    <?php
}
/**
 * Init Admin Page.
*
* @return void
*/
function mindmap_add_new() {
    ?>
    <div class="mindmap-add-new">
        <h2><strong>Create a new mindmap</strong></h2>
        <form id="mindmap_form" method="post" action="javascript:void(0);">
            
            <label for="mindmap_title"><h5>Mindmap Title</h5></label>
            <input type="text" name="mindmap_title" id="mindmap_title" class="form-control" placeholder="Enter Mindmap Title" required>
            
            <label for="mindmap_description"><h5>Mindmap Description</h5></label>
            <textarea name="mindmap_description" id="mindmap_description" class="form-control" rows="4" placeholder="Enter Mindmap Description" required></textarea>

            <label for="mindmap_tags"><h5>Mindmap Tags (comma-separated)</h5></label>
            <input type="text" name="mindmap_tags" id="mindmap_tags" class="form-control" placeholder="Enter Mindmap Tags" required>

            <label for="mindmap_category"><h5>Mindmap Category</h5></label>
            <input type="text" name="mindmap_category" class="form-control" id="mindmap_category" placeholder="Enter Mindmap Categories" required>
            
            <p>Draw Mindmap</p>
        </form>
        <?php require plugin_dir_path( __FILE__ ) . 'templates/app.php'; ?>
    </div>
    <?php
}
function display_mindmaps() {
    $mindmaps = get_posts(array(
        'post_type' => 'mindmap',
        'posts_per_page' => -1,
    ));

    if ($mindmaps) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Title</th><th>Description</th><th>Tags</th><th>Category</th><th>Shortcode</th><th>Edit</th></tr></thead>';
        echo '<tbody>';

        foreach ($mindmaps as $mindmap) {
            $mindmap_tags = get_the_tags($mindmap->ID);
            $mindmap_category = get_the_terms($mindmap->ID, 'mindmap_category');

            $tags_html = '';
            if ($mindmap_tags) {
                foreach ($mindmap_tags as $tag) {
                    $tags_html .= esc_html($tag->name) . ', ';
                }
                $tags_html = rtrim($tags_html, ', ');
            }

            $category_html = '';
            if ($mindmap_category) {
                foreach ($mindmap_category as $category) {
                    $category_html .= esc_html($category->name) . ', ';
                }
                $category_html = rtrim($category_html, ', ');
            }

            // Generate shortcode for each post
            $shortcode = '[mindmap id="' . $mindmap->ID . '"]';

            echo '<tr>';
            echo '<td>' . esc_html($mindmap->post_title) . '</td>';
            echo '<td>' . esc_html($mindmap->post_content) . '</td>';
            echo '<td>' . $tags_html . '</td>';
            echo '<td>' . $category_html . '</td>';
            echo '<td>' . esc_html($shortcode) . '</td>';
            echo '<td><a href="' . get_the_permalink($mindmap->ID) . '">Edit Mindmap</a></td>';
            // echo '<td><a href="#" class="edit-mindmap" data-post-id="' . $mindmap->ID . '">Edit</a></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        // require plugin_dir_path( __FILE__ ) . 'templates/app.php';
    } else {
        echo '<p>No mindmaps found.</p>';
    }
    
}



add_action( 'wp_enqueue_scripts', 'mindmap_admin_enqueue_scripts' );

/**
 * Enqueue scripts and styles.
*
* @return void
*/
function mindmap_wp_enqueue_scripts() {
    wp_enqueue_style( 'mindmap-style', plugin_dir_url( __FILE__ ) . 'build/index.css' );
    wp_enqueue_script( 'mindmap-script', plugin_dir_url( __FILE__ ) . 'build/index.js', array( 'wp-element' ), '1.0.0', true );
    wp_localize_script( 'mindmap-script', 'ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'save_drawing_and_create_post_nonce' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'mindmap_wp_enqueue_scripts' );

/**
 * Enqueue scripts and styles.
*
* @return void
*/
function mindmap_admin_enqueue_scripts() {
    wp_enqueue_style( 'mindmap-style', plugin_dir_url( __FILE__ ) . 'build/index.css' );
    wp_enqueue_script( 'mindmap-script', plugin_dir_url( __FILE__ ) . 'build/index.js', array( 'wp-element' ), '1.0.0', true );

    wp_localize_script( 'mindmap-script', 'ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'save_drawing_and_create_post_nonce' ),
    ) );
    // Enqueue your JavaScript file
    wp_enqueue_script( 'mindmap-update', plugin_dir_url( __FILE__ ) . 'build/index.js', array('jquery'), '1.0', true );

    // Pass the post ID to the JavaScript file
    global $post;
    wp_localize_script( 'mindmap-update', 'post_id_object', array( 'postId' => $post->ID ) );
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', array(), null, 'all');

    // Enqueue Bootstrap JS (jQuery dependency included)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js', array('jquery'), null, true);    
}
// Register custom post type
add_action('init', 'register_custom_post_type');
function register_custom_post_type() {
    $labels = array(
        'name'               => _x( 'Mindmaps', 'post type general name', 'your-text-domain' ),
        'singular_name'      => _x( 'Mindmap', 'post type singular name', 'your-text-domain' ),
        'menu_name'          => _x( 'Mindmaps', 'admin menu', 'your-text-domain' ),
        'name_admin_bar'     => _x( 'Mindmap', 'add new on admin bar', 'your-text-domain' ),
        'add_new'            => _x( 'Add New', 'mindmap', 'your-text-domain' ),
        'add_new_item'       => __( 'Add New Mindmap', 'your-text-domain' ),
        'new_item'           => __( 'New Mindmap', 'your-text-domain' ),
        'edit_item'          => __( 'Edit Mindmap', 'your-text-domain' ),
        'view_item'          => __( 'View Mindmap', 'your-text-domain' ),
        'all_items'          => __( 'All Mindmaps', 'your-text-domain'  ),
        'search_items'       => __( 'Search Mindmaps', 'your-text-domain' ),
        'parent_item_colon'  => __( 'Parent Mindmaps:', 'your-text-domain' ),
        'not_found'          => __( 'No mindmaps found.', 'your-text-domain' ),
        'not_found_in_trash' => __( 'No mindmaps found in Trash.', 'your-text-domain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'mindmap' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'tags', 'categories' ),
    );

    register_post_type( 'mindmap', $args );
    
    // Register custom taxonomy (category)
    register_taxonomy(
        'mindmap_category',
        'mindmap',	
        array(
            'label'             => __( 'Mindmap Categories', 'your-text-domain' ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'mindmap-category' ),
        )
    );
    flush_rewrite_rules();
    // Set custom post type permalink structure
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
}
// Include single-mindmap.php template
add_filter('single_template', 'load_custom_single_template');
function load_custom_single_template($single) {
    global $post;

    if ($post->post_type == 'mindmap') {
        $single = plugin_dir_path(__FILE__) . 'single-mindmap.php';
    }

    return $single;
}

// Add action to display mindmap listing
add_action('admin_menu', 'mindmap_listing_page');
function mindmap_listing_page() {
    add_submenu_page(
        'mindmap_settings',
        'Mindmap Listing',
        'Mindmap Listing',
        'manage_options',
        'mindmap_listing',
        'display_mindmaps_page'
    );
}

function display_mindmaps_page() {
    ?>
    <div class="mindmap-listing">
        <h2><strong>Mindmap Listing</strong></h2>
        <?php display_mindmaps(); ?>
    </div>
    
    <?php
    
}

add_shortcode('mindmap', 'mindmap_shortcode');
// Add shortcode functionality
function mindmap_shortcode($atts) {
    wp_head();

    if (is_admin()) {
        return '';
    }
    
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts );

    $post_id = intval($atts['id']);
    ?> 
    <div class="mindmap-content-preview">
        <?php
        // Check if a valid post ID is provided
        if ($post_id > 0) {
            // Retrieve and display the mindmap post content
            $mindmap = get_post($post_id);

            if ($mindmap && $mindmap->post_type === 'mindmap') {
                $mindmap_title = esc_html($mindmap->post_title);
                $mindmap_content = get_post_meta( $post_id, '_mindmap_json_data', true );
                $mindmap_discription = $mindmap->post_content;

                // Get tags for the mindmap
                $mindmap_tags = get_the_tags($mindmap->ID);
                $tags_html = '';
                if ($mindmap_tags) {
                    $tags_html = '<p>';
                    foreach ($mindmap_tags as $tag) {
                        $tags_html .= esc_html($tag->name) . ', ';
                    }
                    $tags_html = rtrim($tags_html, ', ') . '</p>';
                }

                // Get categories for the mindmap
                $mindmap_categories = get_the_terms($mindmap->ID, 'mindmap_category');
                $categories_html = '';
                if ($mindmap_categories) {
                    $categories_html = '<p>';
                    foreach ($mindmap_categories as $category) {
                        $categories_html .= esc_html($category->name) . ', ';
                    }
                    $categories_html = rtrim($categories_html, ', ') . '</p>';
                }
                // Output the mindmap content with additional information
                ?>
                <div class="mindmap-content">
                    <div class="mindmap-content-title">
                        <h4><?php echo $mindmap_title; ?></h4>
                        <p><?php echo $mindmap_discription; ?></p>
                    </div>
                    <div class="mindmap-content-categories">
                        <h4>Categories</h4>
                        <?php echo $categories_html; ?>
                    </div>
                    <div class="mindmap-content-tags">
                        <h4>Tags</h4>
                        <?php echo $tags_html;?>
                    </div>
                    <!-- Mindmap JSON Data -->
                    <div id="mindmap-tldraw" style="display: none;">
                        <?php echo $mindmap_content; ?>
                    </div>
                </div>
                <?php
            } else {
                // Output a message if the mindmap post is not found or not of the mindmap post type
                echo '<h4>Mindmap not found or not a valid mindmap shortcode id.</h1>';
            }
        } else {
            // Output a message for an invalid or missing post ID
            echo '<h4>Invalid or missing post ID.</h4>';
        }
        require_once plugin_dir_path( __FILE__ ) . 'templates/app-read.php';
    ?>
    </div>
    <?php
}

// Add a new action to handle saving the drawing and creating a new post
add_action('wp_ajax_save_drawing_and_create_post', 'save_drawing_and_create_post');
function save_drawing_and_create_post() {
    // Verify nonce
    if ( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'save_drawing_and_create_post_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Get the drawing data and other information from the request
    $drawingData = isset( $_POST['drawingData'] ) ? wp_unslash( $_POST['drawingData'] ) : '';
    $post_title = isset( $_POST['mindmap_title'] ) ? sanitize_text_field( $_POST['mindmap_title'] ) : '';
    $post_content = isset( $_POST['mindmap_description'] ) ? sanitize_textarea_field( $_POST['mindmap_description'] ) : '';
    $post_tags = isset( $_POST['mindmap_tags'] ) ? sanitize_text_field( $_POST['mindmap_tags'] ) : '';
    $post_category = isset( $_POST['mindmap_category'] ) ? sanitize_text_field( $_POST['mindmap_category'] ) : '';

    // Create a new post
    $post_data = array(
        'post_title'   => $post_title,
        'post_content' => $post_content, // Set post content to drawing data
        // 'post_excerpt' => $post_content, 
        'post_status'  => 'publish',
        'post_type'    => 'mindmap',
    );

    $post_id = wp_insert_post( $post_data );
    // Save drawing data in post meta
    if ( $post_id ) {
        update_post_meta( $post_id, '_mindmap_json_data', $drawingData );
    }
    // Add tags to the created post
    if ( $post_id && !empty( $post_tags ) ) {
        $tags_array = explode( ',', $post_tags );
        wp_set_post_tags( $post_id, $tags_array );
    }

    // Add category to the created post
    if ( $post_id && !empty( $post_category ) ) {
        // Check if the category already exists
        $term = term_exists( $post_category, 'mindmap_category' );
        $term_id = $term ? $term['term_id'] : wp_insert_term( $post_category, 'mindmap_category' );
        
        // Assign the category to the post
        if ( !is_wp_error( $term_id ) ) {
            wp_set_post_terms( $post_id, $term_id, 'mindmap_category' );
        }
    }

    // Check if the post was successfully created
    if ( $post_id ) {
        wp_send_json_success( 'Post created successfully' );
    } else {
        wp_send_json_error( 'Failed to create post' );
    }
}
// Update drawing AJAX handler
function update_drawing_callback() {
    
    // Get the drawing data and post ID from the request
    $drawing_data = isset( $_POST['drawingData'] ) ? wp_unslash( $_POST['drawingData'] ) : '';
    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

    // Update the meta box value with the drawing data
    if ( $post_id ) {
        // Update meta box value
        update_post_meta( $post_id, '_mindmap_json_data', $drawing_data );

        // Send JSON response
        wp_send_json_success( 'Drawing data updated successfully' );
    } else {
        wp_send_json_error( 'Invalid post ID' );
    }
}
add_action( 'wp_ajax_update_drawing', 'update_drawing_callback' );
// Add meta box
add_action( 'add_meta_boxes', 'mindmap_json_meta_box' );
function mindmap_json_meta_box() {
    add_meta_box(
        'mindmap_json_meta_box', // Meta box ID
        'Mindmap JSON Data', // Meta box title
        'mindmap_json_meta_box_callback', // Callback function to render the meta box content
        'mindmap', // Post type
        'normal', // Context
        'default' // Priority
    );
}

// Render meta box content
function mindmap_json_meta_box_callback( $post ) {
    // Retrieve saved JSON object data
    $mindmap_json_data = get_post_meta( $post->ID, '_mindmap_json_data', true );

    // Output meta box HTML
    ?>
    <label for="mindmap_json_data">Mindmap JSON Data:</label><br>
    <textarea id="mindmap_json_data" name="mindmap_json_data" rows="6" style="width: 100%;"><?php echo esc_textarea( $mindmap_json_data ); ?></textarea>
    <?php
    // Add nonce field for security
    wp_nonce_field( 'mindmap_json_meta_box', 'mindmap_json_meta_box_nonce' );
}

// Save meta box data
add_action( 'save_post', 'save_mindmap_json_data' );
function save_mindmap_json_data( $post_id ) {
    // Check if nonce is set and valid
    if ( !isset( $_POST['mindmap_json_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['mindmap_json_meta_box_nonce'], 'mindmap_json_meta_box' ) ) {
        return;
    }

    // Check if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check user's permissions
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save JSON object data
    if ( isset( $_POST['mindmap_json_data'] ) ) {
        $mindmap_json_data = sanitize_textarea_field( $_POST['mindmap_json_data'] );
        update_post_meta( $post_id, '_mindmap_json_data', $mindmap_json_data );
    }
}
// Display saved JSON object value
function display_saved_mindmap_json_data( $post_id ) {
    // Retrieve saved JSON object data
    $mindmap_json_data = get_post_meta( $post_id, '_mindmap_json_data', true );

    // Check if data exists
    if ( !empty( $mindmap_json_data ) ) {
        echo $mindmap_json_data;
    } else {
        echo 'No JSON data found.';
    }
}
// Add meta box to WooCommerce product
add_action('add_meta_boxes', 'add_mindmaps_meta_box');
function add_mindmaps_meta_box() {
    add_meta_box(
        'mindmaps_meta_box',
        'Mindmaps Shortcode',
        'display_mindmaps_meta_box',
        'product', // Change to your custom post type if not 'product'
        'side',
        'high'
    );
}

// Display content of the meta box
function display_mindmaps_meta_box($post) {
    // Retrieve the saved shortcode value
    $shortcode = get_post_meta($post->ID, '_mindmaps_shortcode', true);

    // Output the input field for the shortcode
    ?>
    <label for="mindmaps_shortcode">Mindmaps Shortcode:</label>
    <textarea id="mindmaps_shortcode" name="mindmaps_shortcode" rows="2" style="width: 100%;"><?php echo  $shortcode ; ?></textarea>
    <?php
}

// Save the meta box data
add_action('save_post_product', 'save_mindmaps_meta_box');
function save_mindmaps_meta_box($post_id) {
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if the current user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if the meta box data is set
    if (isset($_POST['mindmaps_shortcode'])) {
        // Sanitize and save the shortcode
        $shortcode = $_POST['mindmaps_shortcode'];
        update_post_meta($post_id, '_mindmaps_shortcode', $shortcode);
    }
}
// Add custom content to WooCommerce product page
add_action('woocommerce_after_single_product_summary', 'display_mindmaps_shortcode', 5);

function display_mindmaps_shortcode() {
    global $product;

    // Get the product ID
    $product_id = $product->get_id();

    // Check if the current user has purchased the product
    if (is_user_logged_in() && wc_customer_bought_product(wp_get_current_user()->user_email, get_current_user_id(), $product_id)) {
        // Get the stored shortcode value
        $shortcode = get_post_meta($product_id, '_mindmaps_shortcode', true);

        // Check if the shortcode exists and output its content
        if (!empty($shortcode)) {
            echo '<div class="mindmaps-shortcode-output">' . do_shortcode($shortcode) . '</div>';
        }
    }
    else{
        ?>
        <div class="container">
            <div class="row purchase-mindmap-message-row justify-content-center align-items-center mt-5">
                <div class="text-center purchase-mindmap-message">
                    <h4>You need to purchased that before view mindmap!</h4>
                    <hr>
                    <p>To view the mind map, you need to purchase it first. Explore our collection of mind maps to enhance your learning experience.</p>
                </div>

            </div>
        </div>
        <?php
    }
}
// Disable single post view for mindmap custom post type
add_action('template_redirect', 'disable_mindmap_single_view');
function disable_mindmap_single_view() {
    if (is_singular('mindmap') && !current_user_can('administrator')) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    }
}