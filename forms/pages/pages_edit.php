<div class="modal fade removable" id="{{_form}}_{{_mode}}" data-show="true" data-keyboard="false" data-backdrop="true" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{header}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

<form id="pagesEditForm" data-wb-form="{{_form}}" data-wb-item="{{_item}}"  class="form-horizontal" role="form">
	<div class="form-group row">
	  <label class="col-sm-3 form-control-label">{{_LANG[page]}}</label>
    <div class="input-group col-sm-9">
      <div class="input-group-prepend">
      <span class="input-group-text">
        {{_SRV[HTTP_HOST]}}/
      </span>
      </div>
      <input data-wb="role=module&name=smartid" type="text" class="form-control" name="id" placeholder="{{_LANG[page]}}" >
    </div>
	</div>

  <div class="form-group row">
	  <label class="col-sm-3 form-control-label">{{_lang[tech]}}</label>
	   <div class="col-sm-9"><input type="text" class="form-control" name="techdescr" placeholder="{{_lang[tech]}}" ></div>
	</div>

<div class="nav-active-primary">
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"><a class="nav-link active" href="#{{_form}}Descr" data-toggle="tab">{{_lang[prop]}}</a></li>
	<li class="nav-item"><a class="nav-link" href="#{{_form}}Text" data-toggle="tab" >{{_lang[content]}}</a></li>
	<!--li class="nav-item"><a class="nav-link" href="#pageSource" data-toggle="tab" >{{_lang[source]}}</a></li-->
	<li class="nav-item"><a class="nav-link" href="#{{_form}}Images" data-toggle="tab">{{_lang[images]}}</a></li>
    <li class="nav-item"><a class="nav-link" href="#{{_form}}Seo" data-toggle="tab">{{_lang[seo]}}</a></li>
</ul>
</div>
<div class="tab-content  p-a m-b-md">
<br />
<div id="{{_form}}Descr" class="tab-pane fade show active" role="tabpanel">

	<div class="form-group row">
	  <label class="col-sm-2 form-control-label">{{_lang[header]}}</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="header" placeholder="{{_lang[header]}}"></div>
	</div>

	<div class="form-group row">
	  <label class="col-sm-2 form-control-label">{{_lang[footer]}}</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="footer" placeholder="{{_lang[footer]}}"></div>
	</div>

	<div class="form-group row">
	  <label class="col-sm-2 form-control-label">{{_lang[template]}}</label>
	   <div class="col-sm-10">
		   <select class="form-control" name="template" placeholder="{{_lang[template]}}" data-wb="role=foreach&call=wbListTpl()">
				<option value="{{_value}}">{{_value}}</option>
		   </select>
		</div>
	</div>

  <div class="form-group row">
    <label class="col-sm-2 form-control-label">{{_lang[visible]}}</label>
    <div class="col-sm-2"><label class="switch"><input type="checkbox" name="active"><span></span></label></div>
	</div>
</div>

<div id="{{_form}}Text" class="tab-pane fade" role="tabpanel">
    <meta data-wb="role=include&snippet=editor&value=text" name="text">
</div>
<div id="{{_form}}Images" class="tab-pane fade" role="tabpanel">
    <input data-wb='{"role":"module","load":"filepicker","path":"/uploads/{{_form}}/{{_item}}/"}' name="images">
</div>
<div id="{{_form}}Seo" class="tab-pane fade" data-wb="role=include&form=common_seo.php" role="tabpanel"></div>
</div>
</form>


    </div>
    <div class="modal-footer" data-wb="role=include&form=common_close_save"></div>

		</div>
</div>
</div>
<script type="text/locale" data-wb="role=include&form={{_form}}_edit.ini"></script>
