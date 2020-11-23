<?php
/*
Plugin Name: Movies Plugin
Description: This is a our Movie plugin,which is used by the students. 
Author: Adarsh Kumar Shah
Text Domain:nm-plugin
Version: 1.0
*/
/*
if(!defined("ABSPATH"))
    exit;
if(!defined("MY_MOVIE_PLUGIN_DIR_PATH"))
	define("MY_MOVIE_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
if(!defined("MY_MOVIE_PLUGIN_URL"))
	define("MY_MOVIE_PLUGIN_URL",plugins_url()."/movies_plugin");
*/

define("PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
define("PLUGIN_URL",plugins_url());
define("PLUGIN_VERSION", '1.0');




// register custom post type movie
add_action( 'init', 'create_post_movie_type' );
function create_post_movie_type() {  // books custom post type
    // set up labels
    $labels = array(
        'name' => 'Movies',
        'singular_name' => 'Movie Item',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Movie Item',
        'edit_item' => 'Edit Movie Item',
        'new_item' => 'New Movie Item',
        'all_items' => 'All Movies',
        'view_item' => 'View Movie Item',
        'search_items' => 'Search Movies',
        'not_found' =>  'No Movies Found',
        'not_found_in_trash' => 'No Movies found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Movies',
    );
    register_post_type(
        'movies',
        array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'hierarchical' => true,
            'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail','page-attributes' ),
            'taxonomies' => array( 'post_tag', 'category' ),
            'exclude_from_search' => true,
            'capability_type' => 'post',
        )
    );
}

// register  taxonomie to go with the Genre Type
add_action( 'init', 'create_taxonomies', 0 );
function create_taxonomies() {
    // Genre-type taxonomy
    $labels = array(
        'name'              => _x( 'Genres', 'taxonomy general name' ),
        'singular_name'     => _x( 'Genre', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Genre' ),
        'all_items'         => __( 'All Genres' ),
        'parent_item'       => __( 'Parent Genre' ),
        'parent_item_colon' => __( 'Parent Genre:' ),
        'edit_item'         => __( 'Edit Genre' ),
        'update_item'       => __( 'Update Genre' ),
        'add_new_item'      => __( 'Add New Genre' ),
        'new_item_name'     => __( 'New Genre' ),
        'menu_name'         => __( 'Genres' ),
    );
    register_taxonomy(
        'Genre',
        'movies',
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'query_var' => true,
            'rewrite' => true,
            'show_admin_column' => true
        )
    );
}
// connection to css,js folder

function custum_movie_plugin(){
	// css and js file
    $slug='';
    $page_include=array('frontendpage','movie-list','movie-add','movie-edit');

    if(empty($currentPage)){
            $actual_link="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if(preg_match("/my_movie/",$actual_link)){
              $currentPage="frontendpage";   
            }
            
        }

    $currentPage=$GET['page'];
    if(in_array($currentPage,$page_include)){

       
    }

    wp_enqueue_style("bootstrap", // unique name
        PLUGIN_URL."/movies_plugin/assest/CSS/bootstrap.min.css", // css file path
    '', // dependency on other file
    PLUGIN_VERSION);

    wp_enqueue_style("datatable", // unique name
        PLUGIN_URL."/movies_plugin/assest/CSS/jquery.dataTables.min.css", // css file path
    '', // dependency on other file
    PLUGIN_VERSION);

    wp_enqueue_style("notifybar", // unique name
        PLUGIN_URL."/movies_plugin/assest/CSS/jquery.notifyBar.css", // css file path
    '', // dependency on other file
    PLUGIN_VERSION);

    wp_enqueue_style("style", // unique name
        PLUGIN_URL."/movies_plugin/assest/CSS/style.css", // css file path
    '', // dependency on other file
    PLUGIN_VERSION);
    
//script
     wp_enqueue_script('jquery');

    wp_enqueue_script("bootstrap.min.js", // unique name
        PLUGIN_URL."/movies_plugin/assest/js/bootstrap.min.js", // css file path
    '', // dependency on other file
    PLUGIN_VERSION,
    true);

    wp_enqueue_script("jquery.validation.min.js", // unique name
        PLUGIN_URL."/movies_plugin/assest/js/jquery.validation.min.js", // css file path
    '', // dependency on other file
    PLUGIN_VERSION,
    true);
    wp_enqueue_script("jquery.datatables.min.js", // unique name
        PLUGIN_URL."/movies_plugin/assest/js/jquery.dataTables.min.js", // css file path
    '', // dependency on other file
    PLUGIN_VERSION,
    true);
     wp_enqueue_script("jquery.notifyBar.js", // unique name
        PLUGIN_URL."/movies_plugin/assest/js/jquery.notifyBar.js", // css file path
    '', // dependency on other file
    PLUGIN_VERSION,
    true);
      wp_enqueue_script("script.js", // unique name
        PLUGIN_URL."/movies_plugin/assest/js/script.js", // css file path
    '', // dependency on other file
    PLUGIN_VERSION,
    true);
    wp_localize_script("script.js","mymovieajaxurl",admin_url("admin-ajax.php"));

}
add_action("init","custum_movie_plugin");

//plugin menu and submenu

function my_movie_plugin_menus(){
	add_menu_page("My Movie","My Movie","manage_options","movie-list","my_movie_list","dashicons-editor-video",30);
	add_submenu_page("movie-list","Movie-list","Movie-list","manage_options","movie-list","my_movie_list");
	add_submenu_page("movie-list","Add New","Add New","manage_options","add-new","my_movie_add");
 // Edit page 
	add_submenu_page("movie-list","","","manage_options","movie-edit","my_movie_edit");
}
add_action("admin_menu","my_movie_plugin_menus");

