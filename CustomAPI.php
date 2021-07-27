<?php
defined( 'ABSPATH' ) || exit;

/**
 * Include Modules
 */
require get_template_directory() . '/classes/api/like-dislike/like-dislike.php';
require get_template_directory() . '/classes/api/pool/pool.php';

/**
 * REST API
 * @rest_api_init
 */
add_action( 'rest_api_init', 'api_register_rest_routes' );
function api_register_rest_routes() {

    $controller = new CustomAPI();
    $controller->register_routes();

}

/**
 * Create Page Single Article
 * @init
 */
add_action( 'init', 'api_register_rest_rules' );
function api_register_rest_rules() {

    /**
     * Add Rule
     */
    add_rewrite_rule('^(app)/(api)/(v1)/(article)/([^/]*)/?','index.php?article_mobile=$matches[1]&article_api=$matches[2]&article_version=$matches[3]&article_endpoind=$matches[4]&article_url=$matches[5]','top');

    flush_rewrite_rules();
    /**
     * Variables
     * @query_vars
     */
    add_filter( 'query_vars', function ($vars){

        $vars[] = 'article_mobile';
        $vars[] = 'article_api';
        $vars[] = 'article_version';
        $vars[] = 'article_endpoind';
        $vars[] = 'article_url';

        return $vars;

    } );
}

/**
 * Render Article Single
 * @template_include
 */
add_action( 'template_include', 'api_article_templates' );
function api_article_templates($template) {

    $article = get_query_var( 'article_url' );

    if ( $article ) :

        /**
         * args
         */
        $args = [
            'name'      => $article,
            'post_type' => 'post'
        ];

        $query = new WP_Query($args);
        if ($query->have_posts()) :

            while ($query->have_posts()) : $query->the_post();

                if( isset( $_GET['readerId'] ) ) :
                    update_post_meta( get_the_ID(), '_api_article_reader_' . $_GET['readerId'], 1 );
                endif;

                $template = get_template_part('classes/api/assets/api', 'template', [ 'post' => get_the_ID() ]);

            endwhile;

            wp_reset_postdata();

        else:

            $template = '<div class="app_article__empty">';
            $template .= '<p>' . __('Record not found') . '</p>';
            $template .= '</div>';

        endif;

        /**
         *
         */
        echo $template;
        exit();

    endif;

    return $template;

}


/**
 * Add Metabox
 * Like and Dislike Activation
 */
add_action('add_meta_boxes', 'api_article_like_dislike');

/**
 * Add Metabox
 * Like and Dislike Activation
 * @api_article_like_dislike
 */
function api_article_like_dislike() {

    /**
     * Articles Like And Dislike
     */
    add_meta_box(
        'api_metabox_settings',         // $id
        'Like and Dislike',         // $title
        'show_api_metabox_settings',    // $callback
        'post',                         // $page
        'side',                         // $context
        'core'                          // $priority
    );

}

/**
 * @param $post
 */
function show_api_metabox_settings($post) {
    wp_nonce_field( 'api_status_box', 'api_status_box_nonce' );

    $like_dislike = get_post_meta( $post->ID, '_api_like_dislike_enable', true );

    ?>
    <div>
        <h4><?= esc_html_e( 'Like and Dislike' ) ?></h4>
        <div>
            <input type="checkbox" name="_api_like_dislike_enable" value="1"<?php checked( $like_dislike ); ?>/>
            <span><?= _e( 'Activate Likes and Dislikes' ) ?></span>
            <div>
                <em>
                    <small>
                        <?= _e( 'Check if you want to include likes and dislikes in this article' ) ?>
                    </small>
                </em>
            </div>

            <div>
                <?php
                $like_count = get_post_meta($post->ID, 'pld_like_count', true);
                $dislike_count = get_post_meta($post->ID, 'pld_dislike_count', true);
                ?>
                <div><h4><?= esc_html_e( 'Like Count' ) ?> - <span><?= (int) $like_count ?></span></h4></div>
                <div><h4><?= esc_html_e( 'Dislike Count' ) ?> - <span><?= (int) $dislike_count ?></span></h4></div>
            </div>

        </div>
    </div>
    <?php
}

