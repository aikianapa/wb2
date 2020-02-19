<div class="modal fade removable" id="{{_form}}_{{_mode}}" data-show="true" data-keyboard="false"
  data-backdrop="true" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{_lang.title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
                <form id="{{_form}}EditForm" data-wb-form="{{_form}}" data-wb-item="{{_item}}" class="form-horizontal" role="form">
                    <div class="row">
                        <div class="col-12 tree-view">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label">{{_lang.name}}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" data-wb="role=module&load=smartid" name="id" placeholder="{{_lang.name}}" required>
                                </div>
                            </div>
                            <div class="tab-content p-a m-b-md">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label">{{_lang.header}}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="header" placeholder="{{_lang.header}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label">{{_lang.tech}}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="techdescr" placeholder="{{_lang.tech}}">
                                    </div>
                                </div>
                                <input data-wb="role=tree&form={{_form}}&item={{_item}}&field=tree" name="tree">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" data-wb="role=include&form=common_close_save&hide=true"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/locale" data-wb="role=include&form=tree_common.ini"></script>
