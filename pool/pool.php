<?php
defined( 'ABSPATH' ) || exit;

/**
 * init
 */
add_action( 'init', 'api_pool_register_taxonomies' );
function api_pool_register_taxonomies() {

    /**
     * Polls
     */
    $labels = [
        'name'                  => __( 'Polls' ),
        'singular_name'         => __( 'Poll' ),
        'add_new'               => __( 'Add New Poll' ),
        'add_new_item'          => __( 'Add New Poll' ),
        'edit_item'             => __( 'Edit Poll' ),
        'new_item'              => __( 'New Poll' ),
        'view_item'             => __( 'View Poll' ),
        'search_items'          => __( 'Search Poll' ),
        'all_items'             => __( 'All Polls' ),
    ];

    $args = [
        "label"                 => __( 'Polls' ),
        "labels"                => $labels,
        "description"           => "",
        "public"                => true,
        "publicly_queryable"    => true,
        "show_ui"               => true,
        "show_in_rest"          => true,
        "rest_base"             => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive"           => false,
        "show_in_menu"          => true,
        "show_in_nav_menus"     => true,
        "delete_with_user"      => false,
        "exclude_from_search"   => false,
        "capability_type"       => "post",
        "map_meta_cap"          => true,
        "hierarchical"          => false,
        "rewrite"               => false,
        "query_var"             => true,
        "menu_icon"             => "dashicons-clipboard",
        "supports"              => ["title"],
    ];

    register_post_type("api_polls", $args);

}

/**
 * Create Panel List
 */
add_action('edit_form_after_title', 'api_pool_admin_create_section' );
function api_pool_admin_create_section($post) {
    global $pagenow;

    if( 'post.php' === $pagenow && 'api_polls' == get_post_type() ) :

        $polls = get_post_meta( $post->ID, '_api_polls_answers', true );

        ?>

        <div class="api_pool__container">

            <div class="api_pool__container__empty <?= ($polls) ? 'hide' : '' ?>">
                <h4><?= __('The list is empty') ?></h4>
            </div>

            <ul class="api_pool__list">

                <?php if( $polls ) : ?>
                    <?php foreach ($polls as $key => $poll) : ?>
                        <li>
                            <div class="api_pool__list__item">
                                <span>0</span>
                                <input type="text" value="<?= $poll['answer'] ?>" name="api_poll_answer[<?= $key ?>]">
                                <a href="#remove" class="button api_poll__remove">x</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

            </ul>

        </div>

        <div>
            <a href="#add" class="api_pool__btn button button-primary button-large"><?= __( 'Add answer' ) ?></a>
        </div>

        <?php

    endif;

}

/**
 * Save Post Meta
 */
add_action( 'save_post', 'api_save_pool_meta' );
function api_save_pool_meta( $post_id ) {

    $slug = 'api_polls';

    if ( $slug != $_POST['post_type'] )
        return;

    if ( isset( $_REQUEST['api_poll_answer'] ) ) :

        $polls = [];
        foreach ( $_REQUEST['api_poll_answer'] as $key => $item ) :
            $polls[$key]['answer']  = $item;
        endforeach;

        update_post_meta( $post_id, '_api_polls_answers', $polls );

    endif;

}

/**
 * Script and CSS
 */
add_action( 'admin_print_footer_scripts', 'api_pool_admin_footer' );
function api_pool_admin_footer() {
    ?>
    <style>
        #edit-slug-box {
            display: none;
        }
        .api_pool__container {
            padding: 15px;
            background-color: #fff;
            border: 1px solid #c3c4c7;
            margin: 0 0 15px 0;
        }
        .api_pool__btn {

        }
        .api_pool__list__item {
            display: flex;
        }

        .api_pool__list__item span {
            width: 50px;
            text-align: center;
            border: 1px solid #8c8f94;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .api_pool__list__item [type="text"] {
            width: 100%;
        }
        .api_pool__list__item a {
            width: 30px;
            height: 30px;
            text-align: center;
            border: 1px solid #c3c4c7;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .api_pool__container__empty.hide {
            display: none;
        }


    </style>
    <script>
        jQuery(document).ready(function() {


            jQuery(document).on('click', '.api_pool__btn', function(e) {
                e.preventDefault();

                const random_id = Math.floor(Math.random() * (9999999 - 1 + 1)) + 1;

                jQuery('.api_pool__container__empty').hide();
                jQuery('.api_pool__list').append(
                    '<li>' +
                    '<div class="api_pool__list__item">' +
                    '<span>0</span>' +
                    '<input type="text" value="" name="api_poll_answer[' + random_id + ']">' +
                    '<a href="#remove" class="button api_poll__remove">x</a>' +
                    '</div>' +
                    '</li>'
                )

            });


            jQuery(document).on('click', '.api_poll__remove', function(e) {
                e.preventDefault();

                jQuery(this).parent().parent().remove();

                if( jQuery('.api_pool__list li').length == 0  ) {
                    jQuery('.api_pool__container__empty').show();
                }


            });


        });
    </script>
    <?php
}

