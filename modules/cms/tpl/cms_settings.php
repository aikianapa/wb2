<div id="cms-settings" data-prop="settings">
  <h3>{{_lang.header}}</h3>
  <form data-wb="role=formdata&form=admin&item=settings">


    <div class="form-group row">
      <label class="col-sm-4 col-12">{{_lang.lang}}</label>
      <div class="col-sm-8 col-12">
      <select class="form-control" name="lang" data-wb="role=foreach&from=_env.locale">
        <option value="{{_key}}">{{_key}} [{{_locale}}]</option>
      </select>
      </div>
    </div>

    <div class="form-group row">
      <label class="form-control-label col-sm-4 col-12">{{_lang.base}}</label>
      <div class="col-sm-8 col-12">
          <input type="text" class="form-control" name="base" placeholder="{{_lang.base}}">
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-4 col-12">{{_lang.editor}}</label>
      <div class="col-sm-8 col-12">
      <select class="form-control" name="editor" data-wb="role=foreach&from=_env.editors">
        <option value="{{_key}}">{{name}} [{{label}}]</option>
      </select>
      </div>
    </div>

    <div class="form-group row">
      <label class="form-control-label col-sm-4 col-12">{{_lang.modcheck}}</label>
      <div class="col-sm-8 col-12">
          <label class="switch">
            <input type="checkbox" name="modcheck">
            <span></span>
          </label>
      </div>
    </div>

    <div class="form-group row">
      <label class="form-control-label col-sm-4 col-12">{{_lang.page_size}}</label>
      <div class="col-sm-8 col-12">
          <input type="number" class="form-control" name="page_size" placeholder="{{_lang.page_size}}">
      </div>
    </div>

    <button type="button" class="btn btn-primary" data-wb="role=save&form=admin&item=settings">{{_lang.save}}</button>
  </form>
</div>
<script type="text/locale">
[en]
  enable = "Enable"
  editor = "Editor"
  header = "System settings"
  modcheck = "Use only enabled modules"
  save = "Save"
  lang = "Language"
  page_size = "Default page size"
  base = "Templates folder"
[ru]
  enable = "Включен"
  editor = "Редактор"
  header = "Настройки системы"
  modcheck = "Подключать выбранные модули"
  save = "Сохранить"
  lang = "Язык"
  page_size = "Размер страниц"
  base = "Папка шаблонов"
</script>
