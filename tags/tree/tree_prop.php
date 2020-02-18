<template type="lang">
    <form data-title="{{_lang.locales}}: " action="javascript:void(0);">
        <div class="form-group row">
            <div class="col-12" data-wb="role=multiinput" name="labels">
                <div class="col-sm-5">
                <select class="form-control" placeholder="{{_lang.lang}}" name="id" data-wb="role=foreach&from=_env.locales">
                    <option value="{{id}}">{{_locale}}</option>
                </select>
                </div>
                <div class="col-sm-7">
                <input class="form-control" placeholder="{{_lang.name}}" type="text" name="name">
                </div>
            </div>
        </div>
    </form>
</template>

<template type="enum">
    <form data-title="{{_lang.enum}}: " action="javascript:void(0);">
        <div class="form-group row">
            <label class="col-sm-3 form-control-label">{{_lang.enum}}</label>
            <div class="col-sm-9">
                <input class="form-control" data-wb="role=module&load=tagsinput" placeholder="{{_lang.enum}}" type="text" name="enum">
            </div>
        </div>
    </form>
</template>

<template type="forms">
    <form data-title="{{_lang.form}}: " action="javascript:void(0);">
      <div class="form-group row">
          <label class="col-sm-3 form-control-label">{{_lang.form}}</label>
          <div class="col-sm-4">
              <select data-wb="role=foreach&from=_env.forms" name="form" class="form-control" placeholder="{{_lang.form}}">
                  <option value='{{_value}}'>{{_value}}</option>
              </select>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 form-control-label">{{_lang.mode}}</label>
          <div class="col-sm-4">
              <input class="form-control" placeholder="{{_lang.mode}}" type="text" name="mode">
          </div>
      </div>
    </form>
</template>



<template type="multiinput">
    <form data-title="{{_lang.multiinput}}: " action="javascript:void(0);">
		<div data-wb="role=dict" name="multiflds"></div>
    </form>
</template>

<template type="common">
    <form data-title="{{_lang.enum}}: " action="javascript:void(0);">
        <div class="form-group row">
            <label class="col-sm-3 form-control-label">{{_lang.unwrap}}</label>
            <div class="col-sm-9">
                <input data-wb="role=include&snippet=switch" name="unwrap">
            </div>
        </div>


        <div class="form-group row">
            <label class="col-sm-3 form-control-label">{{_lang.class}}</label>
            <div class="col-sm-9">
                <input class="form-control" placeholder="{{_lang.class}}" type="text" name="class">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 form-control-label">{{_lang.style}}</label>
            <div class="col-sm-9">
                <input class="form-control" placeholder="{{_lang.style}}" type="text" name="style">
            </div>
        </div>
    </form>
</template>



<script type="text/locale">
[en]
        label	= "Label"
        name    = "Field name"
        type	= "Field type"
        default = "Default value"
        json    = "JSON data"
        css	= "Style CSS"
        other	= "Other"
        plugins = "Plugins"
        form 	= "Form"
        mode	= "Mode"
        class 	= "Сlass"
        style 	= "Style"
        selector= "Selector"
        locales = "Locales"
        lang    = "Language"
        prop	= "Properties"
        multiinput = "Multiinput"
        enum    = "Enum"
        unwrap  = "Unwrap"
[ru]
        label	= "Метка"
        name    = "Имя поля"
        type	= "Тип поля"
        default = "Значение по-умолчанию"
        json    = "Данные JSON"
        css	= "Стиль CSS"
        other	= "Другие"
        plugins = "Плагины"
        form 	= "Форма"
        mode	= "Режим"
        class 	= "Класс"
        style = "Стиль"
        selector= "Селектор"
        locales = "Локализации"
        lang    = "Язык"
        prop	= "Свойства"
        multiinput = "Мультиполе"
        enum    = "Перечисление"
        unwrap  = "В колонку"
</script>
