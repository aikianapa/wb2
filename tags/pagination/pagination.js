$(document).one("pagination-js", function() {

  $(document).find(".pagination .page-more[data-trigger]").each(function(){
      var more = this;
      var selector = $(this).attr("data-trigger");

      $(document).find(selector).on("click tap",function(){
          $(more).find(".page-link").trigger("click");
      });

  });

  $(document).delegate(".pagination .page-link", "click", function(e) {
    e.preventDefault();
    var paginator = $(this).closest(".pagination");
    var pid = $(paginator).attr("id");
    var tplid = pid.substr(5);
    var item = $(this).parent("[data-page]");
    if (item.is("[data-page=next]")) {
      $(this).closest(".pagination").find(".page-item.active").next(".page-item:not([data-page=next])").children(".page-link").trigger("click");
      return;
    } else if (item.is("[data-page=more]")) {
      $(this).closest(".pagination").find(".page-item.active").next(".page-item:not([data-page=next])").children(".page-link").trigger("click");
      return;
    } else if (item.is("[data-page=prev]")) {
      $(this).closest(".pagination").find(".page-item.active").prev(".page-item:not([data-page=prev])").children(".page-link").trigger("click");
      return;
    } else {
      $(this).wbPagination();
    }
    //$(document).find(".pagination[id=" + pid + "] .page-item").removeClass("active");
    //$(document).find(".pagination[id=" + pid + "] .page-item:nth-child(" + ($(this).parent("li").index() + 1) + ")").addClass("active");
  });


  $.fn.wbPagination = function() {
    var paginator = $(this).closest(".pagination");
    var that = $(this);
    var id = $(paginator).attr("id");
    var tid = $(paginator).attr("id").split("-")[1];

    console.log("Trigger: pagination-click");
    $(document).trigger("pagination-click",that);


    //=======//
    // Short function
    var tpl = tid;
    var page = explode("/", $(this).attr("data-wb-ajaxpage"));
    var c = count(page);
    var pagenum = page[c - 2];
    var params = wbapp.template(tpl).params;
    var uri = params.route.uri;
    var result = wbapp.postWait(uri, {
      _watch_page: pagenum
    });
    pager = $(result).find(".pagination#ajax-"+tpl).html();
    result = $(result).find("[data-wb-tpl='"+tpl+"']");
    $(result).find("script[type='text/locale'],template").remove();
    result = $(result).html();
    $("body").removeClass("cursor-wait");
    wbapp.watcher[tpl].page(result);
//    window.location.hash = "page-" + idx + "-" + pagenum;
    $(document).find(".pagination#ajax-"+tpl).html(pager);
    console.log(pager);
    $("body").removeClass("cursor-wait");
    console.log("Trigger: pagination-done");
    $(document).trigger("pagination-done",page,tpl,result);
    return;
    //=======//

    //if (more == undefined || !$(more).length) $("[data-wb-tpl=" + tid + "]").closest().scrollTop(0);


  }

});
