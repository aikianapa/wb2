<div class="bg-blue text-center text-white p-3 mb-4">
  <i class="pb-3 fa fa-unlock-alt fa-4x text-white"></i>
  <p class="mb-0 login-block">{{_lang.recovery}}</p>
  <p class="mb-0 recovery-block d-none">{{_lang.change}}</p>
</div>

<form class="p-2 mb-0" method="post" action="/signin/recover">
<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-user"></i></div>
        <input class="form-control" placeholder="{{_lang.email}}" name="_email" type="email">
      </div>
      <small>{{_lang.emailentry}}</small>
</div>
<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-lock"></i></div>
        <input class="form-control" minlength="3" placeholder="{{_lang.newpass}}" autocomplete="off" required name="_pwd1" type="password">
      </div>
      <small>{{_lang.change_text1}}</small>
</div>

<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-unlock-alt"></i></div>
        <input class="form-control" minlength="3" placeholder="{{_lang.checkpass}}" autocomplete="off" required name="_pwd2" type="password">
      </div>
      <small>{{_lang.change_text2}}</small>
</div>

<div class="form-group">
  <button value="password" name="recovery" disabled class="btn btn-dark btn-block mt-1">{{_lang.update}} &nbsp; <i class="fa fa-gears"></i></button>
  <div class="form-group mt-2" data-wb-where='"{{_sett.ulogin}}"="on"'>
    <meta data-wb="role=module&name=ulogin">
  </div>
</div>
</form>
  <div class="alert text-danger text-center recover-wrong d-none">
    {{_LANG[change_text4]}}
  </div>
<div class="alert text-center">{{_lang.already}} <a href="#signin" class="tab-link">{{_lang.signin}}</a></div>
