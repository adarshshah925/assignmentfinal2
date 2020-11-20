<?php
/*
Plugin Name: Movies Plugin
Description: This is a our Movie plugin,which is used by the students. 
Author: Adarsh Kumar Shah
Text Domain:movie-plugin
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

    $the_post_id=get_option("plugin_page"); // getting the id of the post name (plugin_page)
    if(!empty($the_post_id)){
    	wp_delete_post($the_post_id, true);
    }
}
register_deactivation_hook(__FILE__,"my_movie_list_drop_table");
register_uninstall_hook(__FILE__,"my_movie_list_drop_table");

// creating a dynamic page on activation of plugin

function create_page(){
	// creating page
	$page=array();
	$page['post_title']="Online Movie Management";
	$page['post_content']="Entertainment Platform for the All";
	$page['post_status']="publish";
	$page['page_slug']="Online Movie Management";
	$page['post_title']=" Online Movie Management";
	$page['post_type']="page";
    
	//$post_id=wp_insert_post($page); // post_id as return value

	//add_option("plugin_page",$post_id); // with the help of this post_id we can delete the plugin page on deactivation of plugin.
	wp_insert_post($page);
}
register_activation_hook(__FILE__,"create_page");

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


// implementing isotope on movie list

add_shortcode('isotope',function($atts,$content=null){
    
    wp_enqueue_script('isotope-js','https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js',array(),true);
    
    $query = new WP_Query(array(
        'post_type'=>'movies',
        'posts_per_page'=>9
    ));
    if($query->have_posts()){
        $posts = [];
        $all_categories=[];
        $all_tags = [];
        while($query->have_posts()){
            $query->the_post();
            global $post;
            $category = wp_get_object_terms($post->ID,'category');
            $tag = wp_get_object_terms($post->ID,'post_tag');
            if(!empty($category)){
                $post->cats=[];
                foreach($category as $cat){
                     $post->cats[]=$cat->slug;
                    if(!in_array($cat->term_id,array_keys($all_categories))){
                        $all_categories[$cat->term_id]=$cat;
                    }
                }
            }
            if(!empty($tag)){
                $post->tags=[];
                foreach($tag as $t){
                    $post->tags[] = $t->slug;
                    if(!in_array($t->term_id,array_keys($all_tags))){
                        $all_tags[$t->term_id]=$t;
                    }
                }
            }
            $posts[] = $post;
        }
        wp_reset_postdata();

        echo '<div class="isotope_wrapper"><div>';
        if(!empty($all_categories)){
            ?>
            <ul class="post_categories">
            <?php
                foreach($all_categories as $category){
                    ?>
                <li class="grid-selector" data-filter="<?php echo $category->slug; ?>"><?php echo $category->name; ?></li>
                     <?php
                }
            ?>
            </ul>
            <?php
        }
        if(!empty($all_tags)){
            ?>
            <ul class="post_tags">
            <?php
                foreach($all_tags as $category){
                    ?>
                <li class="grid-subselector" data-filter="<?php echo $category->slug; ?>"><?php echo $category->name; ?></li>
                     <?php
                }
            ?>
            </ul>
            <?php
        }
        ?>
        </div>
        <div class="grid">
        <?php
        foreach($posts as $post){
            ?>
            <div class="grid-item <?php echo empty($post->cats)?'':implode(',',$post->cats); ?> <?php echo empty($post->tags)?'':implode(',',$post->tags); ?>">
                
                <h2>
                    <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
                </h2>
            </div>
            <?php
        }
        ?>
        </div></div>
        <script>
            window.addEventListener('load',function(){
                var iso = new Isotope( document.querySelector('.grid'), {
                  itemSelector: '.grid-item',
                  layoutMode: 'fitRows'
                });
                document.querySelectorAll('.grid-selector').forEach(function(el){

                    el.addEventListener('click',function(){
                        
                        let sfilter = el.getAttribute('data-filter');

                        iso.arrange({
                          filter: function( gridIndex, itemElem ) {
                            return itemElem.classList.contains(sfilter);
                          }
                        });
                        
                    });
                });


                document.querySelectorAll('.grid-subselector').forEach(function(el){

                    el.addEventListener('click',function(){
                        
                        let sfilter = el.getAttribute('data-filter');

                        iso.arrange({
                          filter: function( gridIndex, itemElem ) {
                            return itemElem.classList.contains(sfilter);
                          }
                        });
                        
                    });
                });
                
            });
        </script>
        <style>
            .isotope_wrapper {
                display: flex;
                flex-direction: column;
            }

            .isotope_wrapper > div {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                margin: 0 -1rem;
                justify-content: space-between;
            }

            .isotope_wrapper > div > ul {
                display: flex;
                flex-wrap: wrap;
                margin: 1rem;
            }

            .isotope_wrapper > div>div {
                padding: 1rem;
                border: 1px solid #eee;
                margin: 1rem;
            }

            .isotope_wrapper > div > ul > li {
                padding: 0.5rem 1rem;
                background: #eee;
                margin: 2px;cursor:pointer;
                border-radius: 4px;
            }
        </style>
        <?php
    }
});

//load more button
add_action( 'wp_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {
        var page_count='<?php echo ceil(wp_count_posts('post')->publish/2); ?>';
        var ajaxurl='<?php echo admin_url('admin-ajax.php');?>';
        var page=2;
        jQuery('#load_more').click(function(){
        var data = {
            'action': 'my_action',
            'whatever': page,
        };
        jQuery.post(ajaxurl, data, function(response) {
            jQuery('.book-item').append(response);
            if(page_count==page){
                jQuery('#load_more').hide();
            }
            page=page + 1;
        });
    });
   });
    </script> <?php
}
add_action( 'wp_ajax_my_action', 'my_action' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action' );
function my_action() {
    global $wpdb; // this is how you get access to the database
        $args=array(
   'post_type'=>'movies',
   'paged'=>$_POST['page'],
   );
    $the_query = new WP_Query( $args );
     
    // The Loop
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            echo '<li>' . get_the_title() . '</li>';
        }
    } else {
        // no posts found
    }
    /* Restore original Post Data */
    wp_reset_postdata();
    wp_die(); 
}