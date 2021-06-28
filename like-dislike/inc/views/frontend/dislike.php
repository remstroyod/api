<!-- Vote variant -->
<div class="app_article__vote-variant">
    <a href="<?php echo esc_attr($href); ?>"
       class="pld-dislike-trigger pld-like-dislike-trigger <?php echo ($already_liked == 1) ? 'pld-prevent' : ''; ?>"
       title=""
       data-post-id="<?php echo intval($post_id); ?>"
       data-trigger-type="dislike"
       data-restriction="cookie"
       data-already-liked="<?php echo esc_attr($already_liked); ?>">
        <svg
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:cc="http://creativecommons.org/ns#"
                xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                xmlns:svg="http://www.w3.org/2000/svg"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                width="14"
                height="15"
                viewBox="0 0 14 15"
                fill="none"
                version="1.1"
                sodipodi:docname="thumbs-down not Relevant.svg">
            <path
                    d="M 13.0322,6.92969 C 13.1416,6.30078 13.0596,5.69922 12.7861,5.20703 12.8682,4.55078 12.6768,3.86719 12.2939,3.375 12.2666,1.84375 11.3369,0.75 9.23145,0.75 c -0.19141,0 -0.41016,0.027344 -0.62891,0.027344 -2.81641,0 -3.71875,1.066406 -4.8125,1.066406 H 3.48926 C 3.3252,1.73438 3.13379,1.625 2.91504,1.625 h -1.75 C 0.672852,1.625 0.290039,2.03516 0.290039,2.5 v 6.5625 c 0,0.49219 0.382813,0.875 0.875001,0.875 h 1.75 C 3.21582,9.9375 3.5166,9.77344 3.65332,9.5 h 0.19141 c 0.51953,0.46484 1.25781,1.668 1.88672,2.2969 0.38281,0.3828 0.27343,2.9531 1.96875,2.9531 1.55859,0 2.5977,-0.8477 2.5977,-2.8438 0,-0.5195 -0.1094,-0.9296 -0.2461,-1.2851 h 1.0117 C 12.376,10.6211 13.415,9.5 13.415,8.29688 13.415,7.75 13.2783,7.33984 13.0322,6.92969 Z M 2.04004,8.84375 c -0.38281,0 -0.65625,-0.27344 -0.65625,-0.65625 0,-0.35547 0.27344,-0.65625 0.65625,-0.65625 0.35547,0 0.65625,0.30078 0.65625,0.65625 0,0.38281 -0.30078,0.65625 -0.65625,0.65625 z M 11.0635,9.30859 H 8.21973 c 0,1.03911 0.76562,1.53121 0.76562,2.59761 0,0.6563 0,1.5313 -1.28515,1.5313 C 7.18066,12.9453 7.42676,11.6328 6.66113,10.8672 5.92285,10.1289 4.85645,8.1875 4.06348,8.1875 H 3.79004 V 3.10156 c 1.44922,0 2.73437,-1.01172 4.67578,-1.01172 h 1.03906 c 0.95702,0 1.66802,0.46485 1.44922,1.77735 0.4102,0.24609 0.7383,1.01172 0.3828,1.58593 0.6016,0.54688 0.5195,1.39454 0.1367,1.80469 0.2735,0 0.6289,0.51953 0.6289,1.01172 -0.0273,0.51953 -0.4648,1.03906 -1.039,1.03906 z"
                    fill="#182f50" />
        </svg>
        <?= __( 'Not Relevant' ) ?>
<!--        <span class="pld-dislike-count-wrap pld-count-wrap"> - --><?php //echo esc_html($dislike_count); ?><!--</span>-->
    </a>
</div>
<!-- End Vote variant -->