/**
 * Add Metabox
 * Hide Title
 */
add_action('add_meta_boxes', 'api_article_hide_title');

/**
 * Add Metabox
 * Hide Title
 * @api_article_hide_title
 */
function api_article_hide_title() {

    /**
     * Articles Hide Title
     */
    add_meta_box(
        'api_metabox_hide_title',         // $id
        'Hide Title',         // $title
        'show_api_metabox_hide_title',    // $callback
        'post',                         // $page
        'side',                         // $context
        'core'                          // $priority
    );

}

/**
 * @param $post
 */
function show_api_metabox_hide_title($post) {
    wp_nonce_field( 'api_hide_title_box', 'api_hide_title_box_nonce' );

    $hide_title = get_post_meta( $post->ID, '_api_hide_title', true );

    ?>
    <div style="padding-top: 12px">
        <div>
            <input type="checkbox" name="_api_hide_title" value="1"<?php checked( $hide_title ); ?>/>
            <span><?= _e( 'Check this box if you want to hide the page title' ) ?></span>
        </div>
    </div>
    <?php
}

/**
 * Save Metabox
 * Like and Dislike Activation Save
 */
add_action( 'save_post', 'api_article_like_dislike_save' );

/**
 * @param $post_id
 */
function api_article_like_dislike_save($post_id) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['api_status_box_nonce'] ) )
        return $post_id;

    $nonce = $_POST['api_status_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'api_status_box' ) )
        return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

    // Check the user's permissions.
    if ( 'post' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) )
            return $post_id;

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) )
            return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    $field = sanitize_text_field( $_POST['_api_like_dislike_enable'] );
    update_post_meta( $post_id, '_api_like_dislike_enable', $field );

}

/**
 * Save Metabox
 * Hide Title
 */
add_action( 'save_post', 'api_article_hide_title_save' );

/**
 * @param $post_id
 */
function api_article_hide_title_save($post_id) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['api_hide_title_box_nonce'] ) )
        return $post_id;

    $nonce = $_POST['api_hide_title_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'api_hide_title_box' ) )
        return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

    // Check the user's permissions.
    if ( 'post' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) )
            return $post_id;

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) )
            return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    $field = sanitize_text_field( $_POST['_api_hide_title'] );
    update_post_meta( $post_id, '_api_hide_title', $field );

}

/**
 * Fonts, JS
 * @wp_enqueue_scripts
 */
add_action( 'wp_enqueue_scripts', 'api_template_styles' );
function api_template_styles() {

    $api_page = get_query_var( 'article_url' );

    if( $api_page ) :

        /**
         * Disable All Css
         */
        global $wp_styles;
        $array = array();
        foreach ($wp_styles->queue as $handle) :
            $array[] = $handle;
        endforeach;
        wp_dequeue_style($array);
        wp_deregister_style($array);

        wp_dequeue_style('twenty-twenty-one-style');
        wp_dequeue_style('twenty-twenty-one-print-style');

        /**
         * Disable All JS
         */
        global $wp_scripts;
        $array = array();
        // Runs through the queue scripts
        foreach ($wp_scripts->queue as $handle) :
            //print_r($handle);
            if( ! 'pld-frontend' == $handle ) :
                $array[] = $handle;
            endif;
        endforeach;

        wp_dequeue_script($array);
        wp_dequeue_script($array);

        /**
         * remove_action
         */
        remove_action('wp_head', 'wp_generator');
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_action( 'wp_head', 'wlwmanifest_link' );

        /**
         * Enqueue Style
         * @wp_enqueue_style
         */
        wp_enqueue_style( 'api-fonts', 'https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap', [], null );
        wp_enqueue_style( 'api-styles', get_template_directory_uri() . '/classes/api/assets/css/main.css', [], null );


        /**
         * Enqueue Script
         * @wp_enqueue_script
         */
        wp_enqueue_script( 'api-block-ui', get_template_directory_uri() . '/classes/api/assets/js/jquery.blockUI.js', ['jquery'], false, false );
        wp_enqueue_script( 'api-script', get_template_directory_uri() . '/classes/api/assets/js/api.js', ['jquery'], false, false );

        /**
         * wp_head
         */
        remove_action('wp_head', '_admin_bar_bump_cb');

    endif;

}

