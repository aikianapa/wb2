var wbapp = new Object();
if (typeof $ == 'undefined') {
    alert("jQuery required!");
} else {
wbapp.loadScripts = function(scripts = [], trigger = null, func = null) {
  if (wbapp.loadedScripts == undefined) wbapp.loadedScripts = [];
  let i = 0;
  scripts.forEach(function(src) {
    if (wbapp.loadedScripts.indexOf(src) !== -1) {
      i++;
      if (i >= scripts.length) {
        if (func !== null) return func(scripts);
        if (trigger !== null) {
          $(document).find("script#" + trigger + "-remove").remove();
          $(document).trigger(trigger);
          console.log("Trigger: " + trigger);
        }
      }
    } else {
      var script = document.createElement('script');
      script.src = src;
      script.async = false;
      script.onload = function() {
        i++;
        wbapp.loadedScripts.push(src);
        if (i >= scripts.length) {
          if (func !== null) return func(scripts);
          if (trigger !== null) {
            $(document).find("script#" + trigger + "-remove").remove();
            console.log("Trigger: " + trigger);
            $(document).trigger(trigger);
          }
        }
      }
      document.head.appendChild(script);
    }
  });

}

wbapp.loadStyles = function(styles = [], trigger = null, func = null) {
  if (wbapp.loadedStyles == undefined) wbapp.loadedStyles = [];
  var i = 0;
  styles.forEach(function(src) {
    if (wbapp.loadedStyles.indexOf(src) !== -1) {
      i++;
      if (i >= styles.length) {
        if (func !== null) return func(styles);
        if (trigger !== null) {
          console.log("Trigger: " + trigger);
          $(document).find("script#" + trigger + "-remove").remove();
          $(document).trigger(trigger);
        }
      }
    } else {
      var style = document.createElement('link');
      wbapp.loadedStyles.push(src);
      style.href = src;
      style.rel = "stylesheet";
      style.type = "text/css";
      style.async = false;
      style.onload = function() {
        i++;
        if (i >= styles.length) {
          if (func !== null) return func(styles);
          if (trigger !== null) {
            $(document).find("script#" + trigger + "-remove").remove();
            $(document).trigger(trigger);
            console.log("Trigger: " + trigger);
          }
        }
      }
      document.head.appendChild(style);
    }
  });
}

wbapp.save = function(that,params) {
  var data = {};
  if ($(that).hasClass("contenteditable")) {
    if (!params.field) params.field = "text";
    var field = params.field;
    if (params.editor_id) {
      var data = [];
      var editor = $("#"+params.editor_id);
      data = $(editor).data("contenteditable");
      if (data == undefined) data = $(editor).html();
    } else {
      if ($(that).is("[contenteditable=false]")) {
          data[field] = $(that).find("[contenteditable=true]").html();
      } else {
          data[field] = $(that).html();
      }
    }
    var method = "post";
    var result = wbapp.postWait("/ajax/save/" + method, {
      data: data,
      params: params
    });
  } else if ($(that).is(":input") && !$(that).is("button")) {
      if ($(that).data("save") == undefined) $(that).data("save",true);
      if ($(that).data("save") == true) {
          $(that).data("save",false);
          setTimeout(function(){
            var field = params.field;
            if (field == undefined) return;
            var method = "post";
            var result = wbapp.postWait("/ajax/save/" + method, {
              data: $(that).val(),
              params: params
            });
            $(that).data("save",true);
            wbapp.watcherCheck(params);
          },50);
      }
  } else {
    if (params.selector !== undefined) {
        var form = $(params.selector);
    } else {
        var form = $(that).parents("form");
    }

    if (!$(form).checkRequired()) return;

    var result = false;
    var method = strtolower($(form).attr("method"));
    var data = $(form).serializeJson();

    if (method == undefined || method == "" || !in_array(method, ["post", "get"])) method = "post";
    if (params.method == "ajax") {
      if (method == "post") {
        result = wbapp.postWait("/ajax/save/" + method, {
          data: data,
          params: params
        });
      } else {
        result = wbapp.getWait("/ajax/save/" + method, {
          data: data,
          params: params
        });
      }
    } else {
      $(form).submit();
      return;
    }
  }
  if (result) {
    that.result = result;
    params.result = result;
    params.id = data.id; // needed if rename
    wbapp.watcherCheck(params);
    console.log("Trigger: wb-save-done");
    $(that).trigger("wb-save-done");
  }
}

wbapp.ajaxWait = function(options) {
  return wb_ajaxWait([options]);
}
wbapp.getWait = function(url, data, func) {
  var res;
  wb_ajaxWait([{
    async: false,
    type: 'GET',
    data: data,
    url: url,
    success: function(data) {
      if (func !== undefined) {
        res = func(data);
      } else {
        res = data;
      }
    }
  }]);
  return res;
}
wbapp.postWait = function(url, data, func) {
  var res;
  wb_ajaxWait([{
    async: false,
    type: 'POST',
    data: data,
    url: url,
    success: function(data) {
      if (func !== undefined) {
        res = func(data);
      } else {
        res = data;
      }
    }
  }]);
  return res;
}

wbapp.tplInit = function() {
    $(document).find("template[id]").each(function(){
        var tid = $(this).attr("id");
        if (tid > "") {
            params = [];
            if ($(this).attr("data-params") !== undefined) params = json_decode($(this).attr("data-params"));
            wbapp.template(tid,{
              html:$(this).html(),
              params:params
            });
            $(this).removeAttr("data-params");
            if ($(this).attr("visible") == undefined) $(this).remove();
        }
    });
}

wbapp.modalsInit = function() {
  var zndx = $(document).data("modal-zindex");
  if (zndx == undefined) $(document).data("modal-zindex", 2000);

  $(document).delegate(".modal-header","dblclick",function(event){
      var that = $(event.target);
      $(that).closest(".modal").find(".modal-content").toggleClass("modal-wide");
  });


  $(document).delegate(".modal", "shown.bs.modal", function(event) {
      var that = $(event.target);
      if ($(that).is("[data-zndx]")) return;
      $(that).find('.modal-content')
  //      .resizable({
  //        minWidth: 300,
  //        minHeight: 175,
  //        handles: 'n, e, s, w, ne, sw, se, nw',
  //      })
        .draggable({
          handle: '.modal-header'
        });

      var zndx = $(document).data("modal-zindex");
      if (zndx == undefined) {
        var zndx = 4000;
      } else {
        zndx += 10;
      }
      if (!$(this).closest().is("body")) {
          if ($(this).data("parent") == undefined) $(this).data("parent", $(this).closest());
          $(this).appendTo("body");
      }
      $(this).data("zndx", zndx).css("z-index", zndx).attr("data-zndx",zndx);
      $(that).find("[data-dismiss]").attr("data-dismiss",zndx);
      $(document).data("modal-zindex", zndx);
      if ($(that).attr("data-backdrop") !== undefined && $(that).attr("data-backdrop") !== "false") {
        setTimeout(function() {
          $(".modal-backdrop:not([id])").css("z-index", (zndx - 5)).attr("id", "modalBackDrop" + (zndx - 5));
        }, 0);
      }
  });

  $(document).delegate(".modal [data-dismiss]","click",function(event){
      var zndx =  $(this).attr("data-dismiss");
      var modal = $(document).find(".modal[data-zndx='"+$(this).attr("data-dismiss")+"']");
        modal.modal("hide");
  });

  $(document).delegate(".modal", "hide.bs.modal", function(event) {
    var that = $(event.target);
    var zndx = $(that).attr("data-zndx");
    $("#modalBackDrop" + (zndx - 5) + ".modal-backdrop").remove();
    $(document).data("modal-zindex", zndx - 10);
  });
  $(document).delegate(".modal", "hidden.bs.modal", function(event) {
    var that = $(event.target);
    if ($(this).hasClass("removable")) {$(that).modal("dispose").remove();}
    else {$(this).appendTo($(this).data("parent"));}
  });
  $(document).off("wb-ajax-done");
  $(document).on("wb-ajax-done",function(){
      console.log("Trigger: wb-ajax-done");
      if (wbapp !== undefined) {
        wbapp.tplInit();
        wbapp.watcherInit();
        wbapp.wbappScripts();
      }
      $(".modal.show:not(:visible),.modal[data-show=true]:not(:visible)").modal("show");
      if ($.fn.tooltip) $('[data-toggle="tooltip"]').tooltip();
  });
}

wbapp.init = function() {
  wbapp.modalsInit();
  wbapp.tplInit();
  wbapp.watcherInit();
  wbapp.eventsInit();
  wbapp.wbappScripts();
  if ($.fn.tooltip) $(document).find('[data-toggle="tooltip"]').tooltip();
}

wbapp.alive = function() {
  wbapp.aliveInterval = setInterval(function(){
      if (wbapp.settings.user) {
        var data = wbapp.getWait("/ajax/alive");
        if (data == null) {
          clearInterval(wbapp.aliveInterval);
          document.location.href = document.location.href;
        } else {
          if (data.events > "") {
              data.events = json_decode(base64_decode(data.events));
              if (data.live == false) {
                  clearInterval(wbapp.aliveInterval);
                  setcookie("events", base64_encode(json_encode([])),time()+3600,"/");
                  document.location.href = document.location.href;
              }

              $.each(data.events,function(i,ev){
                $(document).trigger("event",ev);
                delete data.events[i];
              });
              data.events = base64_encode(json_encode(data.events));
              setcookie("events", data.events,time()+3600,"/");
          }
        }
      }
  },15000);
}

wbapp.alert = function(text,type="") {
    alert(text);
}

wbapp.watcherInit = function() {
    if (wbapp.watcher == undefined) wbapp.watcher = {};
    for(var tpl in wbapp.watcher) {
        if (!$(document).find("[data-wb-tpl='"+tpl+"']").length) delete wbapp.watcher[tpl];
    };

    $(document).find("[data-watcher]").each(function(){

      var watcher_filter = function(that){
          let target = $(document).find(params.filter);
          if (!$(params.filter).length) {
            let msg = params.filter+" not found";
            console.log(msg);
            wbapp.alert(msg,"warning");
            return;
          }
          if ($(params.filter).attr("data-wb-tpl") == undefined) {
              let msg = "Add &tpl=true to "+params.filter;
              console.log(msg);
              wbapp.alert(msg,"warning");
              return;
          } else {
              var tpl = $(params.filter).attr("data-wb-tpl");
          }

          if ($(that).data("watcher_filter") == undefined) {
              $(that).off("click").on("click",function(){

                  let filter = $(that).closest("form").serializeJson();
                  let template = wbapp.tpl[tpl].html;

                  let data = {_tpl:template,_tplid:tpl,_filter:filter,_route:wbapp.template(tpl).params.route,_result:params.filter};
                  if (params.tpl == "false") delete data._tpl;
                  var result = wbapp.postWait("/ajax/fetch",data);
                  if (result.result !== undefined) {
                      $(params.filter).html(result.result).trigger("click");
                  }
                  if (result.return !== undefined) {
                      $.each(result.return, function(fld,val){
                          $(params.filter).attr("data-"+fld,val);
                      });
                  }
                  console.log("Trigger: watcher_filter");
                  $(document).trigger("watcher_filter", params.filter, result);
              });
              $(that).data("watcher_filter",true);
          }
      }

      var watcher_change = function(that){
          let target = $(document).find(params.change);
          if (!$(params.change).length) {
            let msg = params.change+" not found";
            console.log(msg);
            wbapp.alert(msg,"warning");
            return;
          }
          if ($(params.change).attr("data-wb-tpl") == undefined) {
              let msg = "Add &tpl=true to "+params.change;
              console.log(msg);
              wbapp.alert(msg,"warning");
              return;
          } else {
              var tpl = $(params.change).attr("data-wb-tpl");
          }
          if ($(that).data("watcher_change") == undefined) {
              $(that).off("change").on("change",function(){
                  let template = wbapp.tpl[tpl].html;
                  let val = $(that).val();
                  if ($(that).is("select") && params.value > "") val = $(that).find("option:selected").attr("data-"+params.value);
                  template = str_replace("%value%",val,template);

                  var result = wbapp.postWait("/ajax/fetch",{_tpl:template,_route:wbapp.template(tpl).params.route});
                  if (result.result !== undefined) {
                      $(params.change).html(result.result).trigger("change");
                  }
                  console.log("Trigger: watcher_change");
                  $(document).trigger("watcher_change",params.change,result);
              });
              $(that).data("watcher_change",true);
              $(that).trigger("change");
          }
      }

      var watcher_watcher = function(that) {
                params.create = function(item,data) { $(document).find(params.watcher).prepend(data); }
                params.update = function(item,data) {
                    let $tpl = $(document).find("[data-wb-tpl='"+tpl+"']");
                    if ($tpl.find("[data-watcher='item="+item+"']").length) {
                        $tpl.find("[data-watcher='item="+item+"']").replaceWith(data);
                    } else if ($tpl.find("[data-watcher^='item="+item+"&']").length) {
                        $tpl.find("[data-watcher^='item="+item+"&']").replaceWith(data);
                    }
                };
                params.remove = function(item,data) {
                      $(document).find("[data-wb-tpl='"+tpl+"'] [data-watcher^='item="+item+"']").remove();
                      //$(document).find(".pagination#ajax-"+tpl+" .page-item.active .page-link").trigger("click");
                      return;
                };
                params.page = function(data) {
                      $(document).find("[data-wb-tpl='"+tpl+"']").html(data);
                }
                delete params.item;
                params.params = wbapp.template(tpl).params;
                wbapp.watcher[tpl] = params;

      }

      var that = this;
      var params = $.parseParams($(this).attr("data-watcher"));
      if (params.item == undefined) params.item = "_new";
      if (params.watcher == undefined) {
          var tpl = $(this).parent("[data-wb-tpl]").attr("data-wb-tpl");
      } else {
          var tpl = $(document).find(params.watcher).attr("data-wb-tpl");
      }
      if (params.change !== undefined) {
          watcher_change(this);
      } else if (params.filter !== undefined) {
          watcher_filter(this);
      } else {
          watcher_watcher(that);
      }
    });

    $(document).find("[data-wb-tpl]:not(.wb-multiinput)").each(function() {
        // fix for pagination without data-watcher attribute
        let tpl = $(this).attr("data-wb-tpl");
        if ($(document).find("#ajax-"+tpl).length && wbapp.watcher[tpl] == undefined) {
              let params = {};
              params.page = function(data) {
                    $(document).find("[data-wb-tpl='"+tpl+"']").html(data);
              }
              wbapp.watcher[tpl] = params;
        }
    });
}

wbapp.watcherCheck = function(params) {
    var form = params.form;
    var item = params.item;
    var watch = item;
    var mode = "update";

    if (!params.result) params.result = {};

    if (params.id) item = params.id;
    if (params.remove == "true") mode = "remove";
    if (params.result.new == true) {
        mode = "create";
        item = params.result.id;
    }

    if (params.watcher == undefined) {
        var tpl = $(this).parent("[data-wb-tpl]").attr("data-wb-tpl");
    } else {
        var tpl = $(document).find(params.watcher).attr("data-wb-tpl");
    }

    if (tpl > "") {
        var uri = wbapp.template(tpl).params.route.uri;
        var result = wbapp.postWait(uri,{_watch_item:item,_watch_route:wbapp.template(tpl).params.route});
        console.log(params);
        result = $(result).find(params.watcher).html();
        $(result).find("script[type='text/locale']").remove();
        result = $(result).outerHTML();

        if (mode == "update") wbapp.watcher[tpl].update(watch,result);
        if (mode == "remove") wbapp.watcher[tpl].remove(item,result);
        if (mode == "create" && wbapp.watcher[tpl] !== undefined) {
            wbapp.watcher[tpl].create(item,result);
        } else if (mode == "create") {
            $(document).find(params.watcher).prepend(result);
        }



        wbapp.init()  ;
        console.log("Trigger: wb-watcher-done");
        $(document).trigger("wb-watcher-done",params);
    }
    return false;
}

wbapp.wbappScripts = function() {
  var done = [];
  $("script[type=wbapp]").each(function(){
      var script = $(this).text();
      //$(this).remove();
      var hash = md5(script);
      if (!in_array(hash,done)) {
          eval(script);
          done.push(hash);
      }
      if ($(this).attr("visible") == undefined) $(this).remove();
  });
}

wbapp.template = function(tid,tpl = null) {
    if (wbapp.tpl == undefined) wbapp.tpl = {};
    if (tpl == null) return wbapp.tpl[tid];
    wbapp.tpl[tid] = tpl;
}


wbapp.sleep = function(miliseconds) {
  var currentTime = new Date().getTime();
  while (currentTime + miliseconds >= new Date().getTime()) {}
}

wbapp.loading = function() {
  if (!$("#wb-loading").length) {
    $("body").append("<div id='wb-loading'></div>");
  }
  $("#wb-loading").show();
}

wbapp.unloading = function() {
  $("#wb-loading").hide();
}

wbapp.getModal = function(id = null) {
  var zndx = $(document).data("modal-zindex");
  var modal = $(document).data("wbapp-modal");
  if (modal == undefined) {
    var modal = wbapp.getWait("/ajax/getform/snippets/modal/");
    modal = $("<div>" + modal.content + "</div>").find(".modal").clone();
    $(document).data("wbapp-modal", modal);
  }
  if (id !== null) $(modal).attr("id", id);
  if (zndx !== undefined) $(modal).data("zndx", zndx).attr("style", "z-index:" + zndx);
  return $(modal).clone();
}

wbapp.newId = function(separator, prefix) {
  if (separator == undefined) {
    separator = "";
  }
  var mt = explode(" ", microtime());
  var md = substr(str_repeat("0", 2) + dechex(ceil(mt[0] * 10000)), -4);
  var id = dechex(time() + rand(100, 999));
  if (prefix !== undefined && prefix > "") {
    id = prefix + separator + id + md;
  } else {
    id = id + separator + md;
  }
  return id;
}

$.fn.checkRequired = function() {
    var form = this;
    var res = true;
    var idx = 0;
    $(form).find("[required],[type=password],[minlength],[min],[max]").each(function(i) {
        idx++;
		var label = $(this).attr("data-label");
		if (label == undefined || label == "") label = $(this).prev("label").text();
		if (label == undefined || label == "") label = $(this).next("label").text();
		if ((label == undefined || label == "") && $(this).attr("id") !== undefined) label = $(this).parents("form").find("label[for="+$(this).attr("id")+"]").text();
		if (label == undefined || label == "") label = $(this).parents(".form-group").find("label").text();
		if (label == undefined || label == "") label = $(this).attr("placeholder");
		if (label == undefined || label == "") label = $(this).attr("name");

        $(this).data("idx", idx);
        if ($(this).is(":not([disabled],[readonly],[min],[max],[maxlength],[type=checkbox]):visible")) {
            if ($(this).val() == "") {
                res = false;
                console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
                $(document).trigger("wb_required_false", [this]);
            } else {
                if ($(this).attr("type") == "email" && !wb_check_email($(this).val())) {
                    res = false;
                    $(this).data("error", wbapp.settings.sysmsg.email_correct);
                    console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
                    $(document).trigger("wb_required_false", [this]);
                } else {
					          console.log("trigger: wb_required_true");
                    $(document).trigger("wb_required_true", [this]);
                }
            }
        }
        if ($(this).is("[type=checkbox]") && $(this).is(":not(:checked)")) {
            res = false;
            console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
            $(document).trigger("wb_required_false", [this]);
        }
				if ($(this).is("[type=radio]") && $(this).is(":not(:checked)")) {
            res = false;
						var fld = $(this).attr("name");
						if (fld > "") {
								$("[type=radio][name='"+fld+"']").each(function(){
									if ($(this).is(":checked")) {res = true;}
								});
						}
						if (!res) {
            		console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
            		$(document).trigger("wb_required_false", [this]);
						}
        }
        if ($(this).is("[type=password]")) {
            var pcheck = $(this).attr("name") + "_check";
            if ($("input[type=password][name='" + pcheck + "']").length) {
                if ($(this).val() !== $("input[type=password][name=" + pcheck + "]").val()) {
                    res = false;
                    $(this).data("error", wbapp.settings.sysmsg.pass_match);
                    console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
                    $(document).trigger("wb_required_false", [this]);
                }
            }
        }
        if ($(this).is("[min]:not([readonly],[disabled]):visible") && $(this).val() > "") {
			var min = $(this).attr("min") * 1;
			var minstr = $(this).val() * 1;
			if (minstr < min) {
                res = false;
                $(this).data("error", ucfirst(label) + " " + wbapp.settings.sysmsg.min_val+": " + min);
                console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
                $(document).trigger("wb_required_false", [this]);
			}
		}

        if ($(this).is("[max]:not([readonly],[disabled]):visible")  && $(this).val() > "") {
			var max = $(this).attr("max") * 1;
			var maxstr = $(this).val() * 1;
			if (maxstr > max) {
                res = false;
                $(this).data("error", ucfirst(label) + " " + wbapp.settings.sysmsg.max_val+": " + max);
                console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
                $(document).trigger("wb_required_false", [this]);
			}
		}

        if ($(this).is("[minlength]:not([readonly],[disabled]):visible") && $(this).val() > "") {
            var minlen = $(this).attr("minlength") * 1;
            var lenstr = strlen($(this).val());
            if (lenstr < minlen) {
                res = false;
                $(this).data("error", ucfirst(label) + " " + wbapp.settings.sysmsg.min_length+": " + minlen);
                console.log("trigger: wb_required_false ["+$(this).attr("name")+"]");
                $(document).trigger("wb_required_false", [this]);
            }
        }

        if ($(this).is("button")) {
    			if (
    				($(this).attr("value") !== undefined && $(this).val() == "")
    				||
    				($(this).attr("value")  == undefined && $(this).html() == "")
    			) {
    				res = false;
    			}
		    }
    });
    if (res == true) {
	console.log("trigger: wb_required_success");
        $(document).trigger("wb_required_success", [form]);
    }
    if (res == false) {
	       console.log("trigger: wb_required_fail");
         $(document).trigger("wb_required_fail", [form]);
    }
    return res;
}


wbapp.eventsInit = function() {
    $(document).undelegate(":checkbox","change");
    $(document).delegate(":checkbox","change",function(){
        $(this).val("");
        if ($(this).prop("checked") == true) $(this).val("on");
    });

    $(document).undelegate("select","change");
    $(document).delegate("select","change",function(){
        if ($(this).attr("data-wb-value") !== undefined) {
            $(this).val( $(this).attr("data-wb-value") );
            $(this).removeAttr("data-wb-value");
        }
        let val = $(this).val();
        $(this).find("option").removeAttr("selected");
        $(this).find("option[value='"+val+"']").prop("selected",true).attr("selected",true);
    });

    $(document).off("event");
    $(document).on("event",function(e,ev){
        if (count(ev)) {
          let event = Object.keys(ev);
          console.log("Event: event_"+event);
          $(document).trigger("event_"+event,ev[event]);
        }
    });

    $(document).on("event_user_signout",function(e,params){
        document.location.href = "/";
    });

}

$.fn.jsonVal = function(data = undefined) {
  if (strtolower($(this).attr("type")) !== "json") {
    return $(this).val();
  }
  if (data == undefined) {
    var data = $(this).val();
    if (data > "") {
      data = json_decode(data);
    } else {
      data = {};
    }
    return data;
  } else {
    if (data == "") {
      data = {};
    } else {
      data = json_encode(data);
    }
    if ($(this).is("textarea")) $(this).html(data);
    $(this).val(data).trigger("change");
  }

}

$.fn.outerHTML = function(s) {
  return s ? this.before(s).remove() : jQuery("<p>").append(this.eq(0).clone()).html();
};

$.fn.runScripts = function() {
  $(this).find("script").each(function() {
    var type = $(this).attr("type");
    if (type !== "text/locale" && type !== "text/template") {
      eval($(this).text());
    }
  });
}

$.fn.serializeJson = function(data = {}) {
  var form = $(this).clone();
  $(form).find("form, .wb-unsaved, .wb-tree-item").remove();
  var branch = $(form).serializeArray();
  $(branch).each(function(i, val) {
    data[val["name"]] = val["value"];
    if ($(form).find("textarea[type=json][name='" + val["name"] + "']").length && strpos(data[val["name"]],"}")) {
          data[val["name"]] = json_decode(data[val["name"]]);
        }
  });
  var check = $(form).find('input[type=checkbox]');
  // fix unchecked values
  $.each(check,function(){
      if (this.name > "") {
        data[this.name] = "";
        if (this.checked) data[this.name] = "on";
      }
  });
  $(form).remove();
  return data;
}


function wb_ajaxWait(ajaxObjs, fn) {
  if (!ajaxObjs) return;
  var data = [];
  var ajaxCount = ajaxObjs.length;

  if (fn == undefined) {
    var fn = function(data) {
      return data;
    }
  }

  for (var i = 0; i < ajaxCount; i++) { //append logic to invoke callback function once all the ajax calls are completed, in success handler.
    $.ajax(ajaxObjs[i]).done(function(res) {
      ajaxCount--;
      if (ajaxObjs.length > 0) {
        data.push(res);
      } else {
        data = res;
      }
    }).fail(function() {
      ajaxCount--;
      if (ajaxObjs.length > 0) {
        data.push(false);
      } else {
        data = false;
      }
    }); //make ajax call
  };
  while (ajaxCount > 0) {
    // wait all done
  }
  fn(data);
}

$.parseParams = function(query) {

  var re = /([^&=]+)=?([^&]*)/g;
  var decode = function(str) {
      return decodeURIComponent(str.replace(/\+/g, ' '));
  };
    var params = {}, e;
    if (query) {
        if (query.substr(0, 1) == '?') {
            query = query.substr(1);
        }

        while (e = re.exec(query)) {
            var k = decode(e[1]);
            var v = decode(e[2]);
            if (params[k] !== undefined) {
                if (!$.isArray(params[k])) {
                    params[k] = [params[k]];
                }
                params[k].push(v);
            } else {
                params[k] = v;
            }
        }
    }
    return params;
};

setTimeout(function(){
    wbapp.loadScripts(["/engine/js/php.js"], "wbapp-js", function() {
      console.log("Trigger: wbapp-js");
      wbapp.settings = wbapp.getWait("/ajax/settings");
      wbapp.init();
      wbapp.alive();
    });
    wbapp.unloading();
},1500);
//wbapp.loading();

}
