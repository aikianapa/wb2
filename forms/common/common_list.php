<div class="element-content mt-2">
    <h5 class="element-header">
                     {{_lang.title}}
                     <button class="btn btn-sm btn-success pull-right" data-wb="role=ajax&url=/form/{{_form}}/edit/_new&append=#content">
                       <i class="fa fa-plus"></i> {{_lang.add}}
                     </button>
    </h5>
    <div class="element-box mt-3">
        <div class="table-responsive">
            <table class="table table-lightborder">
                <thead class="thead-dark">
                    <tr>
                        <th>{{_lang.name}}</th>
                        <th>{{_lang.header}}</th>
                        <th class="text-center"> {{_lang.visible}} </th>
                        <th class="text-right"> {{_lang.action}} </th>
                    </tr>
                </thead>
                <tbody data-wb="role=foreach&form={{_form}}&size={{_sett.page_size}}&sort=id" id="{{_form}}List">
                    <tr data-watcher="item={{id}}&watcher=#{{_form}}List">
                        <td class="nowrap">{{id}}</td>
                        <td class="nowrap">{{header}}</td>
                        <td class="text-center">
                          <label class="switch">
                            <input type="checkbox" name="active" data-wb="role=save&form={{_form}}&item={{id}}&field=active">
                            <span></span>
                          </label>
                        </td>
                        <td class="text-right"  data-wb="role=include&form=common_actions"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/locale">
[en]
        title		= "Items list"
	name            = "Item name"
	header		= "Header"
	visible		= "Visible"
        invisible	= "Invisible"
	action		= "action"
        add             = "Add item"
[ru]
        title		= "Список записей"
	name            = "Имя записи"
	header		= "Заголовок"
	visible		= "Отображать"
        invisible	= "Не отображать"
	action		= "Действие"
        add             = "Добавить запись"
</script>
