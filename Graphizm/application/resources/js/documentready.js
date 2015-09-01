(function($) {
    "use strict";

    var contact_form = function() {
        $('#form-contact').modal();
    };

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({"placement": "left"});
        $("#contact-button-modal").click(function() {
            $("#form-contact-msg .alert").remove();
            $("#email-field").prop('disabled', false);
            $("#message-text").prop('disabled', false);
            $("#send-email-btn").prop("disabled", false);
            contact_form();
        });
        $("#send-email-btn").click(function() {
            var mail = $("#email-field").val(),
                msg = $("#message-text").val(),
                trust = $("#antibot").val(),
                target = document.getElementById('send-email-btn'),
                spinner = new Spinner().spin(target);
            $.ajax({
                type: "POST",
                url: "blank.index.php",
                dataType: "json",
                data: { email: mail, message: msg, state: "contact", key: trust }
            })
            .done(function(r) {
                if (r.state === true) {
                    $("#form-contact-msg").html("");
                    $("#form-contact-msg").append('<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4>Le mail a été envoyé avec succès !</h4><p>' + r.messages[0] + '</p></div>');
                    $("#email-field").prop('disabled', true);
                    $("#email-field").val("");
                    $("#message-text").prop('disabled', true);
                    $("#message-text").val("");
                    $("#send-email-btn").prop("disabled", true);
                }
                else {
                    var erreurs = "";
                    for (var i = 0; i < r.messages.length; i++) {
                        erreurs += r.messages[i] + "<br>";
                    }
                    $("#form-contact-msg").html("");
                    $("#form-contact-msg").append('<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4>Oops! Le mail n\'a pas été envoyé !</h4><p>' + erreurs + '</p></div>');
                }
                spinner.stop();
            });
        });
    });
})(jQuery);

