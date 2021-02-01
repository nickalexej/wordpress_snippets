<? 
// # Widgets vom DashBoard entfernen
function tmdn_remove_dashboard_widgets() {
  global $wp_meta_boxes;
  //Widget: Schneller Entwurf
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  //Widget: Auf einen Blick
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  //Widget: Neue Entwürfe
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
  //Widget: Neue Kommentare
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  //Widget: Wordpress-Veranstaltungen und Neuigkeiten
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  //Widget : Aktivitaet
  remove_meta_box('dashboard_activity', 'dashboard', 'normal');
  //Widget: Willkommen
  remove_action( 'welcome_panel', 'wp_welcome_panel' );
}
add_action('wp_dashboard_setup', 'tmdn_remove_dashboard_widgets');

add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
   
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
// ------------------------------------------------------------------------------------------------------ //

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
// ------------------------------------------------------------------------------------------------------ //

// Remove Wordpressicon from Adminbar
function example_admin_bar_remove_logo() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render', 'example_admin_bar_remove_logo', 0 );
// End Adminbar Removal

?>