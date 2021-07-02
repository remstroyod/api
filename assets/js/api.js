jQuery(document).ready(function() {

    jQuery(document).on('click', '.apiPollsItem', function(e) {
        e.preventDefault();

        let $this       = jQuery(this),
            $container  = jQuery('.apiPolls'),
            id          = $this.data('id'),
            post        = $this.data('post'),
            readerId    = $this.data('reader');

        jQuery.ajax( {
            beforeSend  :   function(){

                $container.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

            },
            data        : {
                action      : 'api_polls_action',
                id          : id,
                post        : post,
                readerId    : readerId
            },
            dataType    :   'json',
            method      :   'POST',
            complete    :   function(){

                $container.unblock();

            },
            success     :   function( response ){

                $container.html(response.data.html);

            },
            url         :   api_js_object.admin_ajax_url,
        } );

    });

});
