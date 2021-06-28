<?php



    class PLD_Enqueue {

        /**
         * Includes all the frontend and backend JS and CSS enqueues
         * 
         * @since 1.0.0
         */
        function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );

        }

        function register_frontend_assets() {
            /**
             * Fontawesome 5 support
             *
             * @version 1.0.6
             */
            wp_enqueue_script( 'pld-frontend', PLD_JS_DIR . '/pld-frontend.js', array( 'jquery' ), PLD_VERSION );
            $ajax_nonce = wp_create_nonce( 'pld-ajax-nonce' );

            $js_object = array( 'admin_ajax_url' => admin_url( 'admin-ajax.php' ), 'admin_ajax_nonce' => $ajax_nonce );
            wp_localize_script( 'pld-frontend', 'api_js_object', $js_object );
        }


    }

    new PLD_Enqueue();
