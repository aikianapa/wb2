$(document).on("yamap-js", function() {
  wbapp.loadScripts([
    "https://api-maps.yandex.ru/2.1/?apikey=1a2c9fab-5efe-4df5-9b87-4dab6b652569&lang=ru_RU"
  ], "yandex-map-js");
});

$(document).on("yandex-map-js", function() {
  function yamap() {

      $.fn.yamap_canvas = function() {
        var yamap = $(this).parents(".yamap");
        var mid = $(this).attr("id");
        var editor = false;
        var points = [];
        var epoints = [];
        var cc = [];
        var canvas = this;
        var zoom = $(this).attr("zoom") * 1;

        $(canvas).data("props",{});
        if (!$(yamap).find(".yamap_editor").length) {
            if (!$(this).find("[role=geopos]").length) {
              epoints = json_decode($(this).html());
              $(this).html("");
            }
        } else {
          editor = true;
          epoints = json_decode($(yamap).find(".yamap_editor").find(".wb-multiinput-data").text());

        }

        if ($(this).attr("geopos") > "") var ll = yamap_pos($(this).attr("geopos"));
        if ($(this).attr("center") > "") var cc = yamap_pos($(this).attr("center"));

        if ($(this).attr("height") > "") $(this).height($(this).attr("height"));
        if ($(this).attr("width")>"") $(this).width($(this).attr("width"));
        if ($(this).attr("name") > "") $(this).find(".yamap_data").attr("name", $(this).attr("name"));

        var height = $(this).height();
        $(window).on("resize", function() {
          $(this).height = height;
        });


        if ($(epoints).length) {
          $(epoints).each(function(i, item) {
            var point = {
              pos: yamap_pos(item.geopos),
              content: item.address,
              title: item.title,
              geofld: $(yamap).find(".yamap_editor [data-wb-field=geopos]:eq(" + i + ")")
            };

            if (point.pos.length == 2) {
              points.push(point);
              if (i == 0) cc = point.pos;
            }
          });
        } else {
          if ($(this).find("[role=geopos]").length) {
            $(this).find("[role=geopos]").each(function(i) {
              var point = {
                pos: yamap_pos($(this).attr("value")),
                content: $(this).html(),
                title: $(this).attr("title")
              };
              if (point.pos.length == 2) {
                points.push(point);
                if (i == 0) cc = point.pos;
              }
              console.log(point);
            });
          } else if (ll !== undefined && ll > "") {
            var point = {
              pos: yamap_pos(ll),
              content: $(this).html(),
              title: $(this).attr("title")
            };
          }

        }
        var map = new ymaps.Map(mid, {
          center: cc,
          zoom: zoom,
          controls: ["zoomControl", "fullscreenControl"]
        });
        if (cc.length !== 2) var ll = yamap_pos("44.894997 37.316259");
        map.behaviors.disable("scrollZoom");
        var clusterer = new ymaps.Clusterer();
        $(canvas).data("props",{
            map:map,
            clusterer:clusterer,
            editor:editor,
            zoom:zoom
        });
        if ($(points).length) {
            $(points).each(function(i, point) {
                $(canvas).yamap_addPoint(point);
            });
            yamap_autozoom(map);
        }
        $(this).yamap_geo();
        $(this).css("opacity",1);
      }

      $.fn.yamap_addPoint = function(point) {
        let canvas = this;
        let map = $(canvas).data("props").map;
        let clusterer = $(canvas).data("props").clusterer;
        let editor = $(canvas).data("props").editor;

        let myPlacemark = new ymaps.Placemark(point.pos, {
          balloonContentHeader: point.title,
          balloonContent: point.content,
          pos: point.pos
        }, {
          draggable: editor, // метку можно перемещать
        });
        if (point.geofld !== undefined) {
          //clusterer.remove(myPlacemark);
          $(point.geofld).data("placemark", myPlacemark);
          myPlacemark.events.add('dragend', function(e) {
            var canvasPlacemark = e.get('target');
            var pos = canvasPlacemark.geometry.getCoordinates();
            $(point.geofld).val(pos[0] + " " + pos[1]);
            $(point.geofld).parents(".wb-multiinput").store();
          });
        }
        clusterer.add(myPlacemark);
        map.geoObjects.add(clusterer);
      }

      $.fn.yamap_geo = function() {
        let canvas = this;
        let yamap = $(this).parents(".yamap");
        let map = $(canvas).data("props").map;
        let zoom = $(canvas).data("props").zoom;
        let clusterer = $(canvas).data("props").clusterer;

        $(yamap).undelegate(".yamap_editor", "before_remove");
        $(yamap).delegate(".yamap_editor", "before_remove", function(e, line) {
            // Удаление точки
            var geo = $(line).find("[data-wb-field=geopos]");
            var myPlacemark = $(geo).data("placemark");
            if (myPlacemark !== undefined) {
              clusterer.remove(myPlacemark);
              yamap_autozoom(map);
            }
        });

        $(yamap).find(".yamap_editor").delegate(".wb-multiinput", "click", function() {
          var geo = $(this).find("[data-wb-field=geopos]");
          var pos = explode(" ", $(geo).val());
          map.setCenter(pos);
        });


        $(yamap).find(".yamap_editor").delegate(".find", "click touch", function() {
            $(this).parents(".wb-multiinput-row").find(".finder").trigger("change");
        });

        $(yamap).find(".yamap_editor").delegate(".finder", "change", function(e) {
            // Вывод точек в редакторе
                var finder = $(this).val();
                var row = $(this).parents(".wb-multiinput-row");
                var geo = $(row).find("[data-wb-field=geopos]");
                var addr = $(row).find("[data-wb-field=address]");
                var title = $(row).find("[data-wb-field=title]").val();

                var myPlacemark = $(geo).data("placemark");
                if (myPlacemark !== undefined) clusterer.remove(myPlacemark);

                var pos = yamap_pos($(geo).val());

                if (title == undefined) var title = "";

                ymaps.geocode(finder, {
                  results: 1
                }).then(function(res) {
                  var obj = res.geoObjects.get(0);
                  var pos = res.geoObjects.get(0).geometry._coordinates;
                  $(geo).val(implode(" ", pos));
                  $(addr).val(finder);
                  var point = {
                    pos: pos,
                    content: finder,
                    title: title,
                    geofld: geo
                  };
                  $(canvas).yamap_addPoint(point);
                  yamap_autozoom(map);
                  map.setCenter(pos);
                  $(row).parents(".wb-multiinput").store();
                }).fail(function(err) {

                  console.log(err.message);

                });
        });

      }


    $(".yamap:not(.done)").each(function(i) {
      var yamap = this;
      var width = "100%";
      var height = "300px";
      var zoom = 10;
      $(this).addClass("done");
      if ($(this).attr("id") == undefined) {
        $(this).attr("id", "ym-" + wbapp.newId());
      }
      var id = $(this).attr("id");
      var editor = $(this).children(".yamap-editor");
      var canvas = $(this).children(".yamap_canvas");
      var mid = "c" + id;
      $(canvas)
        .attr("id", mid)
        .css("height", height)
        .width("width", width);
      if ($(this).attr("zoom") !== undefined) zoom = $(this).attr("zoom");
      $(canvas).attr("zoom", zoom * 1);
      if ($(this).attr("center") !== undefined) {
        $(canvas).attr("center", $(this).attr("center"));
        $(canvas).yamap_canvas();
      } else {
        ymaps.geolocation.get().then(function(res) {
          $(canvas).attr("center", res.geoObjects["position"][0] + " " + res.geoObjects["position"][1]);
          $(canvas).yamap_canvas();
        });
      }
    });

    console.log("Trigger: yamap-plugin");
    $(document).trigger("yamap-plugin");

  }

  function yamap_autozoom(map) {
    var bounds = map.geoObjects.getBounds();
    if (!bounds) return;
    if (bounds[0][0] == bounds[1][0] && bounds[0][1] == bounds[1][1]) return;
        console.log(bounds[0],bounds[1]);
    map.setBounds(bounds,{checkZoomRange:true, zoomMargin:7});
  }

  function yamap_pos(ll) {
    ll = trim(ll);
    ll = str_replace(",", " ", ll);
    ll = str_replace("  ", " ", ll);
    var tmp = explode(" ", ll);
    if (tmp.length == 2) {
      return [tmp[0], tmp[1]];
    } else {
      return [];
    }
  }



  ymaps.ready(yamap);
});
