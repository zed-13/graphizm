(function($) {
    "use strict";

    /**
     * Show or hide elements.
     * @param string identifier.
     */
    var checker = function(o) {
        var id_base = "#" + $(o).attr("class");
        $(id_base + "_check").attr("checked","checked");
        $(id_base).show();
        $(id_base + "_").show();
    };

    /**
     * Toggle elements.
     * @param string identifier.
     */
    var toto = function(o) {
        $("#" + o).toggle();
        $("#" + o + "_").toggle();
        checkNonEmpty();
    };

    /**
     * Checks if at least a gallery is selected.
     */
    var checkNonEmpty = function() {
        if($("input:checkbox:checked").length === 0) {
            $(".btn-style-grpzm").last().parent().append($("<div class='no-gallery js-no-gallery'> Sélectionnez une galerie.</div>"));
        } else {
            $(".js-no-gallery").remove();
        }
    };

    /**
     * Document ready stuff.
     */
    $(document).ready(function() {
        $("input:checkbox").each(function() {
            if($(this).is(":checked")) {
            } else {
                toto($(this).attr("value"));
            }
        });
        $(".checkcheckcheck-yourbooty").each(function() {
            $(this).click(function() {
                checker($(this));
                var a = $(this).parent().find("input:checkbox");
                if(!a.is(":checked")) {
                    a.prop('checked', true);
                    toto(a.attr("value"));
                }
            });
        });
        $(".input-menu_checkbox").each(function() {
            $(this).click(function() {
                toto($(this).val());
            });
        });
        // @TODO : clean up code.
        $("#main").click(function() {
            var ident = $(this).data("value");
            $(".input-menu_checkbox").each(function(){
              if($(this).val() === ident){
                $(this).prop("checked", true);
                $("#" + $(this).val()).show();
                $("#" + $(this).val() + "_").show();
              } else {
                $(this).prop("checked", false);
                $("#" + $(this).val()).hide();
                $("#" + $(this).val() + "_").hide();
              }
            });
            $(".menu-gallery").hide();
            $("#" + ident + "_").hide();
            checkNonEmpty();
            $(this).attr("style", "color:#EFEFEF");
            $("#galleries-link").removeAttr("style");
        });
        $("#main").click();

        $("#galleries-link").click(function() {
            $(".input-menu_checkbox").each(function(){
                  $(this).prop("checked", true);
                  $("#" + $(this).val()).show();
                  $("#" + $(this).val() + "_").show();
            });
            checkNonEmpty();
            $(".menu-gallery").show();
            $("#main").removeAttr("style");
            $(this).attr("style", "color:#EFEFEF");
        });
    });
})(jQuery);
