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
        $hide_title = get_post_meta( get_the_ID(), '_api_hide_title', true );
        if( ! $hide_title ) :
            $title = get_the_title();
            if( ! empty( $title ) && 'Untitled' != $title ) :
                ?>
                <!-- Intro Title -->
                <h2 class="app_article__intro-title">
                    <?= get_the_title() ?>
                </h2>
                <!-- End Intro Title -->
            <?php endif; ?>
        <?php endif; ?>


        <?php if( has_excerpt() ) : ?>
            <!-- Intro Text -->
            <div class="app_article__intro-text">
                <?= the_excerpt() ?>
            </div>
            <!-- End Intro Text -->
        <?php endif; ?>

        <!-- Text Info -->
<!--        <div class="app_article__text-info">-->
<!--            --><?//= $read_time ?>
<!--        </div>-->
        <!-- End Text Info -->



    </div>
    <!-- End Article > Intro -->

    <?php if( has_post_thumbnail() ) : ?>

        <?php
        $categories = get_the_category();

        $cat = '';
        if( $categories ) :
            $cat .= '<div class="badges__list">';
            $cat .= '<ul>';
            foreach ( $categories as $category ) :
                $cat .= '<li><span>' . $category->name .'</span></li>';
            endforeach;
            $cat .= '</ul>';
            $cat .= '</div>';
        endif;
        ?>

        <!-- Intro Image -->
        <div class="app_article__intro-image">
            <?= $cat ?>
            <?= get_the_post_thumbnail( get_the_ID(), 'full' ) ?>
        </div>
        <!-- End Intro Image -->
    <?php endif; ?>

    <!-- Article > Single -->
    <div class="app_article__single">

        <?= the_content() ?>

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
                        <?= get_the_title( $polls['poll'] ) ?>
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
    <!-- End Article > Single -->



</div>
<!-- End Article -->
</body>
</html>
