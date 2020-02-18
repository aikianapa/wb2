<!DOCTYPE html>
<html lang="en">
  <base href="{{_env.modules.login.dir}}/">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Meta -->
    <meta name="description" content="Login">
    <title>{{_LANG[title]}}</title>

    <!-- vendor css -->
    <meta data-wb="role=snippet">
    <link href="/engine/lib/fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/engine/css/animations.css" rel="stylesheet">
    <!-- Katniss CSS -->
  </head>

  <body style="overflow:hidden;" id="mod_login">

<div class="bkg animation-pulseSlow"></div>
<div class="row">
    <div class="d-xs-none col-sm-6 col-lg-8">
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="m-1 mr-sm-4">

<!-- change nav-tabs to nav-pills if you want to display button -->
      <ul class="nav nav-tabs d-none" role="tablist">
          <li class="nav-item">
              <a class="nav-link active" id="signin-tab" data-toggle="tab" href="#signin" role="tab" aria-controls="signin" aria-selected="true">{{_lang.signin}}</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" id="signup-tab" data-toggle="tab" href="#signup" role="tab" aria-controls="signup" aria-selected="false">{{_lang.signup}}</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" id="recover-tab" data-toggle="tab" href="#recovery" role="tab" aria-controls="recovery" aria-selected="false">{{_lang.recovery}}</a>
          </li>
      </ul>
      <div class="tab-content" >
          <div class="tab-pane fade show active pb-1" id="signin" role="tabpanel" data-wb="role=include&file={{_dir_}}/login_signin.php">
          </div>


          <div class="tab-pane fade pb-1" id="signup" role="tabpanel" data-wb="role=include&file={{_dir_}}/login_signup.php">
          </div>



          <div class="tab-pane fade pb-1" id="recovery" role="tabpanel" data-wb="role=include&file={{_dir_}}/login_recover.php">
          </div>
      </div>

</div>
    </div>
</div>



    <script type="wbapp">


      $("form").on("submit",function(){
          if ($(this).checkRequired() == false) return false;
      });




      $(".tab-link").click(function(){
        $(".nav-tabs").find("a[href='"+$(this).attr("href")+"']").trigger("click");
        return false;
			});


			$("input[type=password]").on("keyup",function(){
				if ($(this).is("[name=_pwd1]") || $(this).is("[name=_pwd2]")) {
					if ($(this).attr("minlength") > $(this).val().length) {
						$(this).parents(".input-group").find(".input-group-addon .fa").addClass("tx-danger");
					} else {
						$(this).parents(".input-group").find(".input-group-addon .fa").removeClass("tx-danger");
					}
					if ($("[name=_pwd1]").val() == $("[name=_pwd2]").val() && $("[name=_pwd1]").val().length >= $(this).attr("minlength")) {
						$(this).parents(".recovery-password").find(".input-group-addon .fa").addClass("tx-success");
						$("button[name=recovery]").removeAttr("disabled");
					} else {
						$("button[name=recovery]").attr("disabled",true);
						$(this).parents(".recovery-password").find(".input-group-addon .fa").removeClass("tx-success");
					}

				}
			});
		</script>
  </body>
</html>

<script type="text/locale">
[en]
        login           = "Login"
        password        = "Password"
        signup          = "Sign up"
        signin          = "Sign in"
        already         = "Already registred?"
        prompt          = "Please, fill in your login and password to sign in"
        reg             = "Not registred?"
        title           = "Sign in to the system"
        recovery			= "Send recovery link"
        forgot			= "Forgot password"
        letter			= "<p>Hello!</p><p>To recovery your password in the {{_sett.header}} site, please go to recovery link: <a href='{{link}}'>RECOVERY PASSWORD</a>.<br>If your don't request recovery, do nothing.</p>"
        update			= "Update password"
        change          = "Change password"
    		newpass         = "New password"
    		checkpass       = "New password (check)"
    		change_text1	= "Enter new password (min length: 3 symbols)"
    		change_text2	= "Re-Enter new password (min length: 3 symbols)"
    		change_text3	= "Check your email box {{email}} and ckick Recovery link in new message from {{site}} site."
    		change_text4	= "Something is wrong, please repeat the procedure"
        change_text5	= "User {{email}}  already registred."
        change_text6	= "Require activation."
    		success			= "Password successfully changed"
        email   = "Email"
        emailentry = "Enter your Email"
        firstname = "First Name"
        lastname = "Last Name"
[ru]
        login           = "Логин"
        password        = "Пароль"
        signup          = "Создать аккаунт"
        signin          = "Войти"
        already         = "Уже зарегистрированы?"
        prompt          = "Пожалуйста, введите ваш логин и пароль"
        reg             = "Не зарегестрированы?"
        title           = "Вход в систему"
        recovery			= "Восстановить пароль"
        forgot			= "Забыли пароль"
        letter			= "<p>Приветствуем!</p><p>Для восстановления пароля на сайте {{_sett.header}}, перейдите по сыылке: <a href='{{link}}'>ВОССТАНОВИТЬ ПАРОЛЬ</a>.<br>Если вы не запрашивали восстановление пароля, ничего не делайте.</p>"
        update			= "Изменить пароль"
        change           = "Изменение пароля"
		newpass         = "Новый пароль"
		checkpass       = "Новый пароль (повторите)"
		change_text1	= "Введите новый пароль (минимум: 3 символа)"
		change_text2	= "Повторите новый пароль (минимум: 3 символа)"
		change_text3	= "Проверьте свой почтовый ящик {{email}} нажмите ссылку восстановления с сайта {{site}}"
		change_text4	= "Что-то не так, пожалуйста, повторите процедуру заново"
    change_text5	= "Пользователь {{email}} уже зарегистрирован."
    change_text6	= "Требует активации."
		success			= "Пароль успешно изменён"
    email = "Эл. почта"
    emailentry = "Введите ваш Email"
    firstname = "Имя"
    lastname = "Фамилия"
</script>
<meta data-wb="role=variable" var="bkg" data-wb-if='"{{_sett.modules.login.background.0.img}}">""' value='/uploads/_modules/login/{{_sett.modules.login.background.0.img}}' else='/engine/modules/login/login.jpg'>
<style data-wb="role=module&load=less">
	.bkg {
		position:absolute;
		overflow: hidden;
		background: url({{_var.bkg}}) 50% 50% no-repeat;
		background-size: cover;
		filter: blur({{_sett.modules.login.blur}}px);
		width:105vw;
		height:105vh;
	}

  .tab-content {
      height: 100vh;
      width: 100vw;
      vertical-align: middle;
      display: table-cell;
  }

  .tab-pane {
    background-color: #ffffff60;
    .bg-blue {
        opacity: 0.6;
    }
  }

</style>
