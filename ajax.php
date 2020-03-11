<?php
header('Content-Type: charset=utf-8');
header('Content-Type: application/json');

require_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
$app = new wbApp();
if (is_file($_ENV["path_app"]."/functions.php")) {
    require_once $_ENV["path_app"]."/functions.php";
}

function ajax__onlineusers()
{
    clearstatcache();
    if ($directory_handle = opendir(session_save_path())) {
        $count = 0;
        while (false !== ($file = readdir($directory_handle))) {
            if ($file != '.' && $file != '..') {
                if (time()- filemtime(session_save_path() . '' . $file) < 3 * 60) {
                    $count++;
                }
            }
        }
        closedir($directory_handle);
    }

    header('Content-Type: application/json');
    return json_encode(["count"=>$count]);
}

function ajax__getuser()
{
    $res=false;
    if (isset($_SESSION["user"])) {
        $res=$_SESSION["user"];
    }
    return base64_encode(json_encode($res));
}

function ajax__func()
{
    $func=ltrim($_ENV["route"]["params"][0]);
    $exclude = $_ENV['stop_func'];
    foreach($exclude as $f) {
        $l = strlen($f);
        if (substr($func,0,$l) == $f) {
          echo "Error!!! PHP function <b>{$func}</b> is disabled !";
    			die;
        }
    }
    $res = false;
    header('Content-Type: text/html; charset=utf-8');
    if (is_callable($func)) eval('return call_user_func_array($func, $_POST);');
    die;
}

function ajax__settings()
{
    $sett=$_ENV["settings"];
    $mrch=wbMerchantList();
    foreach ($mrch as $k => $m) {
        unset($sett[$m["name"]]);
    }
    header('Content-Type: application/json');
    return json_encode($sett);
}

function ajax__getsysmsg()
{
    header('Content-Type: application/json');
    return json_encode(wbGetSysMsg());
}

function ajax__alive()
{
    $app = $_ENV["app"];
    if ($app->vars("_env.settings.user")) {
        $ret = true;
    } else {
        $ret = false;
    }
    $events = $app->vars("_cookie.events");
    return json_encode(["live"=>$ret,"events"=>$events]);
}

function ajax__eventtest()
{
    $app = $_ENV["app"];
    $app->addEvent("test",["sdf"=>"test234"]);
}

function ajax__gettree()
{
    foreach ($_POST as $k => $v) {
        $$k=$v;
    }
    $tree=wbTreeRead($tree);
    $tree=wbTreeFindBranch($tree["tree"], $branch, $parent, $childrens);
    return base64_encode(json_encode(wbItemToArray($tree)));
}

function ajax__gettreedict()
{
    foreach ($_POST as $k => $v) {
        $$k=$v;
    }
    $tree=wbTreeRead($tree);
    $tree=$tree["dict"];
    return base64_encode(json_encode(wbItemToArray($tree)));
}

function ajax__rmitem($form=null)
{
    if ($form==null) {
        $form=$_ENV["route"]["form"];
    }
    $aFunc="{$form}_rmitem";
    if (is_callable($aFunc)) {
        $ret=$aFunc();
    } else {
        if (isset($_SESSION["user_id"])) {
            $_ENV["DOM"]=wbGetForm("common", "remove_confirm");
            if (isset($_REQUEST["confirm"]) and $_REQUEST["confirm"]=="true") {
                $_ENV["DOM"]->find("script[data-wb-tag=success]")->remove();
            } else {
                wbItemRemove($_ENV["route"]["form"], $_ENV["route"]["item"]);
                $_ENV["DOM"]->find(".modal")->remove();
            }
            return $_ENV["DOM"];
        }
    }
}

function ajax__getlocale($type=null, $name=null)
{
    if (isset($_POST["type"])) {
        $type=$_POST["type"];
    }
    if (isset($_POST["name"])) {
        $name=$_POST["name"];
    }
    if ($type==null) {
        $type="tpl";
    }
    if ($name==null) {
        $name="default.php";
    }
    if ($type=="tpl") {
        $out=wbGetTpl($name);
    } elseif ($type=="form") {
        $arr=explode("_", $name);
        $form=$arr[0];
        unset($arr[0]);
        if ($arr[1]=="_") {
            unset($arr[1]);
        }
        $name=implode("_", $arr);
        $out=wbGetForm($form, $name);
    } elseif ($type=="file") {
        $out=wbFromFile($_ENV["path_app"].$name);
    } elseif ($type=="url") {
        $out=wbFromString(file_get_contents($name));
    }
    $locale=$out->wbGetFormLocale();
    $locale=$locale[$_SESSION["lang"]];
    return base64_encode(json_encode($locale));
}

