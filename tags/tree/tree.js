function wb_tree() {
    wbapp.loadStyles(["/engine/tags/tree/tree.less",
        "/engine/lib/js/nestable/nestable.css",
        "/engine/lib/fonts/materialicons/materialicons.css"]);
    wbapp.loadScripts(["/engine/lib/js/nestable/nestable.min.js"],"nestable-js");

    if ($(document).data("wb-tree-ui") == undefined && $(".wb-tree").length) {

        wbapp.getWait("/ajax/tree_getform/tree_ui/", {}, function(data) {
            $(document).data("wb-tree-ui", data.content);
        });
    }

$.fn.wbTreeInit = function() {
        $(this).find(".wb-tree-line[data-id='']").each(function(){
            $(this).attr("data-id",wbapp.newId());
        });
        $(this).find(".wb-tree-item[data-expand=false]").each(function() {
            $(this).find("button[data-action=collapse]").trigger("click");
            $(this).removeAttr("data-expand");
        });
        $(this).delegate(".wb-tree-line","click",function(){
            //wbapp.loading();
            var line = $(this).getLine();
            var item = $(this).getItem();
            var tree = $(this).getTree();
            var path = $(this).getPath();
            var parent = $(this).getParentPath(true);
            var data = $(this).getData(path);
            var dict = $(this).getData(false);
            var lnid = $(line).attr("data-id");
            var childata = data.children;
            delete data.children;
            var res = wbapp.postWait("/ajax/tree_getform/", {"data":data,"dict":dict});
            var modal = res.content;
            $(modal).modal({"backdrop":"static"}).modal("show").runScripts();
            var mid = $(modal).attr("id");
            var modal = $("#"+mid);
            // Procedure after edit branch data //
            $(modal).on('hidden.bs.modal',function(){
                storeData();
            });
            $(modal).undelegate(".modal-header a.nav-link",'click');
            $(modal).delegate(".modal-header a.nav-link",'click', function () {
                var postdict = $(tree).getData(false);
                var postdata = $(tree).getData(path);
                delete postdata.children;
                if ($(this).hasClass("data")) {
                   var res = wbapp.postWait("/ajax/tree_getform/data/", {"data":postdata,"dict":postdict});
                   $(modal).find(".treeData form").html(res.content).runScripts();
                   $(modal).find(".treeData form :input",0).trigger("change");
                } else if ($(this).hasClass("dict")) {
                   var res = wbapp.postWait("/ajax/tree_getform/dict/", {"data":postdata,"dict":postdict});
                   $(modal).find(".treeDict form").html(res.content);
                   $(modal).find(".treeDict form").dictEvents(tree);
                   storeData(false);
                }
            });

            function storeData(remod = true) {
                data = $(modal).find(".modal-body > form").serializeJson(data);
                data.children = childata;
                if (data.id == lnid) {
                    data.data = $(modal).find(".modal-body > .tab-content > .treeData form").serializeJson();
                    $(tree).setData(data,path);
                    delete data.data;
                    delete data.children;
                    $(item).updateItem(data);
                } else {
                    var newdata = {};
                    data.data = $(modal).find(".modal-body > .tab-content > .treeData form").serializeJson();
                    $(item).updateItem(data);
                    var store = $(tree).getData(parent);
                    $.each(store,function(i,d){
                        if (i == lnid) {
                            newdata[data.id] = data;
                        } else {
                            newdata[d.id] = d;
                        }
                    });
                    $(tree).setData(newdata,parent);
                }
                if (remod == true) $(modal).remove();
            }

            //wbapp.unloading();
        });

        $.fn.dictEvents = function(tree){
            $(this).delegate(".wb-multiinput","change",function(){
                $(tree).setDict($(this).value());
            });
        }

        $(this).delegate(".wb-tree-del","click",function(){
            $(this).delItem();
        });

        $(this).delegate(".wb-tree-add","click",function(){
            var id = $(this).getLine().attr("data-id");
            $(this).addItem(id);
        });

        $(this).delegate(".wb-tree-switch","click",function(){
            var item = $(this).getItem();
            $(item).children(".wb-tree-check").trigger("click");
            var path = $(this).getPath();
            var data = $(this).getData(path);
            data["active"] = $(item).children(".wb-tree-check").prop("checked");
            if (data["active"] == true) {data["active"] = "on";} else {data["active"] = "";}
            $(this).setData(data,path);
        });

        $(this).delegate("button[data-action]","click",function(){
          var open = true;
          if ($(this).attr("data-action") == "collapse") open = false;
          var item = $(this).getItem();
          var path = $(this).getPath();
          var data = $(this).getData(path);
          data["open"] = open;
          $(this).setData(data,path);
        });


        $(this).delegate('.wb-tree-swp','mousedown', function() {
            var item = $(this).getItem();
            $(item).data("move",{"path":$(this).getPath(),"indx":$(item).index()});
            $(item).addClass("wb-tree-drag");
        });

        $(this).on('change', function() {
            // change branch position //
            $(this).find(".wb-tree ")
            var drag = $(this).find(".wb-tree-item.wb-tree-drag");
            if (!drag.length) return;
            $(drag).removeClass("wb-tree-drag");
            if ($(drag).parent(".wb-tree").parent(".wb-tree-item").length) $(drag).parent(".wb-tree").removeClass("wb-tree");

            var tree = $(drag).getTree();
            var move = $(drag).data("move");
            var indx = $(drag).index();
            var path = $(drag).getPath();
            var parent = $(drag).getParentPath(true);
            $(drag).data("move",undefined);
            if (path == move.path && indx == move.indx) return;
            var selfdata = $(tree).getData(move.path);
            var store = $(tree).getData(parent);
            var newdata = {};
            if (path == move.path) {
                    // Inside branch //
                    $(drag).parent("ol").find(".wb-tree-line").each(function(){
                        var id = $(this).attr("data-id");
                        newdata[id] = store[id];
                    });
                    $(tree).setData(newdata,parent);
            } else {
                // Outeside branch //
                    $(drag).parent("ol").find(".wb-tree-line").each(function(){
                        var id = $(this).attr("data-id");
                        if (id == selfdata.id) {
                            newdata[id] = selfdata;
                        } else {
                            newdata[id] = store[id];
                        }
                    });
                    $(tree).setData(newdata,parent);

                $(tree).delData(move.path);
            }
        });
            $(this).on("wb-tree-change-data",function(){
               //console.log($(this).val().data);
            });
        $(this).disableSelection();
}

$.fn.setData = function(newdata,path=false) {
    var tree = $(this).getTree();
    if (path === false) {
        var data = json_encode(newdata);
    } else {
        var data = $(this).getData(true);
        eval("data.data"+path+" = newdata;");
        data = json_encode(data);
    }

    $(tree).children(".wb-tree-data").html(data);
    $(tree).val($(this).getData());
    console.log("Trigger: wb-tree-change-data");
    $(this).trigger("wb-tree-change-data");
}

$.fn.setDict = function(newdict,path=false) {
    var tree = $(this).getTree();
    var data = $(this).getData(true);
    eval("data.dict = newdict;");
    data = json_encode(data);
    $(tree).children(".wb-tree-data").html(data);
    $(tree).val($(this).getData());
    console.log($(tree).val());
    console.log("Trigger: wb-tree-change-dict");
    $(this).trigger("wb-tree-change-dict");
}

$.fn.updateItem = function(data) {
    var item = this;
    $.post("/ajax/tree_update",data,function(data){
        $(item).children(":not(ol)").remove();
        $(item).prepend($(data.content).html());
    });
}

$.fn.addItem = function(id,copy=false) {
    var root = false;
    var item = $(this).getItem();
    var parent = $(this).getParentPath(true);
    var branch = $(this).getPath();
    var newitem = $(item).clone();
    var newdata = {};
    var data = $(this).getData(parent);
    if (branch.split("][").length == 1) root = true;;
    if (copy == false) {
        var line = newLine();
        $(newitem).children().remove();
    } else {
        var line = data[id];
        if (copy == true) {
            line["id"] = wbapp.newId();
        } else {
            line["id"] = copy;
        }
    }
    $.each(data,function(i,d){
        newdata[d.id] = d;
        if (d.id == id) {
            newdata[line["id"]] = line;
        }
    });
    $(this).setData(newdata,parent);

    $(newitem).updateItem(line);
    $(item).after(newitem);
}

$.fn.delItem = function() {
    var tree = $(this).getTree();
    var item = $(this).getItem();
    var path = $(this).getPath();
    if ($(tree).children(".wb-tree-item").length == 1 && $(item).parent(".wb-tree").length) {
        var data = $(this).getData();
        var newid = wbapp.newId();
        var line = newLine(newid);
        $(item).children("ol").remove();
        $(item).updateItem(line);
        data.data = {};
        data.data[newid]=line;
        $(this).setData(data);
    } else {
        $(this).delData(path);
        $(this).getItem().remove();
    }
}

$.fn.getTree = function() {
    if ($(this).hasClass("wb-tree")) {
        return this;
    } else {
        return $(this).parents(".wb-tree");
    }
}

$.fn.getItem = function() {
    if ($(this).hasClass(".wb-tree-item")) {
        return this;
    } else {
        return $(this).parent(".wb-tree-item");
    }
}

$.fn.getLine = function() {
    if ($(this).hasClass("wb-tree-line")) {
        return this;
    } else if ($(this).hasClass("wb-tree-item")) {
        return $(this).children(".wb-tree-line");
    } else {
        return $(this).parent(".wb-tree-item").children(".wb-tree-line");
    }
}

$.fn.getPath = function() {
    var line = $(this).getLine();
    var path = $(line).attr("data-id");
    var stop = false;
    var count = 0;
    while(stop == false) {
        count++;
        let parent = $(line).parent(".wb-tree-item").parent("ol");
        if ($(parent).hasClass("wb-tree") || count >= 999) {
            stop = true;
        } else {
            line = $(parent).parent(".wb-tree-item").children(".wb-tree-line");
            path = $(line).attr("data-id") + '.["children"].' + path;
        }
    }
    path = convPath(path);
    return path;
}

$.fn.getParentPath = function(branch=false) {
    var line = $(this).getLine();
    var parent = $(line).parent(".wb-tree-item").parent("ol");
    if (!$(parent).hasClass("wb-tree")) {
        line = $(parent).parent(".wb-tree-item").children(".wb-tree-line");
    } else {
        return "";
    }
    parent =  $(line).getPath();
    if (branch === true ) parent += '["children"]';
    return parent;
}

$.fn.getData = function(path=true) {
    // true - return data+dict
    // false - return dict only
    // path - return data only
    var tree = $(this).getTree();
    var content = $.parseJSON($(tree).children(".wb-tree-data").html());

    if (path === true) {
        eval("var data = content;");
    } else if (path === false) {
        eval("var data = content.dict;");
    } else {
        eval("var data = content.data"+path+";");
        if (data == undefined) {
          if ($(this).is(".wb-tree-line")) {var id = $(this).attr("data-id");} else {var id = wbapp.newId();}
          data = {id:id,name:"",children:{}};
        }
    }
    return data;
}

$.fn.delData = function(path) {
    var tree = $(this).getTree();
    var content = $(tree).getData();
    var data = $(tree).getData(path);
    eval("delete content.data"+path);
    $(tree).setData(content);
    return data;
}

function newLine(newid=null) {
    if (newid === null) {
        var newid = wbapp.newId();
    }
    var line = {
        "id" : newid,
        "name" : newid,
        "active" : "on",
        "children" : {},
        "data" : {}
    };
    return line;
}



function convPath(path) {
        path = path.split(".");
        $.each(path,function(i,v) {
            if (v !== '["children"]') v = '["'+v+'"]'; path[i] = v;
        });
        path = path.join("");
        return path;
}

    $(document).on("nestable-js",function(){
            $(".wb-tree").each(function(e) {
                if ($(this).data("wb-tree") == undefined) {
                    $(this).nestable({
                        maxDepth: 100,
                        rootClass: "wb-tree",
                        itemClass: "wb-tree-item",
                        handleClass: "wb-tree-swp",
                        placeClass: "wb-tree-placeholder"
                    });
                    $(".wb-tree .dd-item").unbind("contextmenu");
                    $(this).data("wb-tree",true);
                    $(this).wbTreeInit();
                }
            });
        });
}

$(document).on("tree-js", function () {
    wb_tree();
});