/**
 * Add Metabox
 * Polls Activation
 */
add_action('add_meta_boxes', 'api_polls_metabox');

/**
 * Add Metabox
 * Polls
 * @api_polls_metabox
 */
function api_polls_metabox() {

    /**
     * Polls Metabox
     */
    add_meta_box(
        'api_metabox_polls',            // $id
        'Attach a poll to the post',    // $title
        'show_api_metabox_polls',       // $callback
        'post',                         // $page
        'side',                         // $context
        'core'                          // $priority
    );

}

/**
 * @param $post
 */
function show_api_metabox_polls($post){
    wp_nonce_field( 'api_polls_box', 'api_polls_box_nonce' );

    $polls = get_post_meta( $post->ID, '_api_polls', true );
    $query      = new WP_Query;
    $all_query  = $query->query( array(
        'post_type'         => 'api_polls',
        'posts_per_page'    => -1,

    ) );
    ?>
    <div style="padding-top: 12px">

        <select name="_api_polls" id="_api_polls" style="width: 100%;box-sizing: border-box">
            <option value="0"><?= __( 'Select Poll' ) ?></option>

            <?php foreach( $all_query as $item ) : ?>
                <?php
                $selected = null;
                if( $polls ) :
                    if( $item->ID == $polls['poll'] ) :
                        $selected = 'selected="selected"';
                    endif;
                endif;
                ?>
                <option value="<?php echo esc_attr($item->ID); ?>" <?= $selected ?>>
                    <?php echo esc_html($item->post_title ); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div style="padding-top: 8px">
            <em>
                <small>
                    <?= _e( 'Attaching a questionnaire to the article' ) ?>
                </small>
            </em>
        </div>

    </div>
    <?php
}

/**
 * Add Metabox
 * Like and Dislike Activation Save
 */
add_action( 'save_post', 'show_api_metabox_polls_save' );

/**
 * @param $post_id
 */
function show_api_metabox_polls_save($post_id) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['api_polls_box_nonce'] ) )
        return $post_id;

    $nonce = $_POST['api_polls_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'api_polls_box' ) )
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

    $field = sanitize_text_field( $_POST['_api_polls'] );

    if( $field == 0 ) :

        delete_post_meta( $post_id, '_api_polls' );

    else:

        if( ! empty($field) ) :

            $polls  = get_post_meta( $post_id, '_api_polls', true );
            $arr['poll'] = $field;
            $arr['post'] = $post_id;
            $arr['ip'] = ( $polls['ip'] ) ? $polls['ip'] : [];
            update_post_meta( $post_id, '_api_polls', $arr );

        endif;

    endif;



}

/**
 * Ajax Pool
 */
add_action( 'wp_ajax_api_polls_action', 'api_polls_action' );
add_action( 'wp_ajax_nopriv_api_polls_action', 'api_polls_action' );

function api_polls_action() {

    /**
     * POST
     */
    $id     = absint($_POST['id']);
    $item   = absint($_POST['post']);
    $readerId   = $_POST['readerId'];

    $poll   = get_post_meta( $item, '_api_polls', true );

    $html   = null;

    if( ! empty( $readerId ) ) :

        if( $poll ) :

            $ip = api_get_user_IP();
            $poll['ip'][$id][$readerId] = $readerId;
            update_post_meta( $item, '_api_polls', $poll );

        endif;

    endif;

    $answers  = get_post_meta( $poll['poll'], '_api_polls_answers', true );
    $polls = get_post_meta( $item, '_api_polls', true );

    ob_start();
    get_template_part('classes/api/pool/template/view', 'bar', ['answers' => $answers, 'polls' => $polls, 'my' => $id ]);
    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json_success([
            'html' => $html
    ]);
    wp_die();

}

/**
 * Get User IP
 * @return mixed
 */
function api_get_user_IP() {
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

/**
 * @param array $answers
 * @return int|string
 */
function api_check_polls($answers = []){

    $ip         = api_get_user_IP();
    $out        = 0;
    $readerId   = $_GET['readerId'];

    if( $answers['ip'] ) :
        foreach ( $answers['ip'] as $key => $item ) :
            $poll = array_key_exists($readerId, $item);

            if ($poll) :
                $out = $key;
                return $out;
            endif;

        endforeach;
    endif;

}
