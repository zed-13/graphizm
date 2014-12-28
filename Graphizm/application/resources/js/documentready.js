(function($) {
    "use strict";

    var contact_form = function () {
        $('#form-contact').modal();
    };

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({"placement": "left"});
        $("#contact-button-modal").click(function() {
            contact_form();
        });
    });
})(jQuery);
