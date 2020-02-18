$(document).on("datatimepicker-js",function() {
  wbapp.loadStyles(["/engine/modules/datetimepicker/datetimepicker/bootstrap-datetimepicker.min.css",
                    "/engine/modules/datetimepicker/datetimepicker.less",
                    "/engine/lib/fonts/font-awesome/css/font-awesome.min.css"]);
  var scripts = ["/engine/js/php.js","/engine/modules/datetimepicker/datetimepicker/bootstrap-datetimepicker.min.js"];
  wbapp.loadScripts(scripts, "datetimepicker-js-init");
  $(document).on("datetimepicker-js-init", function() {
        $(".dtpmod:not(.wb-done)").each(function(){
            var input = this;
            var picker = $(input).prev("input");
            var lang = $(picker).attr("data-lang");
            var options = {
              autoclose: true,
              todayBtn: true,
              setDate: new Date(),
              initialDate: $(this).val(),
              language: "ru",
              todayHighlight: true,
              fontAwesome: true
            }

            if ($(picker).attr("type")=="datetimepicker") {
                if ($(picker).attr("data-date-format") == undefined) options.format = "dd.mm.yyyy hh:ii";
            } else if ($(picker).attr("type")=="datepicker") {
                if ($(picker).attr("data-date-format") == undefined) options.format = "dd.mm.yyyy";
              options.minView = 2;
            } else if ($(picker).attr("type")=="timepicker") {
              options.format = "hh:ii";
              options.startView = 1;
              options.minView = 0;
              options.viewSelect = 'hour';
              options.todayBtn = false;
            }

            if (!in_array(lang,[undefined,"en"])) {
                wbapp.loadScripts(["/engine/modules/datetimepicker/datetimepicker/locales/bootstrap-datetimepicker."+lang+".js"],"bootstrap-datetimepicker.lang.js",function(){
                    options.language = lang;
                    datetimepicker_start();
                });

            } else {
                  datetimepicker_start()
            }
            function datetimepicker_start() {
              $(picker).datetimepicker(options).datetimepicker("show").datetimepicker("hide").on("change",function(){
                  if ($(picker).attr("type")=="datetimepicker") {
                      $(input).attr("value",date("Y-m-d H:i:s",strtotime($(picker).val())));
                  } else if ($(picker).attr("type")=="datepicker") {
                      $(input).attr("value",date("Y-m-d",strtotime($(picker).val())));
                  } else if ($(picker).attr("type")=="timepicker") {
                      $(input).attr("value",$(picker).val());
                  }
              });
              $(input).addClass("wb-done");
            }

        });
  });
});
