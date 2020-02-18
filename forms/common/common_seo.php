<div class="form-group row">
    <label class="col-sm-3 form-control-label">{{_lang.title}}</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="title" placeholder="{{_lang.title}}"> </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 form-control-label">{{_lang.descr}}</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="meta_description" placeholder="{{_lang.descr}}"> </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 form-control-label">{{_lang.keywords}}</label>
    <div class="col-sm-9">
        <input type="text" class="form-control input-tags" name="meta_keywords" placeholder="{{_lang.keywords}}"> </div>
</div>

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"><a class="nav-link active" href="#{{_form}}SeoHead" data-toggle="tab">{{_lang.head_inc}}</a></li>
	<li class="nav-item"><a class="nav-link" href="#{{_form}}SeoBody" data-toggle="tab" >{{_lang.body_inc}}</a></li>
</ul>

<div class="tab-content  p-a m-b-md">
<br />
<div id="{{_form}}SeoHead" class="tab-pane fade show active" role="tabpanel">
  <div class="form-group row">
      <div class="col-sm-3">
          <h6 class="form-control-label">{{_lang.head_inc}}</h6>
          <label class="col-12 form-control-label">{{_lang.local_on}}</label>
          <div class="col-sm-2">
              <label class="switch switch-success">
                  <input type="checkbox" name="head_add_active"><span></span></label>
          </div>
          <label class="col-12 form-control-label">{{_lang.glob_off}}</label>
          <div class="col-sm-2">
              <label class="switch switch-success">
                  <input type="checkbox" name="head_noadd_glob"><span></span></label>
          </div>
      </div>
      <div class="col-sm-9">
                <meta data-wb="role=include&snippet=source" data-wb-name="head_add">
      </div>
  </div>
</div>
<div id="{{_form}}SeoBody" class="tab-pane fade show" role="tabpanel">

  <div class="form-group row">
      <div class="col-sm-3">
          <h6 class="form-control-label">{{_lang.body_inc}}</h6>
          <label class="col-12 form-control-label">{{_lang.local_on}}</label>
          <div class="col-sm-2">
              <label class="switch switch-success">
                  <input type="checkbox" name="body_add_active"><span></span></label>
          </div>
          <label class="col-12 form-control-label">{{_lang.glob_off}}</label>
          <div class="col-sm-2">
              <label class="switch switch-success">
                  <input type="checkbox" name="body_noadd_glob"><span></span></label>
          </div>
      </div>
      <div class="col-sm-9">
                <meta data-wb="role=include&snippet=source" data-wb-name="body_add">
      </div>
  </div>
</div>
</div>

<script type="text/locale">
[en]
title           = "Title"
descr           = "META Description"
keywords        = "META Keywords"
head_inc        = "Append to HEAD"
body_inc        = "Append to BODY"
local_on        = "Turn On local append"
glob_off        = "Turn Off global append"
[ru]
title           = "Заголовок (title)"
descr           = "META Описание"
keywords        = "META Ключевые слова"
head_inc        = "Вставка в HEAD"
body_inc        = "Вставка в BODY"
local_on        = "Включить локальную вставку"
glob_off        = "Отключить глобальную вставку"
</script>
