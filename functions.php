/* отключение стилей contact-form-7 */
function remove_styles () {
    wp_deregister_style ('fancybox');
    wp_deregister_style ('contact-form-7');
    wp_deregister_style ('etc');
}
add_action ('wp_print_styles','remove_styles',100);

/* подключение .js в футере */
function footer_enqueue_scripts(){
    remove_action('wp_head','wp_print_scripts');
    remove_action('wp_head','wp_print_head_scripts',9);
    remove_action('wp_head','wp_enqueue_scripts',1);
    add_action('wp_footer','wp_print_scripts',5);
    add_action('wp_footer','wp_enqueue_scripts',5);
    add_action('wp_footer','wp_print_head_scripts',5);
}
add_action('after_setup_theme','footer_enqueue_scripts');

/* увеличение загружаемого файла */
add_filter( 'upload_size_limit', 'PBP_increase_upload' );
function PBP_increase_upload( $bytes )
{
 return 15728640;
}

/*перевод url в нижний регистр*/
if(!is_admin()){
	add_action( 'init', 'vijay_case_sensitive_url' );
}

function vijay_case_sensitive_url(){

	$landing_url = $_SERVER['REQUEST_URI'];

	if(preg_match('/[\.]/', $landing_url)){
		return;
	}

	if(preg_match('/[A-Z]/', $landing_url)){

		$new_url = strtolower($landing_url);
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . $new_url);
		exit(0);
	}
}

/*выводим в rss (фиды) ленту пост тайпы('catalog'-это название пост тайпа) )*/

add_filter('pre_get_posts', 'add_new_post_types_to_feed');
function add_new_post_types_to_feed( $query ) {
    // Выходим если это запрос не фидов
    if( ! $query->is_feed || ! $query->is_main_query() )
        return;

    $query->set( 'post_type', array('post', 'catalog') );
}

/*кол-во записей в рсс*/
add_filter( 'pre_get_posts', 'how_many_posts_display_in_feed' );
function how_many_posts_display_in_feed($query) {
    if( ! $query->is_feed || ! $query->is_main_query() )
        return;

    // этот вариант не работает
    // $query->set( 'posts_per_page', 11 );

    // Сколько записей показывать
    $n = 15;
    add_filter( 'post_limits', create_function('', "return 'LIMIT $n';") );
}


/* Функция для обновления поля ACF */
function update_acf_field(){
	$args = array(
	    'post_type' => 'product', // тип поста
	    'post_status' => 'publish',
	    'posts_per_page' => -1
	);
	$posts = new WP_Query( $args );
	if ( $posts -> have_posts() ) {
	    while ( $posts -> have_posts() ) {
	    	$posts->the_post();
	    	$post_id = get_the_ID();
	        update_post_meta( $post_id, 'field_name', 'field_new_value', 'field_old_value');
	    }
	}
	wp_reset_query();
}


// Функция добавления новых полей в профиль пользователя
add_filter('user_contactmethods', 'my_user_contactmethods');
 
function my_user_contactmethods($user_contactmethods){
 
  $user_contactmethods['twitter'] = 'Twitter Username';
  $user_contactmethods['facebook'] = 'Facebook Username';
 
  return $user_contactmethods;
}
