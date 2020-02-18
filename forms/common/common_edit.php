<div class="modal fade removable" id="{{_form}}_{{_mode}}" data-show="true" data-keyboard="false"
  data-backdrop="true" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">{{_lang.title}}</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
      <div class="modal-body">

<form id="{{_form}}EditForm" data-wb-form="{{_form}}" data-wb-item="{{_item}}"  class="form-horizontal" role="form">
	<div class="form-group row">
	  <label class="col-sm-2 form-control-label">{{_lang.name}}</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="id" placeholder="{{_lang.name}}" required ></div>
	</div>

<div class="nav-active-primary">
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"><a class="nav-link active" href="#{{_form}}Descr" data-toggle="tab">{{_lang.prop}}</a></li>
	<li class="nav-item"><a class="nav-link" href="#{{_form}}Text" data-toggle="tab" >{{_lang.content}}</a></li>
	<li class="nav-item"><a class="nav-link" href="#{{_form}}Images" data-toggle="tab">{{_lang.images}}</a></li>
</ul>
</div>
<div class="tab-content  p-a m-b-md">
<br />
<div id="{{_form}}Descr" class="tab-pane fade show active" role="tabpanel">

	<div class="form-group row">
	  <label class="col-sm-2 form-control-label">{{_lang.header}}</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="header" placeholder="{{_lang.header}}"></div>
	</div>

	<div class="form-group row">
	  <label class="col-sm-2 form-control-label">{{_lang.descr}}</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="meta_description" placeholder="{{_lang.descr}}"></div>
	</div>

	<div class="form-group row">
		<label class="col-sm-2 form-control-label">{{_lang.visible}}</label>
		<div class="col-sm-2"><label class="switch switch-success"><input type="checkbox" name="active"><span></span></label></div>
	</div>


</div>

<div id="{{_form}}Text" class="tab-pane fade" role="tabpanel">
  <meta data-wb="role=include&snippet=editor&value=text" name="text">
</div>
<div id="{{_form}}Images" class="tab-pane fade" role="tabpanel">
  <input data-wb='{"role":"module","load":"filepicker","path":"/uploads/{{_form}}/{{_item}}/"}' name="images">
</div>
</div>
</form>


    </div>
		  <div class="modal-footer" data-wb="role=include&form=common_close_save&hide=true"></div>

		</div>
</div>
</div>

<script type="text/locale">
[en]
        title		= "Edit item"
	name            = "Item name"
	header		= "Header"
	descr		= "Description"
	visible		= "Visible"
	keywords	= "Keywords"
	prop		= "Properties"
	content		= "Content"
	images		= "Images"
[ru]
        title		= "Редактирование записи"
	name            = "Имя записи"
	header		= "Заголовок"
	descr		= "Описание"
	visible		= "Отображать"
	keywords	= "Ключевые слова"
	prop		= "Характеристики"
	content		= "Контент"
	images		= "Изображения"
</script>
