(function($) {
    "use strict";

    function checker(o){
        var id_base = "#" + $(o).attr("class");
        $(id_base + "_check").attr("checked","checked");
        $(id_base).show();
        $(id_base + "_").show();
    }

    function toto(o) {
        $("#" + o).toggle();
        $("#" + o + "_").toggle();
    }

    $(document).ready(
        $("input:checkbox").each(function(i){
            if($(this).is(":checked")) {
            } else {
                toto($(this).attr("value"));
            }
        });
        $("a").tipTip();
    );
})(jQuery);