/**
 * Class CustomAPI
 * @WP_REST_Controller
 */
class CustomAPI extends WP_REST_Controller {

    /**
     * @var string[]
     */
    protected $endpoind = [
        'namespace' => 'api/v1',
        'posts'     => 'articles',
        'post'      => 'article',
    ];

    /**
     * CustomAPI constructor.
     */
    function __construct(){

        $this->namespace = 'api/v1';

    }

    /**
     * Routes
     */
    function register_routes(){

        register_rest_route( $this->namespace, "/" . $this->endpoind['posts'], [
            [
                'methods'             => 'GET',
                'callback'            => [ $this, 'get_items' ],
                'permission_callback' => [ $this, 'get_items_permissions_check' ],
            ],
            'schema' => [ $this, 'get_item_schema' ],
        ] );

        register_rest_route( $this->namespace, "/" . $this->endpoind['post'] . "/(?P<slug>[a-z0-9\-]+)", [
            [
                'methods'   => 'GET',
                'callback'  => [ $this, 'get_item' ],
                'permission_callback' => [ $this, 'get_item_permissions_check' ],
            ],
            'schema' => [ $this, 'get_item_schema' ],
        ] );

    }

    /**
     * @param WP_REST_Request $request
     * @return bool
     */
    function get_items_permissions_check( $request ){
//        if ( ! current_user_can( 'read' ) )
//            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), [ 'status' => $this->error_status_code() ] );

        return true;
    }

