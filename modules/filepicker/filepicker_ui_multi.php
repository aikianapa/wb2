<script type="wbapp">wbapp.loadScripts(["/engine/modules/filepicker/filepicker.js"],"filepicker-js")</script>
<div class="filepicker">
  <textarea type="json" name class="d-none filepicker-data"></textarea>
  <!-- Button Bar -->
  <div class="button-bar">
    <div class="btn btn-success fileinput">
      <i class="fa fa-image"></i><span class="d-none d-md-inline"> {{_lang.choose}}</span>
      <input type="file" name="files[]" multiple class="wb-unsaved">
      <input type="hidden" name="upload_url" class="wb-unsaved">
    </div>

    <button type="button" class="btn btn-primary camera-show">
      <i class="fa fa-camera"></i><span class="d-none d-md-inline"> {{_lang.camera}}</span>
    </button>

    <!--button type="button" class="btn btn-info start-all">
      <i class="fa fa-arrow-circle-o-up"></i> Start all
    </button>

    <button type="button" class="btn btn-warning cancel-all">
      <i class="fa fa-ban"></i> Cancel all
    </button>

    <button type="button" class="btn btn-danger delete-all">
      <i class="fa fa-trash-o"></i> Delete all
    </button-->
  </div>

  <!-- Files -->
  <div class="listview row">

  </div>

  <template id="fp-listviewItem">
    <div class="card col-2 p-2 m-2">
      <img class="card-img-top" src="/thumb/200x200/src/%url%" data-src="%url%" data-img='%name%' title="%title%" alt="%alt%">
      <div class="card-body">
        <a href="#" class="action action-primary crop"><i class="fa fa-crop"></i></a>
        <a href="#" class="action action-danger delete pull-right"><i class="fa fa-trash-o text-danger"></i></a>
      </div>
    </div>
  </template>

  <!-- Drop Window -->
  <div class="drop-window">
    <div class="drop-window-content">
      <h3><i class="fa fa-upload"></i> Drop files to upload</h3>
    </div>
  </div>


  <!-- Crop Modal -->
  <div id="crop-modal" class="modal fade" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <span class="close" data-dismiss="modal">&times;</span>
          <h4 class="modal-title">Make a selection</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning crop-loading">Loading image...</div>
          <div class="crop-preview"></div>
        </div>
        <div class="modal-footer">
          <div class="crop-rotate">
            <button type="button" class="btn btn-default btn-sm crop-rotate-left" title="Rotate left">
              <i class="fa fa-undo"></i>
            </button>
            <button type="button" class="btn btn-default btn-sm crop-flip-horizontal" title="Flip horizontal">
              <i class="fa fa-arrows-h"></i>
            </button>
            <button type="button" class="btn btn-default btn-sm crop-flip-vertical" title="Flip vertical">
              <i class="fa fa-arrows-v"></i>
            </button>
            <button type="button" class="btn btn-default btn-sm crop-rotate-right" title="Rotate right">
              <i class="fa fa-repeat"></i>
            </button>
          </div>
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success crop-save" data-loading-text="Saving...">Save</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end of #crop-modal -->

  <!-- Camera Modal -->
  <div id="camera-modal" class="modal fade" tabindex="-1"  data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <span class="close" data-dismiss="modal">&times;</span>
          <h4 class="modal-title">Take a picture</h4>
        </div>
        <div class="modal-body">
          <div class="camera-preview"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left camera-hide" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success camera-capture">Take picture</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end of #camera-modal -->

<script type="text/locale">
[en]
camera = "Camera"
choose = "Choose files"
[ru]
camera = "Камера"
choose = "Выбрать файлы"
</script>
</div>
<!-- end of #filepicker -->
