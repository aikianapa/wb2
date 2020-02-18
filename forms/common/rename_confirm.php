<div class="modal fade removable" id="confirm_{{_form}}_{{_item}}" data-show="true" data-keyboard="false" data-backdrop="true" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal" role="form" id="rename_{{_form}}_{{_item}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{_lang.title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <div class="col-3">
                            <i class="fa fa-warning fa-4x text-warning"></i>
                        </div>
                        <div class="col-9">
                            {{_lang.confirm}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">{{_lang.new_id}}</label>
                        <div class="col-sm-9"><input data-wb="role=module&name=smartid" type="text" class="form-control" name="id" placeholder="{{_lang.new_id}}" value="{{_item}}"></div>
                    </div>
                    <div class="alert alert-warning text-center d-none" style="margin-top:20px;">{{_lang.error}}</div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> {{_lang.cancel}}</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" data-wb="role=save&form={{_form}}&item={{_item}}&selector=#rename_{{_form}}_{{_item}}&watcher=#{{_form}}List">
                        <span class="fa fa-trash"></span> {{_lang.rename}}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/locale">
[en]
    title		= "Rename item"
    error 		= "WARNING! rename item error."
    rename		= "Rename"
    cancel		= "Cancel"
    old_id      = "Previous ID"
    new_id      = "New ID"
    confirm		= "Rename item with ID <b>{{_item}}</b> in form <b>{{_form}}</b>."
[ru]
    title		= "Переименование записи"
    error 		= "ВНИМАНИЕ! Ошибка удаления записи"
    rename		= "Переименовать"
    old_id      = "Предыдущий ID"
    new_id      = "Новый ID"
    cancel		= "Отмена"
    confirm		= "Переименование записи с идентификатором <b>{{_item}}</b> в форме <b>{{_form}}</b>."
</script>
