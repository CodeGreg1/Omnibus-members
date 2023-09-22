(function ($) {
"use strict";

    $(document).on('click', '#cookie-consent-box .close-btn', function(){
        $('#cookie-consent-box').addClass('d-none');
    });

    $(document).on('click', '#btn-accept-cookie', function(){

        $('#cookie-consent-box').addClass('d-none');

        $.ajax({
            type: 'POST',
            url: "/cookie-consent",
            success:  function (response) {
                if(response.success){
                    $('#cookie-consent-box').remove();
                }
            }
        });
    });
})(jQuery);