    /**
     * @param WP_REST_Request $request
     * @return int[]
     */
    function get_items( $request ){

        $data = [];
        $out = [];


        /**
         * args
         */
        $args = [
            'posts_per_page'    => 10,
            'orderby'           => 'date',
            'order'             => 'ASC',
        ];


        /**
         * Not Reader
         */
        if( isset( $request['readerId'] ) && isset( $request['viewed'] ) ) :

            if( $request['viewed'] === 'true' ) :

                $args['meta_query'] = [
                    'relation' => 'OR',
                    [
                        'key'       => '_api_article_reader_' . $request['readerId'],
                        'value'     => true,
                        'compare'   => '=',
                    ]
                ];

            elseif ( $request['viewed'] === 'false' ) :

                $args['meta_query'] = [
                    'relation' => 'OR',
                    [
                        'key'       => '_api_article_reader_' . $request['readerId'],
                        'value'     => true,
                        'compare'   => 'NOT EXISTS',
                    ]
                ];

            endif;

        endif;

        /**
         * Tags
         */
        if( isset( $request['tags'] ) ) $args['tag'] = explode('%2B', $request['tags']);

        /**
         * Limit
         */
        if( isset( $request['limit'] ) ) $args['posts_per_page'] = (int) $request['limit'];

        /**
         * Paged
         */
        if( isset( $request['offset'] ) ) $args['offset'] = (int) $request['offset'];

        /**
         * Date From > To
         */
        if( isset( $request['createdFrom'] ) && isset( $request['createdTo'] ) ) :

            /**
             * Date From
             */
            $createdFrom_d = date_i18n( 'd', $request['createdFrom'] );
            $createdFrom_m = date_i18n( 'm', $request['createdFrom'] );
            $createdFrom_y = date_i18n( 'Y', $request['createdFrom'] );

            /**
             * Date To
             */
            $createdTo_d = date_i18n( 'd', $request['createdTo'] );
            $createdTo_m = date_i18n( 'm', $request['createdTo'] );
            $createdTo_y = date_i18n( 'Y', $request['createdTo'] );

            $args['date_query'] = [
                [
                    'after'     => [
                        'year'  => $createdFrom_y,
                        'month' => $createdFrom_m,
                        'day'   => $createdFrom_d,
                    ],
                    'before'    => [
                        'year'  => $createdTo_y,
                        'month' => $createdTo_m,
                        'day'   => $createdTo_d,
                    ],
                    'inclusive' => true,
                ]
            ];

        endif;

        /**
         * Date From
         */
        if( isset( $request['createdFrom'] ) && ! isset( $request['createdTo'] ) ) :

            /**
             * Date From
             */
            $createdFrom_d = date_i18n( 'd', $request['createdFrom'] );
            $createdFrom_m = date_i18n( 'm', $request['createdFrom'] );
            $createdFrom_y = date_i18n( 'Y', $request['createdFrom'] );

            /**
             * Date To
             */
            $createdTo_d = date_i18n( 'd', time() );
            $createdTo_m = date_i18n( 'm', time() );
            $createdTo_y = date_i18n( 'Y', time() );

            $args['date_query'] = [
                [
                    'after'     => [
                        'year'  => $createdFrom_y,
                        'month' => $createdFrom_m,
                        'day'   => $createdFrom_d,
                    ],
                    'before'    => [
                        'year'  => $createdTo_y,
                        'month' => $createdTo_m,
                        'day'   => $createdTo_d,
                    ],
                    'inclusive' => true,
                ]
            ];

        endif;

        /**
         * Date To
         */
        if( ! isset( $request['createdFrom'] ) && isset( $request['createdTo'] ) ) :

            $date = '1999-01-01 00:00:00';
            $timestamp = strtotime($date);

            /**
             * Date From
             */
            $createdFrom_d = date_i18n( 'd', $timestamp );
            $createdFrom_m = date_i18n( 'm', $timestamp );
            $createdFrom_y = date_i18n( 'Y', $timestamp );

            /**
             * Date To
             */
            $createdTo_d = date_i18n( 'd', $request['createdTo'] );
            $createdTo_m = date_i18n( 'm', $request['createdTo'] );
            $createdTo_y = date_i18n( 'Y', $request['createdTo'] );

            $args['date_query'] = [
                [
                    'after'     => [
                        'year'  => $createdFrom_y,
                        'month' => $createdFrom_m,
                        'day'   => $createdFrom_d,
                    ],
                    'before'    => [
                        'year'  => $createdTo_y,
                        'month' => $createdTo_m,
                        'day'   => $createdTo_d,
                    ],
                    'inclusive' => true,
                ]
            ];

        endif;

        /**
         * Sort
         */
        if( isset( $request['sort'] ) ) $args['order'] = $request['sort'];

        /**
         *
         */
        $query = new WP_Query;
        $posts = $query->query($args);

        if ( empty( $posts ) )
            return $out;

        /**
         * Posts
         * @array
         */
        foreach( $posts as $post ) :
            $response = $this->prepare_item_for_response( $post, $request );
            $data[] = $this->prepare_response_for_collection( $response );
        endforeach;

        return $data;
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|true|WP_Error
     */
    function get_item_permissions_check( $request ){
        return $this->get_items_permissions_check( $request );
    }

    /**
     * @param WP_REST_Request $request
     * @return array
     */
    function get_item( $request ){

        $data = [];
        $out = [
            'code'      => 'success',
            'message'   => '',
            'data'      => [
                'status' => 200,
            ],
        ];

        $id = $request['slug'];

        $args = [
            'post_type'         => 'post',
            'numberposts'    => 1,
            //'post__in'          => [ $id ],
            'name' => $id,
        ];
        $query = new WP_Query;
        $posts = $query->query($args);


        if( ! $posts )
            return array();

        foreach( $posts as $post ){
            $data[] = $this->prepare_item_for_response( $post, $request );
        }

        $out['data']['response'] = $data;

        return $out;

    }

    /**
     * @param mixed $post
     * @param WP_REST_Request $request
     * @return array
     */
    function prepare_item_for_response( $post, $request ){

        $post_data = [];

        $schema = $this->get_item_schema();

        // We are also renaming the fields to more understandable names.
        if ( isset( $schema['properties']['id'] ) )
            $post_data['id'] = (string) $post->ID;

        if ( isset( $schema['properties']['title'] ) )
            $post_data['title'] = $post->post_title;

        if ( isset( $schema['properties']['subtitle'] ) )
            $post_data['subtitle'] = $post->post_excerpt;

        if ( isset( $schema['properties']['tags'] ) ) :

            $tags = get_the_tags($post->ID);
            $tags_arr = [];
            if( $tags ) :
                foreach ( $tags as $tag ) :
                    $tags_arr[] = $tag->name;
                endforeach;
            endif;

            $post_data['tags'] = $tags_arr;
        endif;

        if ( isset( $schema['properties']['image'] ) ) :
            $featured_media = get_post_thumbnail_id( $post->ID );
            if ( $featured_media ) {
                $post_data['imageUrl'] = wp_get_attachment_image_url($featured_media, 'full');
            }
        endif;

        if ( isset( $schema['properties']['url'] ) )
            //$post_data['articleUrl'] = get_permalink($post->ID);
            $post_data['articleUrl'] = home_url('/app/api/v1/article/' . $post->post_name . '/');


        if ( isset( $schema['properties']['viewed'] ) ) :

            if( isset( $request['readerId'] ) ) :
                if( !empty($request['readerId']) ) :
                    $reader = get_post_meta($post->ID, '_api_article_reader_' . $request['readerId'], true);
                    if( $reader ) :
                        $post_data['viewed'] = true;
                    else:
                        $post_data['viewed'] = false;
                    endif;
                endif;

            else:
                $post_data['viewed'] = false;
            endif;

        endif;

        if ( isset( $schema['properties']['date'] ) )
            $post_data['createdAt'] = get_post_timestamp($post->ID); //get_post( $post->ID )->post_date;

        if ( isset( $schema['properties']['content'] ) )
            $post_data['content'] = apply_filters( 'the_content', $post->post_content, $post );

        return $post_data;
    }

    /**
     * @param WP_REST_Response $response
     * @return array|mixed|WP_REST_Response
     */
    function prepare_response_for_collection( $response ){

        if ( ! ( $response instanceof WP_REST_Response ) ){
            return $response;
        }

        $data = (array) $response->get_data();
        $server = rest_get_server();

        if ( method_exists( $server, 'get_compact_response_links' ) ){
            $links = call_user_func( [ $server, 'get_compact_response_links' ], $response );
        }
        else {
            $links = call_user_func( [ $server, 'get_response_links' ], $response );
        }

        if ( ! empty( $links ) ){
            $data['_links'] = $links;
        }

        return $data;
    }

    /**
     * @return array
     */
    function get_item_schema(){
        $schema = [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'vehicle',
            'type'       => 'object',
            'properties' => [
                'id' => [
                    'description' => 'Unique identifier for the object.',
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit', 'embed' ],
                    'readonly'    => true,
                ],
                'title' => [
                    'description' => 'Title Post',
                    'type'        => 'string',
                ],
                'subtitle' => [
                    'description' => 'SubTitle Post',
                    'type'        => 'string',
                ],
                'tags' => [
                    'description' => 'Tags Post',
                    'type'        => 'object',
                ],
                'image' => [
                    'description' => 'Image Post',
                    'type'        => 'string',
                ],
                'url' => [
                    'description' => __( 'URL to the object.' ),
                    'type'        => 'string',
                    'format'      => 'uri',
                    'context'     => array( 'view', 'edit', 'embed' ),
                    'readonly'    => true,
                ],
                'viewed' => [
                    'description' => 'Viewed Post',
                    'type'        => 'integer',
                ],
                'date'         => array(
                    'description' => __( "The date the object was published, in the site's timezone." ),
                    'type'        => array( 'string', 'null' ),
                    'format'      => 'date-time',
                    'context'     => array( 'view', 'edit', 'embed' ),
                ),
                // TODO
                // []
            ],
        ];

        return $schema;
    }

    /**
     * @return int
     */
    function error_status_code(){
        return is_user_logged_in() ? 403 : 401;
    }

    /**
     * @param $id
     * @return string
     */
    function estimated_reading_time($id) {

        $post = get_post($id);

        $words = str_word_count( strip_tags( $post->post_content ) );
        $minutes = floor( $words / 120 );
        $seconds = floor( $words % 120 / ( 120 / 60 ) );

        if ( 1 <= $minutes ) {
            $estimated_time = $minutes . __(' minute read');
        } else {
            $estimated_time = $seconds . __(' sec read');
        }

        return $estimated_time;

    }

}
