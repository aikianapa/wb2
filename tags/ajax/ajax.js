$(document).undelegate("[data-ajax]","click");
$(document).delegate("[data-ajax]","click",function(){
    var link = this;
    if ($(link).is(".select2")) return;
    var params = json_decode($(link).attr("data-ajax"));
    var url = params.url;
    var data = $(this).attr("data");
    if (data == undefined) {data = {};} else {data = $.parseJSON(data);}
    if ($(link).attr("data-filter") !== undefined) data._filter = $.parseParams($(link).attr("data-filter"));
    var result = wbapp.postWait(url,data);
    var actions=["remove","after","before","html","replace","append","prepend","value","data"];
    var selector = params.selector;
    if (selector !== undefined) {
        selector = selector.split("->");
        if (selector.length > 1)  var value = trim(selector[1]);
        selector = trim(selector[0]);
        var content = $("<html></html>");
        $(content).html(result);
        if (value !== undefined) {
            eval('result = $(content).find(selector).'+value+'()');
        } else {
            result = $(content).find(selector);
        }
    }
    $(actions).each(function(i,a) {
        if (params[a] !== undefined) {
            tmp = result;
            if (typeof tmp === 'object' && tmp.prevObject == undefined) tmp=json_encode(tmp);
            eval('$(params[a]).'+a+'(tmp);');
        }
    });
    if ($(link).attr("data-callback") !== undefined) eval($(link).attr("data-callback")+'(result)');
    if (typeof $.fn.tooltip === 'function') $(document).find('[data-toggle="tooltip"]').tooltip();
    $(link).trigger("wb-ajax-done",result);
    $(link).trigger("wbapp-js",result);
});
