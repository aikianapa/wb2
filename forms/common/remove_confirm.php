<div class="modal fade removable" id="confirm_{{_form}}_{{_item}}" data-show="true" data-keyboard="false" data-backdrop="true" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{_lang.title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-3">
				<i class="fa fa-warning fa-4x text-danger"></i>
			</div>
			<div class="col-9">
				{{_lang.confirm}}
			</div>
		</div>
          <div class="alert alert-warning text-center d-none" style="margin-top:20px;">{{_lang.error}}</div>
      </div>

		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> {{_lang.cancel}}</button>
			<button type="button" class="btn btn-danger wb-remove-confirm" data-dismiss="modal"
					   data-wb="role=save&form={{_form}}&item={{_item}}&remove=true&watcher=#{{_form}}List">
					 <span class="fa fa-trash"></span> {{_lang.remove}}
			</button>
		  </div>
		</div>
</div>
</div>
<script data-wb-tag="success" language="javascript">
	if ($("[data-wb-table='{{_form}}']").length) {
		$("[data-wb-table='{{_form}}'] [idx='{{_item}}']").remove();
	} else {
		$(document).find("[idx='{{_item}}']").remove();
	}
</script>
<script type="text/locale">
[en]
	title		= "Remove item"
	error 		= "WARNING! Remove item error."
	remove		= "Remove"
	cancel		= "Cancel"
	confirm		= "Confirm remove item [ {{_item}} ]<br>in form [ {{_form}} ]"
[ru]
	title		= "Удаление записи"
	error 		= "ВНИМАНИЕ! Ошибка удаления записи"
	remove		= "Удалить"
	cancel		= "Отмена"
	confirm		= "Пожалуйста, подтвердите удаление записи с идентификатором {{_item}} из таблицы {{_form}}"
</script>
