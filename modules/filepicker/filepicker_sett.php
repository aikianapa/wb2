<form>
  <div class="formgroup row">
          <label class="form-control-label col-sm-4">{{_lang.resizeTo}}</label>
          <div class="col-sm-8">
              <div class="input-group">
                <input type="number" name="resizeto" class="form-control" placeholder="{{_lang.resizeTo}}" min="10" max="10000"  />
                <div class="input-group-append">
                  <span class="input-group-text">px</span>
                </div>
              </div>
              <small>{{_lang.resizeToHint}}</small>
          </div>
          <label class="form-control-label col-sm-4">{{_lang.quality}}</label>
          <div class="col-sm-8">
              <div class="input-group">
                <input type="number" name="quality" class="form-control" placeholder="{{_lang.quality}}" min="1" max="100"  />
                <div class="input-group-append">
                  <span class="input-group-text">%</span>
                </div>
              </div>
          </div>
        </div>
</form>

<script type="text/locale">
[en]
  quality = "Image quality"
  resizeTo = "Resize to"
  resizeToHint = "Change the image size if the original image width is greater than the specified width"
[ru]
  resizeTo = "Уменьшать до"
  resizeToHint = "Изменить размер изображения, если исходная ширина изображения более указанной ширина"
  quality = "Качество"
</script>
