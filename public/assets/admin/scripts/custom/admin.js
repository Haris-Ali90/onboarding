/*
var Admin = function () {

    return {
        //main function to initiate the module
        init: function () {
            $('#header-logout-link, #leftnav-logout-link').click(function() {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            });
        }

    };

}();
*/

var Admin = function () {

    return {
        //main function to initiate the module
        init: function () {
            $('#header-logout-link, #leftnav-logout-link').click(function(event) {
                event.preventDefault();
                let redirect_url = $(this).attr('data-logout-url');

                let logout_form = $('#logout-form');
                logout_form.attr('action',redirect_url);//.submit();

                setTimeout(function(){ $('#logout-form').submit();}, 3000);

                //logout_form.submit();
                //
                //
                // return false;
                // $()
                // document.getElementById('logout-form').submit();
            });
        }

    };

}();
