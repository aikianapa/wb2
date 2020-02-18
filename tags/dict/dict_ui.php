    <div class="col-12 wb-dict" data-wb="role=multiinput" name="dict">
        <div class="col-4">
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="input-group-prepend wb-tree-dict-prop-btn">
                        <span class="input-group-text text-blue">
                            <i class='material-icons-outlined'>settings_applications</i>
                        </span>
                    </div>
                    <input class="form-control" placeholder="{{_lang.name}}" type="text" name="name">
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="input-group-prepend wb-tree-dict-lang-btn">
                        <span class="input-group-text text-blue">
                            <i class='material-icons-outlined'>g_translate</i>
                        </span>
                    </div>
                    <input class="form-control" placeholder="{{_lang.label}}" type="text" name="label">
                    <textarea type="json" name="lang" class="d-none"></textarea>
                </div>
            </div>
        </div>
        <div class="col-4">
            <select class="form-control wb-done" name="type" placeholder="{{_lang.type}}">
                <option value="string">string</option>
                <option value="text">text</option>
                <option value="number">number</option>
                <option value="checkbox">checkbox</option>
                <option disabled>--== {{_lang.plugins}} ==--</option>
                <option value="forms">forms</option>
                <option value="editor">editor</option>
                <option value="source">source</option>
                <option value="gallery">gallery</option>
                <option value="image">image</option>
                <option value="multiinput">multiinput</option>
                <option value="switch">switch</option>
                <option value="enum">enum</option>
                <option value="tree">tree</option>
                <option value="snippet">snippet</option>
                <option value="tags">tags</option>
                <option value="phone">phone</option>
                <option value="mask">mask</option>
                <option value="module">module</option>
                <option value="datepicker">datepicker</option>
                <option value="datetimepicker">datetimepicker</option>
                <option disabled>--== {{_lang.other}} ==--</option>
                <option value="tel">tel</option>
                <option value="date">date</option>
                <option value="week">week</option>
                <option value="month">month</option>
                <option value="year">year</option>
                <option value="time">time</option>
                <option value="color">color</option>
            </select>
            <input type="hidden" name="value">
            <textarea type="json" name="prop" class="d-none"></textarea>
        </div>
    </div>

<script id="dict-js-remove">wbapp.loadScripts(["/engine/tags/dict/dict.js"],"dict-js");</script>
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
        class 	= "Style class"
        selector= "Selector"
        locales = "Locales"
        prop	= "Properties"
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
        class 	= "Класс стиля"
        selector= "Селектор"
        locales = "Локализации"
        prop	= "Свойства"
</script>
