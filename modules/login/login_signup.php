<div class="bg-blue text-center text-white p-3 mb-4">
  <i class="pb-3 fa fa-user fa-4x text-white"></i>
  <p class="mb-0 login-block">{{_lang.signup}}</p>
</div>


<form method="post" class="p-2 mb-0" action="/signup/submit">
<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-user"></i></div>
        <input class="form-control" placeholder="{{_lang.firstname}}" name="first_name" type="text" required>
      </div>
      <small>{{_lang.firstname}}</small>
</div>

<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-user"></i></div>
        <input class="form-control" placeholder="{{_lang.lastname}}" name="last_name" type="text" required>
      </div>
      <small>{{_lang.lastname}}</small>
</div>

<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-phone"></i></div>
        <input class="form-control" placeholder="{{_lang.phone}}" name="phone" type="phone" required>
      </div>
      <small>{{_lang.phoneentry}}</small>
</div>

<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-envelope"></i></div>
        <input class="form-control" placeholder="{{_lang.email}}" name="email" type="email" required>
      </div>
      <small>{{_lang.emailentry}}</small>
</div>
<div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend"><i class="input-group-text fa fa-lock"></i></div>
        <input class="form-control" minlength="3" placeholder="{{_lang.password}}" autocomplete="off" required name="password" type="password">
      </div>
      <small>{{_lang.password}}</small>
</div>
<div class="form-group">
  <button value="signup" name="signup" class="btn btn-dark btn-block mg-t-10">{{_lang.signup}} &nbsp; <i class="fa fa-gears"></i></button>
</div>
</form>
<div class="alert text-danger text-center signup-wrong d-none">
  {{_lang.change_text5}}
  <span class="signup-wrong-ia">{{_lang.change_text6}}</span>
</div>

<div class="alert text-center">{{_lang.already}} <a href="#signin" class="tab-link">{{_lang.signin}}</a></div>
