<form>
  <div class="formgroup row">
          <label class="form-control-label col-sm-4">{{_lang.bkg}}</label>
          <div class="col-sm-8">
              <input data-wb="role=module&load=filepicker&mode=single" name="background" data-wb-path="/uploads/_modules/login" data-wb-ext="jpg|png|gif|svg"/>
          </div>
  </div>
  <div class="formgroup row">
  <label class="form-control-label col-sm-4">{{_lang.blur}}</label>
  <div class="col-sm-8">
      <div class="input-group">
        <input type="number" name="blur" class="form-control" placeholder="{{_lang.blur}}"  />
        <div class="input-group-append">
          <span class="input-group-text">px</span>
        </div>
      </div>
  </div>
  </div>
</form>

<script type="text/locale">
[en]
  save = "Save"
  close = "Close"
  bkg = "Background"
  blur = "Blur"
[ru]
  save = "Сохранить"
  close = "Закрыть"
  bkg = "Фон"
  blur = "Размытие"
</script>
