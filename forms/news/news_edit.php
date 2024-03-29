<div class="modal fade removable" id="{{_form}}_{{_mode}}" data-show="true" data-keyboard="false"
  data-backdrop="true" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{header}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form autocomplete="off" id="{{_form}}EditForm" data-wb-form="{{_form}}" data-wb-item="{{_item}}"
          class="form-horizontal" role="form">
          <input type="hidden" class="form-control" name="id" required>

          <div class="form-group row">
            <label class="col-sm-2 form-control-label">{{_LANG[datetime]}}</label>
            <meta data-wb-role="variable" var="date" data-wb-if='date>""' value='{{date("Y-m-d H:i:s",{{strtotime({{date}})}})}}'
              else='{{date("Y-m-d H:i:s")}}' data-wb-hide="*">
            <div class="col-sm-4">
              <input type="datetimepicker" data-wb="role=module&load=datetimepicker"
                class="form-control" name="date" value="{{_var.date}}" placeholder="{{_LANG[datetime]}}"
                required>
            </div>
            <label class="col-sm-2 form-control-label">{{_LANG[visible]}}</label>
            <div class="col-sm-2">
              <label class="switch switch-success">
                <input type="checkbox" name="active">
                <span></span>
              </label>
            </div>
          </div>

          <div class="nav-active-primary">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" href="#{{_form}}Descr" data-toggle="tab">{{_LANG[content]}}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#{{_form}}Images" data-toggle="tab">{{_LANG[images]}}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#{{_form}}Seo" data-toggle="tab">{{_LANG[seo]}}</a>
              </li>
            </ul>
          </div>
          <div class="tab-content  p-a m-b-md">
            <br />
            <div id="{{_form}}Descr" class="tab-pane fade show active" role="tabpanel">

              <div class="form-group row">
                <label class="col-sm-3 form-control-label">{{_LANG[header]}}</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="header" placeholder="{{_LANG[header]}}"
                    required>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 form-control-label">{{_LANG[short]}}</label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="descr" placeholder="{{_LANG[short]}}"></textarea>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 form-control-label">
                  {{_LANG[content]}}
                  <div class="row">
                    <div class="col-6 col-sm-12">
                      <label class="col-12 form-control-label">{{_LANG[home]}}</label>
                      <div class="col-12">
                        <label class="switch">
                          <input type="checkbox" name="home">
                          <span></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </label>
                <div class="col-sm-9">
                  <meta data-wb="role=include&snippet=editor&value=text" name="text">
                </div>
              </div>

            </div>
            <div id="{{_form}}Images" class="tab-pane fade" role="tabpanel">
              <input data-wb='{"role":"module","load":"filepicker","path":"/uploads/{{_form}}/{{_item}}/"}' name="images">
            </div>
            <div id="{{_form}}Seo" class="tab-pane fade" data-wb="role=include&form=common_seo.php"
              role="tabpanel"></div>
          </div>

        </form>
      </div>
      <div class="modal-footer" data-wb="role=include&form=common_close_save"></div>
    </div>
  </div>
</div>
<script type="text/locale" data-wb="role=include&form={{_form}}_edit.ini"></script>
