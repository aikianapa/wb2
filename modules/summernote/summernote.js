$(document).on("summernote-js",function(){
    $(document).find('.summernote:not(.summernote-done)').each(function() {
        var that = this;
        var name = $(that).attr("name");
        $(that).data("timeout",false);

            //var lang = wbapp.settings.i18n;
            var scripts = ["/engine/modules/summernote/dist/summernote-bs4.min.js"];
            var lang = $(that).attr("data-lang");
            if (lang == undefined) lang = wbapp.settings.lang;
            lang = lang.substr(0,2);
            if (lang !== "en") scripts.push("/engine/modules/summernote/dist/lang/summernote-"+lang+".js");
            wbapp.loadStyles(["/engine/modules/summernote/dist/summernote-bs4.css"]);
            wbapp.loadScripts(scripts,"summernote-init");


            $(document).on("summernote-init",function() {
                var options = {
                    height: 250,
                    lang: lang,
                    codemirror: { // codemirror options
                        theme: 'monokai',
                        mode: 'text/html',
                        lineNumbers: true,
                        lineWrapping: true,
                        extraKeys: {
                            "Ctrl-Q": function (cm) {
                                cm.foldCode(cm.getCursor());
                            }
                        },
                        foldGutter: true,
                        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
                    },
                    callbacks: {
                        onChange: function (contents, $editable) {
                            $(that).html(contents);
                            if ($(that).hasClass("contenteditable")) $(that).data("contenteditable",contents);
                            if ($(that).data("timeout") == false) {
                                $(that).data("timeout",true);
                                setTimeout(function(){
                                    $(that).trigger("change");
                                    $(that).data("timeout",false);
                                },300);
                            }
                            //console.log('onChange:', contents);
                        }
                    }
                };
                if ($(that).hasClass("contenteditable")) options.airMode = true;
                $(".summernote:not(.summernote-done)").summernote(options);
                $(".summernote:not(.summernote-done)").addClass("summernote-done");
            });

    });
});
