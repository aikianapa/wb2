function wb_tagsinput() {
    $(".wb-tagsinput").each(function () {
        if ($(this).data("wb-tagsinput") == undefined) {
            $(this).tagsInput({
                // min/max number of characters
                minChars: 0,
                maxChars: null,
                // max number of tags
                limit: null,
                // RegExp
                validationPattern: null,
                // duplicate validation
                unique: true
            });
            $(this).data("wb-tagsinput", true);
            $(this).on("change",function(){
                $(this).attr("value",$(this).val());
            });
        }
    });

}
$(document).off("tagsinput-js");
$(document).on("tagsinput-js", function () {
  setTimeout(function(){
      wb_tagsinput();
  },10);

});

$(document).off("tagsinit-js");
$(document).on("tagsinit-js", function () {
    wbapp.loadScripts(["/engine/modules/tagsinput/tagsinput.js"], "tagsinput-js");
    wbapp.loadStyles(["/engine/modules/tagsinput/tagsinput.css"]);
    $("[data-remove=tagsinit-js]").remove();
});
