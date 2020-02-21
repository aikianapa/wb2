$(document).one("pagination-js", function() {

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
    $(document).find(".pagination[id=" + pid + "] .page-item").removeClass("active");
    $(document).find(".pagination[id=" + pid + "] .page-item:nth-child(" + ($(this).parent("li").index() + 1) + ")").addClass("active");
  });


  $.fn.wbPagination = function() {
    console.log("Pagination: Click");
    var paginator = $(this).closest(".pagination");
    var that = $(this);
    var id = $(paginator).attr("id");
    var tid = $(paginator).attr("id").split("-")[1];


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
    result = $(result).find("[data-wb-tpl='"+tpl+"']");
    $(result).find("script[type='text/locale'],template").remove();
    result = $(result).html();
    $("body").removeClass("cursor-wait");
    wbapp.watcher[tpl].page(result);
//    window.location.hash = "page-" + idx + "-" + pagenum;
    $("body").removeClass("cursor-wait");






    return;
    //=======//

    //if (more == undefined || !$(more).length) $("[data-wb-tpl=" + tid + "]").closest().scrollTop(0);


  }

});
