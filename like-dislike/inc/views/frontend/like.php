<!-- Vote variant -->
<div class="app_article__vote-variant">
    <a href="<?php echo esc_attr($href); ?>"
       class="pld-like-trigger pld-like-dislike-trigger <?php echo ($already_liked == 1) ? 'pld-prevent' : ''; ?>"
       title=""
       data-post-id="<?php echo intval($post_id); ?>"
       data-trigger-type="like"
       data-restriction="<?php echo esc_attr($pld_settings['basic_settings']['like_dislike_resistriction']); ?>"
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
                version="1.1">
            <path
                    d="M 13.4521,8.59766 C 13.6982,8.1875 13.835,7.75 13.835,7.23047 13.835,6.02734 12.7959,4.90625 11.4834,4.90625 H 10.4717 C 10.6084,4.55078 10.7178,4.14062 10.7178,3.62109 10.7178,1.625 9.67871,0.75 8.12012,0.75 6.4248,0.75 6.53418,3.34766 6.15137,3.73047 5.52246,4.35938 4.78418,5.5625 4.26465,6 H 1.58496 C 1.09277,6 0.709961,6.41016 0.709961,6.875 v 6.5625 c 0,0.4922 0.382809,0.875 0.874999,0.875 h 1.75 c 0.38281,0 0.73828,-0.2734 0.82031,-0.6289 1.23047,0.0273 2.07813,1.0664 4.86719,1.0664 h 0.62891 c 2.10543,0 3.03513,-1.0664 3.06253,-2.5977 0.3828,-0.4921 0.5742,-1.1757 0.4922,-1.832 0.2734,-0.49218 0.3554,-1.09374 0.246,-1.72264 z m -1.6953,1.47654 c 0.3555,0.5742 0.0274,1.3399 -0.3828,1.586 0.2188,1.3125 -0.4922,1.7773 -1.4492,1.7773 H 8.88574 c -1.9414,0 -3.22656,-1.0117 -4.67578,-1.0117 V 7.3125 H 4.4834 c 0.79297,0 1.85937,-1.91406 2.59765,-2.65234 0.76563,-0.76563 0.51954,-2.07813 1.03907,-2.59766 1.28515,0 1.28515,0.90234 1.28515,1.55859 0,1.06641 -0.76562,1.5586 -0.76562,2.59766 h 2.84375 c 0.5742,0 1.0117,0.51953 1.0391,1.03906 0,0.49219 -0.3555,1.01172 -0.6289,1.01172 0.3828,0.41016 0.4648,1.25781 -0.1368,1.80467 z m -8.64059,2.4883 c 0,0.3828 -0.30078,0.6563 -0.65625,0.6563 -0.38281,0 -0.65625,-0.2735 -0.65625,-0.6563 0,-0.3555 0.27344,-0.6563 0.65625,-0.6563 0.35547,0 0.65625,0.3008 0.65625,0.6563 z"
                    fill="#182f50"/>
        </svg>
        <?= __( 'More Like This' ) ?>
<!--        <span class="pld-like-count-wrap pld-count-wrap"> - --><?php //echo esc_html($like_count); ?>
    </a>
</div>
<!-- End Vote variant -->