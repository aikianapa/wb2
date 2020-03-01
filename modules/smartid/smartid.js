$(document).on("smartid-js", function() {
    $(".wb-smartid").each(function(){
        let smartid = this;
        $(smartid).on("keypress",function(event){
            let char = String.fromCharCode(event.which);
            if (char == " ") char = "_";
            let result = char.match(/^[а-яА-Яa-zA-Z0-9_-]{1,}$/gm);
            if (result == null) return false;
            var val = $(this).val();
            $(this).val(val + char);
            event.preventDefault();
        });
        if ($(smartid).attr("data-furl") !== undefined) {
            let furl = $(smartid).attr("data-furl");
            if ($(document).find(furl).length) {
                $(this).parents("form").find(furl).on("change",function(){
                    let str = wbapp.furl($(this).val());
                    $(smartid).val(str);
                });
            }
            $(smartid).removeAttr("data-furl");
        }
    });
});
