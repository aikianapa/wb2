$(document).one("pagination-js", function() {

    $(document).delegate(".pagination .page-link","click",function(){
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
        $(document).find(".pagination[id="+pid+"] .page-item").removeClass("active");
        $(document).find(".pagination[id="+pid+"] .page-item:nth-child(" + ($(this).parent("li").index()+1) + ")").addClass("active");
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
var result = wbapp.postWait(uri,{_watch_page:pagenum});
result = $(result).find("[data-wb-tpl]");
$(result).find("script[type='text/locale']").remove();
result = $(result).html();
$("body").removeClass("cursor-wait");
wbapp.watcher[tpl].page(result);
window.location.hash = "page-" + idx + "-" + pagenum;
$("body").removeClass("cursor-wait");






return;
//=======//
    var list = $(document).find("[data-wb-tpl='"+tid+"']");


    var page = explode("/", $(this).attr("data-wb-ajaxpage"));
    var c = count(page);
    var pagenum = page[c - 2];
    var page = "ajax-" + page[c - 3] + "-" + pagenum;
    var sort = null;
    var desc = null;
    if (substr(page, 0, 4) == "page") {
      // js пагинация
      $("[data-page^=" + id + "]").hide();
      $("[data-page=" + page + "]").show();
    } else {



      var sort = $(paginator).attr("data-wb-sort");
      var more = $(paginator).find(".page-more").length;
      var idx = $(paginator).attr("data-wb-idx");
      var param = wbapp.template(tid);

      param.tid = tid;
      param.tpl = wbapp.template(tid).html,
      param.page = pagenum;
      param.uri = $(this).attr("data-wb-ajaxpage");
      param.route = wbapp.template(tid).route;


      var url = "/ajax/pagination/";
      if ($("#" + id).data("find") !== undefined) {
        var find = $("#" + id).data("find");
      } else {
        var find = $(paginator).attr("data-wb-find");
      }
      if (find > "") find = urldecode(find);

      param.find = find;
      param.sort = sort;
      $("body").addClass("cursor-wait");
      console.log("Trigger: wb-pagination-start");
      $(this).trigger("wb-pagination-start", [id, page, pagenum]);
      $.ajax({
        async: true,
        type: 'POST',
        data: param,
        url: url,
        cache: false,
        success: function(data) {
//                var data = wb_json_decode(data);
          if (more) {
            $("[data-wb-tpl=" + tid + "]").append(data.data);
            if (data.pages == pagenum) $(paginator).find(".page-more").remove();
          } else {
            $("[data-wb-tpl=" + tid + "]").html(data.data);
          }
/*
          if (data.pages > "1") {
            $(paginator).show();
          } else {
            $(paginator).hide();
          }
*/
          window.location.hash = "page-" + idx + "-" + pagenum;
          $("body").removeClass("cursor-wait");
          console.log("Trigger: wb-pagination-done");
          $(document).trigger("wb-pagination-done", [id, page, pagenum]);
        },
        error: function(data) {
          $("body").removeClass("cursor-wait");
          console.log("Trigger: wb-pagination-error");
          $(document).trigger("wb-pagination-error", [id, page, pagenum]);
        }
      });
    }
    $(document).find(".pagination[id="+id+"] .page-item").removeClass("active");
    $(document).find(".pagination[id="+id+"] .page-item:nth-child(" + ($(this).parent("li").index()+1) + ")").addClass("active");

    //if (more == undefined || !$(more).length) $("[data-wb-tpl=" + tid + "]").closest().scrollTop(0);


}

});
