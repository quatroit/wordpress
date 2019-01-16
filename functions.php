/* отключение стилей contact-form-7 */
function remove_styles () {
    wp_deregister_style ('fancybox');
    wp_deregister_style ('contact-form-7');
    wp_deregister_style ('etc');
}
add_action ('wp_print_styles','remove_styles',100);

/*подключение .js в футере*/
function footer_enqueue_scripts(){
    remove_action('wp_head','wp_print_scripts');
    remove_action('wp_head','wp_print_head_scripts',9);
    remove_action('wp_head','wp_enqueue_scripts',1);
    add_action('wp_footer','wp_print_scripts',5);
    add_action('wp_footer','wp_enqueue_scripts',5);
    add_action('wp_footer','wp_print_head_scripts',5);
}
add_action('after_setup_theme','footer_enqueue_scripts');