function ajax_fetch() {
  $app = $_ENV["app"];
  $data = [];
  if ($app->vars("_post._route")) $_ENV["route"] = $app->vars("_post._route");
  if (!$app->vars("_post._tpl")) {
    $url = $app->vars("_post._route.hostp").$app->vars("_post._route.uri");
    $filter = $app->vars("_post._filter");
    $tpl = $app->fromString(wbAuthPostContents($url, ["_filter"=>$filter]) );
    if ($app->vars("_post._result")) $tpl = $tpl->find($app->vars("_post._result"));
  } else {
    $tpl = $app->fromString($app->vars("_post._tpl"));
    if ($app->vars("_post._data"))   $tpl->data = $app->vars("_post._data");
    $tpl->fetch();
  }
  $data = [];
  foreach($tpl->attributes() as $at => $val) {
      if (substr($at,0,5) == "data-" && substr($at,0,7) !== "data-wb") {
          if (substr($at,5) == "data") $val = json_decode($val);
          $data[substr($at,5)] = $val;
      }
  }
  return wb_json_encode(["result"=>$tpl->html(),"return"=>$data]);
}

function ajax__setdata()
{
    $form=$_ENV["route"]["form"];
    $item=$_ENV["route"]["item"];
    $Item=array();
    $_REQUEST=json_decode(base64_decode($_REQUEST["data"]), true);
    if (isset($_REQUEST["_base"])) {
        $_ENV['path_tpl'] = $_ENV['path_app'].$_REQUEST["_base"];
    }
    if (isset($_REQUEST["data-wb-mode"])) {
        $_REQUEST["mode"]=$_REQUEST["data-wb-mode"];
    }
    if (!isset($_REQUEST["data-wb-mode"])) {
        $_REQUEST["mode"]="list";
    }
    if ($form!=="undefined" && $item!=="undefined") {
        $Item=wbItemRead($form, $item);
    }
    if (!is_array($_REQUEST["data"])) {
        $_REQUEST["data"]=array($_REQUEST["data"]);
    }
    $Item=wbItemToArray($Item);
    if (!is_array($Item)) {
        $Item=array();
    }
    $Item=array_merge($Item, $_REQUEST["data"]);
    if (isset($Item["_form"])) {
        $_ENV["route"]["form"]=$_GET["form"]=$Item["_form"];
        $_ENV["route"]["controller"]="form";
    }
    if (isset($Item["_item"])) {
        $_ENV["route"]["item"]=$_GET["item"]=$Item["_item"];
    }
    if (isset($Item["_mode"])) {
        $_ENV["route"]["mode"]=$_GET["mode"]=$Item["_mode"];
    }
    $Item=wbCallFormFunc("BeforeShowItem", $Item, $form, $_REQUEST["mode"]);
    $Item=wbCallFormFunc("BeforeItemShow", $Item, $form, $_REQUEST["mode"]);
    $tpl=wbFromString($_REQUEST["tpl"]);
    $tpl->find(":first")->attr("item", "{{id}}");
    foreach ($tpl->find("[data-wb-role]") as $dr) {
        $dr->removeClass("wb-done");
    }
    $tpl->wbSetData($Item, true);
    $tpl->find("empty")->remove();
    $tpl->tagHideAttrs();
    return $tpl;
}

function ajax__listfiles()
{
    $result=array();
    $_GET["path"]="/".implode("/", $_ENV["route"]["params"]);
    if ($_GET["path"]=="") {
        $path=$_ENV["path_app"]."/uploads";
    } else {
        $path=$_ENV["path_app"].$_GET["path"];
    }
    $files=wbListFiles($path);
    if (is_array($files)) {
        foreach ($files as $key => $file) {
            if (is_file($path."/".$file)) {
                $result[]=$file;
            }
        }
    }
    return json_encode($result);
}

function ajax__newid()
{
    return json_encode(wbNewId());
}

