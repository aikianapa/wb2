<div class="col-12 alert alert-warning" style="display:none;" id="{{_form}}_{{_mode}}_changePswd">
  <h5>{{_lang.changepswd}}</h5>
  <div class="form-group row">
    <div class="col-sm-5 mt-1">
      <div class="input-group">
        <span class="input-group-prepend"><i class="input-group-text fa fa-lock"></i></span>
        <input type="password" class="form-control" data-name="newpassword" minlength="3" placeholder="{{_lang.newpass}}" required>
      </div>
    </div>
    <div class="col-sm-5 mt-1">
      <div class="input-group">
        <span class="input-group-prepend"><i class="input-group-text fa fa-unlock-alt"></i></span>
        <input type="password" class="form-control" data-name="newpassword_check" minlength="3"
          placeholder="{{_lang.checkpass}}" required>
      </div>
    </div>
    <a href="#" class="col-sm-2 mt-1 btn btn-success disabled"><i class="fa fa-key"></i> {{_lang.change}}</a>
    <input type="hidden">
  </div>
</div>
    <script>
        function {{_form}}_{{_mode}}_changePswd() {
              var parent = $("#{{_form}}_{{_mode}}");
              var chbox = $("#{{_form}}_{{_mode}}_changePswd");
              var input = $("#{{_form}}_{{_mode}}_changePswd input[data-name]");
              $(input).on("keyup",function(){ check(); });
              $(chbox).find(".btn-success").on("click",function(){
                  $(input).val("");
                  $(chbox).hide("fade");
              });

              function check() {
                var pwd = trim($(chbox).find("[data-name=newpassword]").val());
                var chk = trim($(chbox).find("[data-name=newpassword_check]").val());
                if (chk > "" && pwd == chk) {
                    $(chbox).find("input[type=hidden]").attr("name","newpwd").val(pwd);
                    $(chbox).find(".btn-success").removeClass("disabled");
                } else {
                    $(chbox).find("input[type=hidden]").removeAttr("name","newpwd").val(undefined);
                    $(chbox).find(".btn-success").addClass("disabled");
                }
              }


        }
        {{_form}}_{{_mode}}_changePswd();
/*

        var form=$("#{{_form}}_{{_mode}}_pswdForm");
        if ($(form).attr("data-wb-prefix")!==undefined) prefix=$(form).attr("data-wb-prefix");
        if ($(form).attr("data-wb-suffix")!==undefined) suffix=$(form).attr("data-wb-suffix");
        $(modal).find(".btn-primary").off("click");
        $(modal).find(".btn-primary").on("click",function(){
                if (wb_check_required(form)) {
                   $(parent).find("input[name=password]").val(md5( prefix + $(form).find("input[name=newpassword]").val() + suffix ));
                    $(form).find("input").val("");
                    $(modal).modal('hide');
                }
        });
        */
    </script>
</div>

<script type="text/locale">
[en]
changepswd           = "Change password"
newpass         = "New password"
checkpass       = "New password (check)"
change          = "Change"
cancel          = "Cancel"
[ru]
changepswd           = "Изменение пароля"
newpass         = "Новый пароль"
checkpass       = "Новый пароль (повторите)"
change          = "Изменить"
cancel          = "Отмена"
</script>
