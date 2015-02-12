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
        $("#send-email-btn").click(function() {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                mail = $("#email-field").val(),
                msg = $("#message-text").val();

            $.ajax({
                type: "POST",
                url: "blank.index.php",
                dataType: "json",
                data: { email: mail, message: msg, state: "contact" }
            })
            .done(function(r) {
                if (r.state === true) {
                    $("#form-contact-msg").html("");
                    $("#form-contact-msg").append('<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4>Le mail a été envoyé avec succès !</h4><p>' + r.messages[0] + '</p></div>');
                }
                else {
                    var erreurs = "";
                    for (var i = 0; i < r.messages.length; i++) {
                        erreurs += r.messages[i] + "<br>";
                    }
                    $("#form-contact-msg").html("");
                    $("#form-contact-msg").append('<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4>Oops! Le mail n\'a pas été envoyé !</h4><p>' + erreurs + '</p></div>');
                }
            });
        });
    });
})(jQuery);