function ajax__gettpl()
{
    echo wbGetTpl($_ENV["route"]["params"][0]);
    die;
}

function ajax__remove()
{
    if ($_ENV["route"]["params"][0]=="uploads") {
        $file=$_ENV["path_app"]."/".implode("/", $_ENV["route"]["params"]);
        return json_encode(wbFileRemove($file));
    }
    die;
}

function ajax__getform()
{
    $app = new wbApp();
    $out = $app->getForm($_ENV["route"]["params"][0], $_ENV["route"]["params"][1])->outerHtml();
    return json_encode(["content"=>$out]);
}

function ajax__buildfields()
{
    $res=array();
    if (isset($_POST["data"])) {
        $data=$_POST["data"];
    } else {
        $data=array();
    }
    foreach ($_POST["dict"] as $dict) {
        $dict["value"]=htmlspecialchars($dict["value"]);
        $res=wbFieldBuild($dict, $data);
        return $res;
    }
    die;
}


function ajax__treeedit()
{
    $out=wbGetForm("common", "tree_edit");
    $out->wbSetData($_POST);
    $arr=array();
    if (is_array($_POST["fields"])) {
        foreach ($_POST["fields"] as $dict) {
            if (isset($dict["name"]) and !in_array($dict["name"], $arr)) {
                $dict["value"]=htmlspecialchars($dict["value"]);
                $res=wbFieldBuild($dict, $_POST["data"]);
                $out->find(".treeData form")->append($res);
                $arr[]=$dict["name"];
                $prop=$out->find(".treeDict [data-wb-field=name][value={$dict["name"]}]")->parents(".wb-multiinput")->find(".wb-prop-fields");
                foreach ($prop->find("[data-type-allow],[data-type-disallow]") as $element) {
                    if ($element->is("[data-type-allow]")) {
                        $attr=wbArrayAttr($element->attr("data-type-allow"));
                        if (!in_array($dict["type"], $attr)) {
                            $element->remove();
                        }
                    }
                    if ($element->is("[data-type-disallow]")) {
                        $attr=wbArrayAttr($element->attr("data-type-disallow"));
                        if (in_array($dict["type"], $attr)) {
                            $element->remove();
                        }
                    }
                }
            }
        }
    }
    return $out->outerHtml();
}


function ajax__mailer()
{
    return ajax__mail();
}

function ajax__mail()
{
    $attachments=[];
    if (!isset($_POST["_subject"])) {
        $_POST["_subject"]=$_ENV['sysmsg']["mail_from_site"];
    }
    if (!isset($_POST["subject"])) {
        $_POST["subject"]=$_POST["_subject"];
    }

    if (isset($_POST["_tpl"])) {
        $out=wbGetTpl($_POST["_tpl"]);
    } elseif (isset($_POST["_form"])) {
        $out=wbGetTpl($_POST["_form"]);
    } elseif (isset($_POST["_message"])) {
        $out=wbFromString($_POST["_message"]);
        $b64img = $out->find("img[src^='data:']");
        foreach ($b64img as $b64) {
            $attachments[] = $b64->attr("src");
            $b64->remove();
        }
    } else {
        $out=wbGetTpl("mail.php");
    }
    if (!isset($_POST["email"])) {
        $_POST["email"]=$_ENV["route"]["mode"]."@".$_ENV["route"]["host"];
    }
    if (!isset($_POST["name"])) {
        $_POST["name"]="Site Mailer";
    }
    if (isset($_POST["_mailto"])) {
        $mailto=$_POST["_mailto"];
    } else {
        $mailto = $_ENV["settings"]["email"];
    }
    $Item=$_POST;
    $out->wbSetData($Item);
    $out=$out->outerHtml();
    $res=wbMail("{$_POST["email"]};{$_POST["name"]}", "{$mailto};{$_ENV["settings"]["header"]}", $_POST["subject"], $out, $attachments);
    if (!$res) {
        $result=json_encode(array("error"=>true,"msg"=>$_ENV['sysmsg']["mail_sent_error"].": ".$_ENV["error"]['wbMail']));
    } else {
        $result=json_encode(array("error"=>false,"msg"=>$_ENV['sysmsg']["mail_sent_success"]."!"));
    }
    if (isset($_POST["_callback"]) and is_callable($_POST["_callback"])) {
        return @$_POST["_callback"]($result);
    }
    return $result;
}
