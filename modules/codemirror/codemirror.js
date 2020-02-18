$(document).on("codemirror-js", function() {
  wbapp.loadScripts(['/engine/modules/codemirror/dist/lib/codemirror.js'], 'codemirror-js-ready', function() {
    wbapp.loadStyles([
      '/engine/modules/codemirror/dist/lib/codemirror.css',
      '/engine/modules/codemirror/dist/addon/fold/foldgutter.css'
    ], "codemirror-css");
    wbapp.loadScripts([
      '/engine/modules/codemirror/dist/addon/edit/closetag.js',
      '/engine/modules/codemirror/dist/addon/edit/closebrackets.js',
      '/engine/modules/codemirror/dist/addon/fold/foldcode.js',
      '/engine/modules/codemirror/dist/addon/fold/foldgutter.js',
      '/engine/modules/codemirror/dist/addon/fold/xml-fold.js',
      '/engine/modules/codemirror/dist/mode/xml/xml.js',
      '/engine/modules/codemirror/dist/mode/javascript/javascript.js',
      '/engine/modules/codemirror/dist/mode/css/css.js',
      '/engine/modules/codemirror/dist/mode/htmlmixed/htmlmixed.js'
    ], "codemirror-js-addons");
  });
});

$(document).on("codemirror-js-addons", function() {
  $('textarea.codemirror').each(function() {
    var that = this;
    var name = $(that).attr("name");
    var form = $(that).parents("form")[0];
    if ($(that).data("wb-loaded") == undefined) {
      if ($(that).data("theme") !== undefined) {
        var theme = $(that).data("theme");
      } else {
        var theme = 'cobalt';
      }
      if ($(that).data("mode") !== undefined) {
        var mode = $(that).data("mode");
      } else {
        var mode = 'htmlmixed';
      }
      wbapp.loadStyles(['/engine/modules/codemirror/dist/theme/' + theme + '.css']);
      wbapp.loadScripts(['/engine/modules/codemirror/dist/mode/' + mode + '/' + mode + '.js']);
      // Initialize CodeMirror editor with a nice html5 canvas demo.
      var editor = CodeMirror.fromTextArea(that, {
        mode: mode,
        theme: theme,
        lineNumbers: true,
        lineWrapping: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        autoRefresh: true,
        extraKeys: {
          "Ctrl-Q": function(cm) {
            cm.foldCode(cm.getCursor());
          }
        },
        foldGutter: true,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
      });
      editor.foldCode(CodeMirror.Pos(0, 0));
      editor.on("change", function() {
        $(that).html(htmlentities(editor.getValue()));
      });
      if ($(that).parents(".modal").length) {
        $('#'+$(that).parents(".modal").attr("id")).on('shown.bs.modal,shown.bs.tab', function () {
          setTimeout(function() {
              editor.refresh();
          }, 200);
        });
      }
      $(that).data("wb-loaded", true);
    }
  });
});
