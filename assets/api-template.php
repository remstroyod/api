<?php
defined( 'ABSPATH' ) || exit;

/**
 * Tags List
 * @array
 */
$tags = wp_get_post_tags(get_the_ID());

/**
 * Read Time
 * @string
 */
$controller = new CustomAPI();
$read_time = $controller->estimated_reading_time(get_the_ID());

/**
 * Like and Dislike Activate
 */
$like_dislike = get_post_meta( get_the_ID(), '_api_like_dislike_enable', true );

/**
 * Polls
 */
$polls = get_post_meta( get_the_ID(), '_api_polls', true );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body>

<!-- Article -->
<div class="app_article">

    <!-- Article > Intro -->
    <div class="app_article__intro">

        <!--
        <?php if( $tags ) : ?>
            <div class="app_article__suggesting-reading-tags">

                <?php foreach ( $tags as $tag ) : ?>
                    <p class="app_article__suggesting-reading-tag">
                        <?= $tag->name ?>
                    </p>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
        -->
        <?php
        $title = get_the_title();
        if( ! empty( $title ) && 'Untitled' != $title ) :
        ?>
            <!-- Intro Title -->
            <h2 class="app_article__intro-title">
                <?= get_the_title() ?>
            </h2>
            <!-- End Intro Title -->
        <?php endif; ?>

        <?php if( has_excerpt() ) : ?>
            <!-- Intro Text -->
            <div class="app_article__intro-text">
                <?= the_excerpt() ?>
            </div>
            <!-- End Intro Text -->
        <?php endif; ?>

        <!-- Text Info -->
        <div class="app_article__text-info">
            <?= $read_time ?>
        </div>
        <!-- End Text Info -->

        <?php if( has_post_thumbnail() ) : ?>
            <!-- Intro Image -->
            <div class="app_article__intro-image">
                <?= get_the_post_thumbnail( get_the_ID(), 'full' ) ?>
            </div>
            <!-- End Intro Image -->
        <?php endif; ?>

    </div>
    <!-- End Article > Intro -->

    <!-- Article > Single -->
    <div class="app_article__single">

        <?= the_content() ?>

    </div>
    <!-- End Article > Single -->

    <?php
    if( $like_dislike ) :

        echo do_shortcode('[posts_like_dislike]');

    endif;
    ?>

    <?php if( $polls ) : ?>

        <?php
        $answers    = get_post_meta( $polls['poll'], '_api_polls_answers', true );
        $title      = get_the_title( $polls['poll'] );

        ?>

        <!-- Article > Poll -->
        <div class="app_article__poll">

            <?php if( ! empty( $title ) ) : ?>
                <!-- Poll Question -->
                <h2 class="app_article__poll-question">
                    <?= get_the_title( $polls ) ?>
                </h2>
                <!-- End Poll Question -->
            <?php endif; ?>

            <?php if( $answers ) : ?>
                <div class="apiPolls">
                <?php
                $check = null;
                $check = api_check_polls($polls);

                if( !empty($check) ) :

                    get_template_part('classes/api/pool/template/view', 'bar', ['answers' => $answers, 'polls' => $polls, 'my' => $check ]);

                else:

                    get_template_part('classes/api/pool/template/view', 'polls', ['polls' => $answers ]);

                endif;

                ?>

                </div>

            <?php endif; ?>

        </div>
        <!-- End Article > Poll -->

    <?php endif; ?>

</div>
<!-- End Article -->
</body>
</html>
