<?php
defined('ABSPATH') or die('No script kiddies please');


class PLD_Comments_like_dislike {

    function __construct() {
        $this->define_constants();
        $this->includes();
    }

    /**
     * Include all the necessary files
     *
     * @since 1.0.0
     */
    function includes() {
        require_once PLD_PATH . '/inc/classes/pld-library.php';
        require_once PLD_PATH . '/inc/classes/pld-init.php';
        require_once PLD_PATH . '/inc/classes/pld-enqueue.php';
        require_once PLD_PATH . '/inc/classes/pld-hook.php';
        require_once PLD_PATH . '/inc/classes/pld-ajax.php';
    }

    /**
     * Define necessary constants
     *
     * @since 1.0.0
     */
    function define_constants() {

        defined('PLD_PATH') or define('PLD_PATH', dirname(__FILE__));
        defined('PLD_JS_DIR') or define('PLD_JS_DIR', get_template_directory_uri() . '/classes/api/like-dislike/js');
        defined('PLD_VERSION') or define('PLD_VERSION', '1.0.5');
        defined('PLD_TD') or define('PLD_TD', 'posts-like-dislike');
        defined('PLD_BASENAME') or define('PLD_BASENAME', basename(__FILE__));
    }

}

$pld_object = new PLD_Comments_like_dislike();