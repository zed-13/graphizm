(function($) {
    "use strict";

    /**
     * Show or hide elements.
     * @param string identifier.
     */
    var checker = function(o){
        var id_base = "#" + $(o).attr("class");
        $(id_base + "_check").attr("checked","checked");
        $(id_base).show("slow");
        $(id_base + "_").show("slow");
    };

    /**
     * Toggle elements.
     * @param string identifier.
     */
    var toto = function(o) {
        $("#" + o).toggle("slow");
        $("#" + o + "_").toggle("slow");
    };

    /**
     * Document ready stuff.
     */
    $(document).ready(function(){
        $("input:checkbox").each(function(){
            if($(this).is(":checked")) {
            } else {
                toto($(this).attr("value"));
            }
        });
        $(".checkcheckcheck-yourbooty").each(function() {
            $(this).click(function() {
                checker($(this));
            });
        });
        $(".input-menu_checkbox").each(function() {
            $(this).click(function() {
                toto($(this).val());
            });
        });
        $("a").tipTip();
    });
})(jQuery);
