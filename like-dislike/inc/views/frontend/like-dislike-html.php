<?php
global $post;
$post_id = $post->ID;
$like_count = get_post_meta($post_id, 'pld_like_count', true);
$dislike_count = get_post_meta($post_id, 'pld_dislike_count', true);
$post_id = get_the_ID();
$pld_settings = get_option('pld_settings');


$already_liked = 0;
$href = 'javascript:void(0)';

/**
 * Cookie Restriction Validation
 *
 */
if (isset($_COOKIE['pld_' . $post_id])) {
    $already_liked = 1;
}

/**
 * IP Restriction Validation
 */
if ($pld_settings['basic_settings']['like_dislike_resistriction'] == 'ip') {
    $liked_ips = get_post_meta($post_id, 'pld_ips', true);
    $user_ip = $this->get_user_IP();
    if (empty($liked_ips)) {
        $liked_ips = array();
    }
    if (in_array($user_ip, $liked_ips)) {
        $already_liked = 1;
    }
}

/**
 * Filters like count
 *
 * @param type int $like_count
 * @param type int $post_id
 *
 * @since 1.0.0
 */
$like_count = apply_filters('pld_like_count', $like_count, $post_id);

/**
 * Filters dislike count
 *
 * @param type int $dislike_count
 * @param type int $post_id
 *
 * @since 1.0.0
 */
$dislike_count = apply_filters('pld_dislike_count', $dislike_count, $post_id);

?>

<?php if( $already_liked == 0 ) : ?>
    <div class="app_article__feedback pld-like-dislike-wrap">

        <!-- Vote feedback text -->
        <div class="app_article__feedback-text pld-message" style="display: none">
            Thanks for your feedback! We will try and only show articles that are relevant to you.
        </div>
        <!-- End feedback text -->

        <!-- Container -->
        <div class="pld-container">

            <!-- Feedback Title -->
            <h2 class="app_article__feedback-title">
                <?= __( 'Was this article useful?' ) ?>
            </h2>
            <!-- End Feedback Title -->

            <!-- Vote variants -->
            <div class="app_article__vote-variants">

                <?php
                /**
                 * Like Dislike Order
                 */
                include(PLD_PATH . '/inc/views/frontend/like.php');
                include(PLD_PATH . '/inc/views/frontend/dislike.php');
                ?>
            </div>
            <!-- End Vote variants -->

        </div>
        <!-- End Container -->

    </div>
<?php endif; ?>