var test;
$(document).on("filepicker-js", function() {
  wbapp.loadStyles([
    "/engine/modules/filepicker/assets/css/cropper.min.css",
    "/engine/modules/filepicker/assets/css/fileicons.css",
    "/engine/modules/filepicker/assets/css/filepicker.css",
    "/engine/modules/filepicker/filepicker.less"
  ]);

  wbapp.loadScripts([
    "/engine/modules/filepicker/assets/js/cropper.min.js",
    "/engine/modules/filepicker/assets/js/filepicker.js",
    "/engine/modules/filepicker/assets/js/filepicker-drop.js",
    "/engine/modules/filepicker/assets/js/filepicker-crop.js",
    "/engine/modules/filepicker/assets/js/filepicker-camera.js"
  ],"filepicker-init");
});

$(document).on("filepicker-init", function() {
      var selector = ".filepicker";
      var uploader = "/engine/modules/filepicker/uploader/index.php";
      var size = "200px";


      $(document).find(selector).each(function(){
            let $filepicker = $(this);
            let input = $filepicker.find(".filepicker-data");
            let inpfile = $filepicker.find("input[type=file]");
            let save;
            let fp;

            var getparams = function() {
              let params = {};
              $filepicker.find(".fileinput input[type=hidden]").each(function(i){
                  params[$(this).attr("name")] = $(this).val();
              });
              return params;
            }

            var geturi = function() {
                var arr = [];
                $.each(getparams(),function(key,val){
                    arr.push(key+"="+val);
                });
                return uploader+"?"+arr.join("&");
            }


            var getdata = function() {
                inp = $filepicker.find(".filepicker-data");
                if ($(inp).val() == undefined || $(inp).val() == "") return {};
                var data = json_decode($(inp).val());
                return data;
            }

            var setdata = function() {
                var data = {};
                $filepicker.find("img[data-img]").each(function(i){
                    data[i] = {
                      img: $(this).attr("data-img"),
                      title: $(this).attr("title"),
                      alt: $(this).attr("alt")
                    }
                });
                return data;
            }

            var listview = function(list = []) {
                var data = getdata();
                var $filepicker = $(selector);
                var template = wbapp.template("fp-listviewItem").html;
                var result = {};
                var idx = 0;
                $.each(data,function(i,item){
                    // add to result files present on data
                    $.each(list.files,function(i,file){
                        if (item.img == file.name) {
                            file.alt = item.alt;
                            file.title = item.title;
                            result[idx] = file;
                            idx++;
                            list.files[i] = 0;
                            return false;
                        }
                    });
                });
                $.each(list.files,function(i,file){
                    // add to result files outside of data
                    if (file == 0) return false;
                    file.alt = "";
                    file.title = "";
                    result[idx] = file;
                    idx++;
                });

                var inner = "";
                $.each(result,function(i,file){
                    var item = template;
                    $.each(file,function(fld,val){
                        if (fld == "error") item = ""; // if upload error
                        item = str_replace("%"+fld+"%",val, item);
                    });
                    inner += item;
                    if (!$(inpfile).is("[multiple]")) {
                        $filepicker.find("[name=prevent_img]").val(file.name);
                        return false;
                    }

                });
                if (!$(inpfile).is("[multiple]") && inner == "") {
                      var item = template;
                      item = str_replace("%","", item);
                      item = $(item);
                      $(item).html('<i class="fa file-icon-jpg"></i>');
                      $(item).find(".fa")
                          .css("width",size)
                          .css("height",size)
                          .css("text-align","center")
                          .css("font-size",size);
                      inner = $(item).outerHTML();
                }


                return inner;
            }


            var list = wbapp.getWait(geturi());
            $filepicker.find(".listview").html(listview(list));
            $filepicker.find(".listview").sortable({
              update: function(){
                $(input).html(json_encode(setdata()));
              }
            });
            $filepicker.find(".listview").disableSelection();
            $(input).html(json_encode(setdata()));


            $filepicker.filePicker({
                url: uploader
                ,data: getparams()
                ,ui: {
                    autoUpload: true
                }
                ,plugins: ['ui', 'drop', 'camera', 'crop']

            })
            .on('done.filepicker', function (e, data) {
              let oldfile = $filepicker.find("[name=prevent_img]").val();
              let name;
              if (!$(inpfile).is("[multiple]")) {
                  if (oldfile > "" && data.files[0].name !== oldfile && data.files[0].name !== undefined) {
                      fp.delete(oldfile).done(function(){
                          $filepicker.find("[name=prevent_img]").val(data.files[0].name);
                      });
                  } else {
                      $filepicker.find(".listview").html(save);
                      console.log("loading.error");
                      return;
                  }
              }

              if (data.files[0].original == null) {
                  name = data.files[0].name;
              } else {
                  name = data.files[0].original;
              }
              $filepicker.find(".listview img[data-img='"+name+"']").parents(".card").replaceWith(listview(data));
              $(input).html(json_encode(setdata()));
            })
            .on('cropsave.filepicker', function (e, file) {
                let card = listview({files:[file]});
                let img = $(card).find("img[data-src='"+file.url+"']");
                let src = $(img).attr("src")+"?"+wbapp.newId();
                $filepicker.find(".listview img[data-src='"+file.url+"']").attr("src",src);
            })
            .on('add.filepicker', function(e,data){
                  save = $filepicker.find(".listview").html();
                  $.each(data.originalFiles,function(i,item){
                    var template = wbapp.template("fp-listviewItem").html;
                    template = str_replace("%name%",item.name, template);
                    if (!$(inpfile).is("[multiple]")) $filepicker.find(".listview").html("");
                    if (!$filepicker.find(".listview img[data-img='"+item.name+"']").length) {
                        $filepicker.find(".listview").prepend(template);
                        $filepicker.find(".listview img[data-img='"+item.name+"']")
                            .attr("src","/engine/modules/filepicker/assets/img/loader.gif")
                            .attr("title","")
                            .attr("alt","")
                            .next(".card-body").remove();
                    }
                  });
            })
            .on('fail.filepicker', function(e,data){
              $filepicker.find(".listview img[data-img='"+data.files[0].name+"']")
                  .attr("src","/engine/modules/filepicker/assets/img/error.png")
                  .attr("title","Error")
                  .on("click",function(){$(this).parent(".card").remove();});
            })
            .on('uploadfallback.filepicker', function(e,data){
                console.log(data);
            })
            ;
            fp = $filepicker.filePicker();


            $filepicker.delegate("a.delete","click",function(){
                var card = $(this).closest(".card");
                var file = $(card).find("img").attr("data-src");
                fp.delete(file).done(function(){
                  $(card).remove();
                });
                if (!$(inpfile).is("[multiple]")) {
                    $filepicker.find(".listview").html(listview());
                }
                $(input).html(json_encode(setdata()));
                return false;
            });

            $filepicker.delegate("a.crop","click",function(){
                var card = $(this).closest(".card");
                var file = $(card).find("img").attr("data-src");
                fp.plugins.crop.show(file);
                return false;
            });

      });
});
