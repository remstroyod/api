<?php
defined( 'ABSPATH' ) || exit;

/**
 * Poll and Answers
 */
$polls      = $args['polls'];
$answers    = $args['answers'];

?>
<!-- Vote Poll Results -->
<div class="app_article__poll-results">

    <?php if( $polls ) : ?>

        <?php
        $percent    = [];
        $totals     = 0;

        /**
         * Totlas All Votes
         */
        foreach ( $polls['ip'] as $key => $item ) :
            $totals = $totals + count($item);
        endforeach;

        /**
         * Percent
         */
        foreach ( $polls['ip'] as $key => $item ) :
            $percent[$key] = (count($item) / $totals) * 100;
        endforeach;

        ?>

        <?php $total_prc = 0; foreach ( $answers as $key => $item ) : ?>
            <?php
            $prc = (array_key_exists( $key, $percent )) ? $percent[$key] : 0;

            $prc = round($prc);

            $total_prc = $total_prc + $prc;

            if( $total_prc > 100 ) $prc = $prc - 1;

            ?>
        <!-- Poll Result -->
        <div
                class="app_article__poll-result <?= ($args['my'] == $key) ? 'app_article__poll-my-result' : '' ?>"
                title="<?= $item['answer'] ?> (<?= $prc ?>%)"
                style="width: <?= $prc ?>%;"
        >
            <span class="app_article__poll-result-text"><?= $item['answer'] ?></span> (<?= $prc ?>%)
        </div>
        <!-- End Poll Result -->
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<!-- End Poll Result -->
