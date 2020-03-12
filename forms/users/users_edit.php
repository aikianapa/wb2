<div class="modal fade removable" id="{{_form}}_{{_mode}}" data-show="true" data-keyboard="false"
  data-backdrop="true" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
			{{_LANG[header]}}
		</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- User form -->
        <form id="{{_form}}EditForm" class="form-horizontal" role="form" data-wb-allow="admin"
          data-wb-where='"{{_route.params.group}}" != "true" AND isgroup = ""'>
          <div class="form-group row">
            <div class="col-sm-4">
              <label class="form-control-label">{{_LANG[login]}}
                <span class="text-danger" data-wb-where='super == "on"'>[superuser]</span>
              </label>
              <div class="input-group">
                <input type="text" class="form-control" name="id" placeholder="{{_LANG[login]}}"
                  required data-wb-enabled="admin">
                <input type="hidden" class="form-control" name="password">
                <div class="input-group-append" onclick="$('#{{_form}}_{{_mode}}_changePswd').toggle('fade');"
                  data-wb-allow="admin"><i class="bg-warning input-group-text fa fa-key"></i></div>
              </div>
            </div>
            <div class="col-sm-4">
              <label class="form-control-label">{{_LANG[group]}}</label>
              <select class="form-control" placeholder="" name="role" data-wb="role=foreach&form=users&tpl=false"
                data-wb-if='isgroup="on" AND active="on"'>
                <option value="{{id}}">{{id}}</option>
              </select>
            </div>
            <div class="col-sm-4">
              <label class="form-control-label col-12">{{_lang.active}}</label>
              <label class="switch">
                <input type="checkbox" name="active">
                <span></span>
              </label>
            </div>
          </div>

          <div data-wb="role=include&form=common_changePassword&hide=true"></div>


          <div class="form-group row">
            <div class="col-sm-4">
              <label class="form-control-label">{{_lang.firstname}}</label>
              <input type="text" class="form-control" name="first_name" placeholder="{{_lang.firstname}}">
            </div>
            <div class="col-sm-4">
              <label class="form-control-label">{{_lang.lastname}}</label>
              <input type="text" class="form-control" name="last_name" placeholder="{{_lang.lastname}}">
            </div>
            <div class="col-sm-4">
              <label class="form-control-label">{{_lang.email}}</label>
              <input type="text" class="form-control" name="email" placeholder="{{_lang.email}}">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-4 form-control-label">{{_LANG[lang]}}</label>
            <div class="col-sm-8">
              <select class="form-control" name="lang" data-wb="role=foreach&from=_env.locales">
                <option value="{{_key}}">{{_key}} [{{_locale}} ]</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-12 form-control-label">{{_LANG[about]}}:</label>
            <div class="col-12">
              <meta placeholder="{{_LANG[about]}}" name="text" data-wb="role=include&snippet=editor">
            </div>
          </div>

        </form>
        <!-- Group form -->
        <form id="{{_form}}EditForm" class="form-horizontal" role="form" data-wb-allow="admin"
          data-wb-where='"{{_route.params.group}}" = "true" OR isgroup = "on"'>
          <input type="hidden" name="isgroup" value="on">
          <div class="form-group row">
            <div class="col-sm-4">
              <label class="form-control-label">{{_lang.group}}</label>
            </div>
            <div class="col-auto">
              <input type="text" class="form-control" name="id" placeholder="{{_lang.group}}" required
                data-wb-enabled="admin">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-4">
              <label class="form-control-label">{{_lang.name}}</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="name" placeholder="{{_lang.name}}"
                required data-wb-enabled="admin">
            </div>
          </div>
          <div class="form-group row">
            <label class="form-control-label col-sm-4">{{_lang.active}}</label>
            <div class="col-sm-4">
              <label class="switch switch-success">
                <input type="checkbox" name="active">
                <span></span>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-4">
              <label class="form-control-label">{{_LANG[login_url]}}</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="login_url" placeholder="{{_LANG[login_url]}}">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-4">
              <label class="form-control-label">{{_LANG[logout_url]}}</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="logout_url" placeholder="{{_LANG[logout_url]}}">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-4 form-control-label">{{_LANG[lang]}}</label>
            <div class="col-sm-8">
              <select class="form-control" name="lang" data-wb="role=foreach&from=_env.locales">
                <option value="{{_key}}">{{_key}} [{{_locale}} ]</option>
              </select>
            </div>

          </div>
        </form>

      </div>
      <div class="modal-footer" data-wb="role=include&form=common_close_save">
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/locale" data-wb="role=include&form=users_common.ini"></script>
<script>
/*
	$("#{{_form}}_{{_mode}} [name=isgroup]").on("change",function(){
		console.log($("#{{_form}}_{{_mode}} [name=isgroup]").val());
		if ( $("#{{_form}}_{{_mode}} [name=isgroup]").val() !== "on" ) {
			$("#{{_form}}Group").parents("form").find(".nav-tabs	").find(".nav-link").removeClass("active");
			$("#{{_form}}Group").parents(".tab-content").find(".tab-pane").removeClass("show active");
			if ($(this).is(":checked")) {
				$("#{{_form}}Group").addClass("show active");
				$("[href='#{{_form}}Group']").show().addClass("active");
			} else {
				$("[href='#{{_form}}Group']").hide().removeClass("active");
				$("#{{_form}}Descr").addClass("show active");
				$("[href='#{{_form}}Descr']").show().addClass("active");
			}
		}
	});

	$("#{{_form}}_{{_mode}} [name=isgroup]").trigger("change");
  */
</script>
