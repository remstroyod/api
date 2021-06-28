<?php
defined( 'ABSPATH' ) || exit;

/**
 * Polls
 */
$polls = $args['polls'];

?>

<!-- Poll options -->
<div class="app_article__poll-options">

    <?php foreach ( $polls as $key => $answer ) : ?>
        <?php if( ! empty( $answer['answer'] ) ) : ?>
            <!-- Poll option -->
            <div class="app_article__poll-option apiPollsItem" data-id="<?= $key ?>" data-post="<?= get_the_ID() ?>">
                <?= $answer['answer'] ?>
            </div>
            <!-- End Poll option -->
        <?php endif; ?>
    <?php endforeach; ?>

</div>
<!-- End Poll options -->