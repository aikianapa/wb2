$(document).on("dict-js",function(){
   $(".wb-dict").each(function () {
        if ($(this).data("wb-dict") == undefined) {
            $(this).undelegate(".wb-tree-dict-prop-btn");
            $(this).delegate(".wb-tree-dict-prop-btn","click",function(){
                var modal = wbapp.getModal();
                var title = $(this).next("input").val();
                var type = $(this).parents(".wb-multiinput-row").find("[data-wb-field=type]").val();
                var prop = $(this).parents(".wb-multiinput-row").find("[data-wb-field=prop]");
                $(modal).attr("id",wbapp.newId());
                console.log($(modal));
                $(modal).modal({"backdrop":"static"}).modal("show")
                    .on("hidden.bs.modal",function(){
                        var data = $(modal).find(".modal-body form").serializeJson();
                        $(prop).jsonVal(data);  
                        $(modal).remove();
                    })
                    .on("shown.bs.modal",function(){
                        $(this).runScripts();
                    });
                var res = wbapp.postWait("/ajax/tree_getform/prop/", {"dict":$(prop).jsonVal(),"type":type});
                $(modal).find(".modal-body").html(res.content);
                $(modal).find(".modal-dialog").addClass("modal-xl");
                if ($(modal).find(".modal-body form",0).attr("data-title") !== undefined) {
                    title = $(modal).find("form",0).attr("data-title") + title;
                }
                $(modal).find(".modal-title").html(title);
            });
            
            $(this).undelegate(".wb-tree-dict-lang-btn");
            $(this).delegate(".wb-tree-dict-lang-btn","click",function(){
                var modal = wbapp.getModal();
                var title = $(this).next("input").val();
                var lang = $(this).next("input").next("textarea");
                $(modal).attr("id",wbapp.newId());
                $(modal).modal({"backdrop":"static"}).modal("show")
                    .on("hidden.bs.modal",function(){
                        var data = $(modal).find(".modal-body form").serializeJson();
                        $(lang).jsonVal(data);
                        $(modal).remove();
                    })
                    .on("shown.bs.modal",function(){
                        $(this).runScripts();
                    });
                var res = wbapp.postWait("/ajax/tree_getform/lang/", {"dict":$(lang).jsonVal()});
                $(modal).find(".modal-body").html(res.content);
                if ($(modal).find(".modal-body form",0).attr("data-title") !== undefined) {
                    title = $(modal).find("form",0).attr("data-title") + title;
                }
                $(modal).find(".modal-title").html(title);
            });
            
            $(this).data("wb-dict", true);
        }
   });
});