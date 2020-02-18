<?php
$app->routerAdd("/signin","/controller:module/mode:login/item:signin");
$app->routerAdd("/signup","/controller:module/mode:login/item:signup");
$app->routerAdd("/signup/(:any)","/controller:module/mode:login/item:signup/action:$1");
$app->routerAdd("/signout","/controller:module/mode:login/item:signout");
$app->routerAdd("/signin/recover","/controller:module/mode:login/item:recover");

function login__init(&$app) {
  $item = $app->vars->get("_route.item");
  if ($item !== "signin") {
      $call="login__{$item}";
	    if (is_callable($call)) {$out=@$call($app);}
	    return $out;
	} else {
      return login__signin($app);
  }
}

function login__signin(&$app) {
    $out = $app->fromFile(__DIR__."/login_ui.php", true);
    $out->data = $app->vars("_post");
    $out->data["_dir_"] = $out->path;
    $out->fetch();
    if (count($app->vars("_post"))) {
        $user = modLoginCheckUser($app->vars("_post.l"),$app->vars("_post.p"));
        if ($user) {
            $user = modLoginSuccess($app,$user);
            header('Location: '.$user->group->login_url);
        } else {
            $out->find("#signin .signin-wrong")->removeClass("d-none");
        }
    }

    return $out;
}

function login__signup(&$app) {

    $out = $app->fromFile(__DIR__."/login_ui.php", true);
    $out->data = $app->vars("_post");
    $out->data["_dir_"] = $out->path;
    $out->fetch();
    if (count($app->vars("_post"))) {
        $user = modLoginCheckUser($app->vars("_post.email"));
        if ($user) {
            $out->find("#signup .signup-wrong")->removeClass("d-none");
            if ($user->active == "on") $out->find("#signup .signup-wrong .signup-wrong-ia")->remove();
        } else {
          $app->vars("_post.password",wbPasswordMake($app->vars("_post.password")));
          $user=array(
               "id"               => wbNewId()
              ,"active"           => ""
              ,"role"             => "user"
          );
          $user = $app->postToArray($user);
          $app->itemSave("users", $user);
          header('Location: /signin');
        }
    }
    $out->find("#signin")->removeClass("show active");
    $out->find("#signup")->addClass("show active");
    $out->find("#signin-tab")->removeClass("active");
    $out->find("#signup-tab")->addClass("active");
    return $out;
}

function login__signout(&$app) {
  $user = wbArrayToObj($app->vars("_env.user"));
  $group = wbArrayToObj($app->itemRead("users",$user->role));
  $app->vars->set("_sess.user",null);
  $app->vars->set("_env.user",null);
  setcookie("user", "", time()-3600, "/");

  if ($group->logout_url > "") {
      header('Location: '.$group->logout_url);
  } else {
      header('Location: /');
  }
  die;
}

function login__recover(&$app) {
    $out = $app->fromFile(__DIR__."/login_ui.php", true);
    $out->data = $app->vars("_post");
    $out->data["_dir_"] = $out->path;
    $out->fetch();
    $out->find("#signin")->removeClass("show active");
    $out->find("#recovery")->addClass("show active");
    $out->find("#signin-tab")->removeClass("active");
    $out->find("#recovery-tab")->addClass("active");
    return $out;
}


function modLoginSuccess(&$app,$user) {
    if ($user->avatar > "") {
        if ($user->avatar->length) $user->avatar = $user->avatar[0];
        $user["avatar"]="/uploads/users/{$user->id}/{$user->avatar->img}";
    } else {
        $user->avatar = "/engine/tpl/img/person.svg";
    }
    if ($user->group->logout_url == "") $user->group->logout_url = "/";
    if ($user->group->login_url == "") $user->group->login_url = "/";
    unset($user->password);
    $app->vars("_sess.user",wbObjToArray($user));
    $app->vars("_env.user",wbObjToArray($user));
    setcookie("user",$user->id,time()+3600);
    $app->user = $user;
    return $user;
}

function modLoginCheckUser($login=null,$pass=null) {
      $fld = "id";
      if (is_email($login)) $fld = "email";
      $users = wbItemList("users", $fld . ' = "'.$login.'" AND isgroup != "on"');
      if (!count($users)) return false;
      $user = wbArrayToObj(array_shift($users));
      $group = wbArrayToObj(wbItemRead("users",$user->role));
      $user->group = $group;
      if ($pass == null) return $user;
      if ($group->active == "on" AND wbPasswordCheck($pass,$user->password)) return $user;
      return false;
}


function __engineRecoveryPassword(&$app)
{
    $out=$app->fromFile(__DIR__."/login_ui.php");
    $out->fetch();
    if (isset($_POST["l"]) and $_POST["l"]>"") {
        if (strpos($_POST["l"], "@")) {
            $users=wbItemList("users", 'email="'.$_POST["l"].'"');
            foreach ($users as $key => $item) {
                $_POST["l"]=$item["id"];
                break;
            }
        }
        if ($user=wbItemRead("users", $_POST["l"])) {
            if (isset($user["lang"]) and $user["lang"]>"") {
                $_SESSION["lang"]=$_ENV["lang"]=$user["lang"];
            }
            $user["pwdtoken"]=wbNewId();
            wbItemSave("users", $user, true);
            $letter=$out->find(".recovery-letter", 0);
            $link=$_ENV["route"]["hostp"]."/login/recovery/".base64_encode($user["password"].";".$user["email"].";".$user["pwdtoken"]);
            $letter->wbSetData(["link"=>$link]);
            $subject=$out->find(".signbox-header .recovery-block")->text();
            $res=wbMail($_ENV["settings"]["email"].";".$_ENV["settings"]["header"], $user["email"], $subject, $letter->outerHtml());
            $out->find(".recovery-block")->removeClass("d-none");
            $out->find('.main-block')->addClass('d-none');
            $out->find('.recovery-password')->addClass('d-none');
            $out->find('.login-block')->addClass('d-none');
            $out->wbSetData(["email"=>$user["email"],"site"=>$_ENV["settings"]["header"]]);
            if ($res) {
                $out->find(".recovery-info")->removeClass("d-none");
                echo $out;
            } else {
                $out->find(".recovery-wrong")->removeClass("d-none");
                echo $out;
            }
        } else {
            header('Location: /login');
        }
    }
    die;
}
