<div class="yamap">
  <div class="yamap_editor form-group" data-wb="role=multiinput" name="yamap">
      <div class="col-sm-5">
        <div class="input-group input-group-sm">
          <input type="text" class="form-control finder" name="address" placeholder="Адрес">
          <div class="input-group-append find" style="cursor:alias;">
            <i class="input-group-text material-icons">location_searching</i>
          </div>
        </div>
      </div>
      <div class="col-sm-4"><input class="form-control form-control-sm" name="title"  placeholder="Описание"></div>
      <div class="col-sm-3"><input class="form-control form-control-sm" name="geopos" placeholder="Геопозиция" readonly></div>
  </div>
  <div class="yamap_canvas"></div>
</div>
<script type="wbapp">
  wbapp.loadScripts(["/engine/modules/yamap/yamap.js"],"yamap-js");
</script>
<style>
  .yamap .yamap_canvas {
      opacity: 0;
      transition-duration: 0.3s;
  }
</style>
