$(document).on("froalaeditor-js",function(){
      $(document).find('.froalaeditor').each(function() {
        var that = this;
        var name = $(that).attr("name");
        var froala = $(that).attr("id");
        if ($(that).data("wb-loaded") == undefined) {
          var theme = $(that).attr("data-theme");
          if (theme == undefined) theme = "gray";
          var lang = $(that).attr("data-lang");
          if (lang == undefined) lang = wbapp.settings.locale;
          lang = lang.substr(0,2);
          wbapp.loadStyles(["/engine/modules/froalaeditor/lib/css/froala_editor.pkgd.min.css",
            "/engine/modules/froalaeditor/lib/css/themes/" + theme + ".min.css",
            "/engine/modules/froalaeditor/lib/css/third_party/image_tui.min.css",
            "https://cdn.jsdelivr.net/npm/tui-image-editor@3.2.2/dist/tui-image-editor.css",
            "https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.css",
            "/engine/lib/fonts/font-awesome/css/font-awesome.min.css"
          ]);


          var loadjs = ["/engine/js/php.js",
            "https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.min.js",
            "https://cdn.jsdelivr.net/npm/tui-code-snippet@1.4.0/dist/tui-code-snippet.min.js",
            "https://cdn.jsdelivr.net/npm/tui-image-editor@3.2.2/dist/tui-image-editor.min.js",
            "/engine/modules/froalaeditor/lib/js/froala_editor.pkgd.min.js",
            "/engine/modules/froalaeditor/lib/js/third_party/image_tui.min.js"
          ];
          if (lang !== "en" && lang !== undefined) {
            loadjs.push("/engine/modules/froalaeditor/lib/js/languages/" + lang + ".js");
          }

          $(document).on("froalaeditor-init", function() {
            $(that).froalaEditor({
              theme: theme,
              language: lang,
              //    imageUploadURL: '/engine/js/uploader/upload.php',
              events: {
                'contentChanged': function() {
                  $(that).html(this.codeBeautifier.run(this.html.get()));
                  if ($(that).hasClass("contenteditable")) $(that).data("contenteditable",$(that).html());

                  $(that).trigger("change");
                }
              }
            });
            $(that).data("wb-loaded", true);
          });

          wbapp.loadScripts(loadjs, "froalaeditor-init");
        }

        $.fn.froalaEditor = function(options = {}) {

          if ($(this).hasClass("contenteditable")) {
              options.toolbarInline = true;
              options.toolbarVisibleWithoutSelection = true;
          }
          var frid = $(this).attr("id");
          var edit = new FroalaEditor('#' + frid, options, function() {
            //console.log(edit.html.get());
          });
        }


      });
});
