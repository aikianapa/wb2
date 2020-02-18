<div class="bg-blue text-center text-white p-3 mb-4">
  <i class="pb-3 fa fa-user-circle fa-4x text-white"></i>
  <p class="mb-0 login-block">{{_lang.prompt}}</p>
</div>
<form class="p-2 mb-0" method="post" action="/signin">
<div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend"><i class="input-group-text fa fa-user"></i></div>
              <input class="form-control" placeholder="{{_lang.login}}" name="l" type="text">
            </div>
</div>

<div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend"><i class="input-group-text fa fa-lock"></i></div>
              <input class="form-control" placeholder="{{_lang.password}}" name="p" type="password">
            </div>
            <small>
                   <a href="#recovery" class="tab-link">{{_lang.forgot}}?</a>
            </small>
</div>

<div class="form-group">
  <button class="btn btn-dark btn-block mt-1">{{_lang.signin}} &nbsp; <i class="fa fa-sign-in"></i></button>
  <div class="form-group mt-2" data-wb-where='"{{_sett.ulogin}}"="on"'>
    <meta data-wb="role=module&name=ulogin">
  </div>
</div>
</form>
  <div class="alert text-danger text-center signin-wrong d-none">
    {{_lang.prompt}}
  </div>
<div class="alert text-center">{{_lang.reg}} <a href="#signup" class="tab-link">{{_lang.signup}}</a></div>