function my_movie_list(){
	include_once PLUGIN_DIR_PATH."/view/movie-list.php";
}
function my_movie_add(){
	include_once PLUGIN_DIR_PATH."/view/movie-add.php";
}

function my_movie_edit(){
	include_once PLUGIN_DIR_PATH."/view/movie-edit.php";
}


// Database base table generation on activation of plugin

//table generater
function my_movie_list_generate_table_script(){
    global $wpdb;
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

    $sql_query_to_create_table="CREATE TABLE `wp_my_movie_list` (
		 `id` int(11) NOT NULL AUTO_INCREMENT,
		 `movie_name` varchar(255) NOT NULL,
		 `director` varchar(255) NOT NULL,
		 `category` varchar(255) NOT NULL,
		 `description` text NOT NULL,
		 `movie_image` text NOT NULL,
		 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	dbDelta($sql_query_to_create_table);
}
register_activation_hook(__FILE__,"my_movie_list_generate_table_script");

// deactivate table
function my_movie_list_drop_table(){
	global $wpdb;
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    $wpdb->query('DROP table if Exists wp_my_movie_list');

    //step-1: we get the id of the post page
    //delete the page from table

    $the_post_id=get_option("movie_page"); // getting the id of the post name (movie_page)
    if(!empty($the_post_id)){
        delete_option($the_post_id,true);
    	wp_delete_post($the_post_id, true);
    }
}
register_deactivation_hook(__FILE__,"my_movie_list_drop_table");
register_uninstall_hook(__FILE__,"my_movie_list_drop_table");

// creating a dynamic page on activation of plugin

function create_page(){
	// creating page
	$page=array();
	$page['post_title']="movie page";
	$page['post_content']="[movie_page]";
	$page['post_status']="publish";
    $page['post_type']="page";
    $page['post_name']="my_movie";


	$post_id=wp_insert_post($page); // post_id as return value

	add_option("movie_page",$post_id); // with the help of this post_id we can delete the plugin page on deactivation of plugin.
}
register_activation_hook(__FILE__,"create_page");


function page(){
   include_once PLUGIN_DIR_PATH."/view/movie-add.php";
   include_once PLUGIN_DIR_PATH."/view/movie-list.php";
}
add_shortcode("movie_page","page");


// front End of page listioning of book

function page_layout($page_template){
    global $post;
    $page_slug=$post->post_name;

    if($page_slug=="my_movie"){
        $page_template=PLUGIN_DIR_PATH."/view/frontend-movie-template.php";
    }
    return $page_template;
}
add_filter("page_template","page_layout");





function my_movie_table(){
    global $wpdb;
    return $wpdb->prefix."my_movie_list"; // wp_my_book_list
}

add_action("wp_ajax_mymovielibrary","my_movie_ajax_handler");
function my_movie_ajax_handler(){
    if($_REQUEST['param']=="save_movie"){

        print_r($_REQUEST);
        //saving data to database
         global $wpdb;
   $table_name=$wpdb->prefix.'my_movie_list';
   $movie_name=$_REQUEST['movie_name'];
   $director=$_REQUEST['director_name'];
   $category=$_REQUEST['category'];
   $description=$_REQUEST['description'];
   $image=$_REQUEST['image_name'];
       $wpdb->insert($table_name,
                  array(
                        'movie_name'=>$movie_name,
                        'director'=>$director,
                        'category'=>$category,
                        'description'=>$description,
                        'movie_image'=>$image,),
                  array(
                         '%s',
                          '%s',
                          '%s',
                          '%s','%s',)
               );
       echo json_encode(array("status"=>1,"message"=>"movie inserted sucessfully"));
 }
 elseif($_REQUEST['param']=="edit_movie"){
     print_r($_REQUEST);
     global $wpdb;
   $table_name=$wpdb->prefix.'my_movie_list';
   $movie_name=$_REQUEST['movie_name'];
   $director=$_REQUEST['director_name'];
   $category=$_REQUEST['category'];
   $description=$_REQUEST['description'];
   $image=$_REQUEST['image_name'];
       $wpdb->update($table_name,
                  array(
                        'movie_name'=>$movie_name,
                        'director'=>$director,
                        'category'=>$category,
                        'description'=>$description,
                        'movie_image'=>$image,),
                  array("id"=>$_REQUEST['movie_id'])
               );
       echo json_encode(array("status"=>1,"message"=>"movie inserted sucessfully"));
 }
 elseif($_REQUEST['param']=="delete_movie"){
     print_r($_REQUEST);
     global $wpdb;
   $table_name=$wpdb->prefix.'my_movie_list';
       $wpdb->delete($table_name,
                  array("id"=>$_REQUEST['movie_id'])
               );
       echo json_encode(array("status"=>1,"message"=>" sucessfully"));
 }
    wp_die();
}
// implementing isotope
function shortcode_movies_post_type(){
 
    $args = array(
                    'post_type'      => 'movies',
                    'posts_per_page' => '2',
                    'publish_status' => 'published',
                 );
 
    $query = new WP_Query($args);
 
    if($query->have_posts()) :
 
        while($query->have_posts()) :
 
            $query->the_post() ;
                     
        $result .= '<div class="book-item">';
        $result .= '<div class="book-image">' . get_the_post_thumbnail() . '</div>';
        $result .= '<div class="book-name">' . get_the_title() . '</div>';
        $result .= '<div class="book-desc">' . get_the_content() . '</div>';
        $result .= '</div>';

      
        endwhile;
          echo '<button id="load_more">Load More</button>'; 
 
        wp_reset_postdata();
 
    endif;    
 
    return $result;            
}

add_shortcode( 'movie-list', 'shortcode_movies_post_type');


