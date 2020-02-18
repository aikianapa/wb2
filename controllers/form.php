<?php
function form__controller(&$app)
{
    $app->controller = "form";
    wbTrigger("func", __FUNCTION__, "before");
    if (!in_array($_ENV["route"]["mode"], array("setup_engine","edit","list")) and !in_array($_ENV["route"]["form"], $_ENV["forms"])) {
        echo form__controller__error_404();
        die;
    }
    $call=__FUNCTION__ ."__".$_ENV["route"]["mode"];
    if (is_callable($call)) {
        $dom=$call($app);
    } else {
        $aCall=$_ENV["route"]["form"]."_".$_ENV["route"]["mode"];
        $eCall=$_ENV["route"]["form"]."__".$_ENV["route"]["mode"];
        if (is_callable($aCall)) {
            $out=$aCall($app);
        } elseif (is_callable($eCall)) {
            $out=$eCall($app);
        } elseif (form__controller__common__controller($app)) {
            $out = $app->dom;
        } else {
            echo __FUNCTION__ .": {$_ENV['sysmsg']['err_func_lost']} ".$call."()";
            die;
        }
        if (!is_object($out)) $dom=$app->fromString($out);
        $dom=$out;
    }
    if (isset($_COOKIE["wbcookies"]) and $_COOKIE["wbcookies"]==true and isset($_SESSION["user_id"]) and isset($_SESSION["user"]) and (!isset($_SESSION["user"]["wbcookies"]) or $_SESSION["user"]["wbcookies"] == "" or $_SESSION["user"]["wbcookies"] == false)) {
        $user=$app->ItemRead("users", $_SESSION["user_id"]);
        $user["wbcookies"] = $_SESSION["user"]["wbcookies"] = $_COOKIE["wbcookies"];
        $app->ItemSave("users", $user);
    }
    wbTrigger("func", __FUNCTION__, "after");
    return $dom;
}

function form__controller__common__controller(&$app)
{
    $mode=$_ENV["route"]["mode"];
    $form=$_ENV["route"]["form"];
    $item=$_ENV["route"]["item"];
    $tpl = null;
    $app = new wbApp();
    if (isset($_ENV["route"]["tpl"]) and $_ENV["route"]["tpl"]>"") {
        $tpl=$_ENV["route"]["tpl"];
    }
    $aCall=$form."_".$mode;
    $eCall=$form."__".$mode;
    $out=false;
    if ($tpl!==null and $tpl>"") {
        $out=$app->getTpl($tpl);
    }
    if ($out && is_object($out)) {
        $app->dom=$out;
        return true;
    }
    if (is_callable($aCall)) {
        $out=$aCall($item);
    } elseif (is_callable($eCall)) {
        $out=$eCall($item);
    }
    if ($out==false) {
        $out=$app->getForm($form, $mode);
        if (!$out) {
            $out=$app->getForm($form, $mode.="_".$item);
        }
        if (!is_object($out)) {
            return false;
        }
        $out->fetch();
        $app->dom = $out;
        return true;
    }
    if (!is_object($out)) {
        $out=$app->fromString($out);
    }
    $dom=$out;
    if (isset($_REQUEST["confirm"]) and $_REQUEST["confirm"]=="true") {
        $dom->find("script[data-wb-tag=success]")->remove();
    } else {
        wbItemRemove($_ENV["route"]["form"], $_ENV["route"]["item"]);
        $dom->find(".modal")->remove();
    }
    return true;
}

function form__controller__show(&$app)
{
    $form = $app->vars->get("_route.form");
    $item = $app->vars->get("_route.item");
    $tpl = null;
    if (isset($_ENV["route"]["tpl"]) and $_ENV["route"]["tpl"]>"") {
        $tpl = $app->vars->get("_route.tpl");
    }
    $mode="show";
    $Item = $app->ItemRead($form, $item);
    if (!$Item) {
        $fid=wbFurlGet($form, $item);
        if ($fid) {
            $item=$fid;
        } elseif (!$tpl) {
            return form__controller__error_404();
        }
        $Item = $app->wbItemRead($form, $item);
    }
    $Item = wbCallFormFunc("BeforeItemShow", $Item, $form, $mode);
    $aCall=$form."_".$mode;
    $eCall=$form."__".$mode;
    if (is_callable($aCall)) {
        $out=$aCall($Item);
    } elseif (is_callable($eCall)) {
        $out=$eCall($Item);
    }
    if (!in_array($form, $_ENV["forms"])
                or (isset($_ENV["route"]["item"])
                and $Item==false)
                or (isset($Item["active"])
                and $Item["active"]!=="on")) {
        echo form__controller__error_404();
        die;
    } else {
        if (isset($out)) {
            if (is_string($out)) {
                $dom=$app->fromString($out);
            } elseif (is_object($out)) {
                $dom = $out;
            }
        } else {
            $out=$app->getForm($form, $mode);
            if (is_object($out)) {
                $dom = $out;
            } else {
                if ($tpl) {
                    $dom = $app->getTpl($tpl);
                } else {
                    if (!isset($Item["template"]) or $Item["template"]=="") {
                        //$Item["template"]="default.php";
                        $dom = $app->fromString("<html>{{text}}</html>");
                    } else {
                        $dom = $app->getTpl($Item["template"]);
                    }
                }
                if (!is_object($dom)) $dom = $app->fromString($dom);
            }
        }
        if (is_object($dom)) {
            //$dom->wbBaseHref();
            $dom->fetch($Item);
            $dom->find("script[type=text/locale]")->remove();
        }
    }
    return $dom;
}

function form__controller__remove(&$app)
{
    //	if (isset($_SESSION["user_id"])) {
    $dom=$app->getForm("common", "remove_confirm");
    $dom->fetch();
    return $dom;
    //	}
}

function form__controller__rename(&$app)
{
    //if (isset($_SESSION["user_id"]) AND in_array($_SESSION["user_role"],["admin","moder"])) {
    $dom=$app->getForm("common", "rename_confirm");
    if (isset($_REQUEST["confirm"]) and $_REQUEST["confirm"]=="true") {
        $dom->find("script[data-wb-tag=success]")->remove();
    } else {
        //wbItemRename($_ENV["route"]["form"],$_ENV["route"]["item"]);
        $dom->find(".modal")->remove();
    }
    $dom->fetch();
    return $dom;
    //}
    die;
}


function form__controller__error_404($id=null)
{
    return wbPage404();
}

function form__controller__error_301()
{
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /");
    die;
}

function form__controller__list(&$app)
{
    $form=$app->vars->get("_route.form");
    $mode=$app->vars->get("_route.mode");
    $aCall=$form."_".$mode;
    $eCall=$form."__".$mode;
    if (is_callable($aCall)) {
        $dom=$aCall($app);
    } elseif (is_callable($eCall)) {
        $dom=$eCall($app);
    }
    if (!isset($dom)) {
        $dom=$app->getForm($form, $mode);
    } else {
        if (is_string($dom)) {
            $dom=$app->fromString($out);
        }
    }
    $dom->fetch();
    return $dom;
}

function form__controller__edit(&$app)
{
    $form=$app->vars->get("_route.form");
    $item=$app->vars->get("_route.item");
    $mode=$app->vars->get("_route.mode");
    if ($item=="" or $item=="_new") {
        $item=$_GET["item"]=$_ENV["route"]["item"]=wbNewId();
        $_ENV["route"]["_new"]=true;
    }
    $aCall=$form."_".$mode;
    $eCall=$form."__".$mode;
    if (is_callable($aCall)) {
        $out=$aCall($app);
    } elseif (is_callable($eCall)) {
        $out=$eCall($app);
    }
    if (!isset($out)) {
        $dom=$app->getForm($form, $mode);
        $dom->data = wbItemRead($form, $item);
        if (!$dom->data) {
            $dom->data=array("id"=>$_ENV["route"]["item"]);
        }
    } else {
        if (is_string($out)) {
            $out=$app->fromString($out);
        }
        $dom = $out;
    }
    $dom->fetch();
    return $dom;
}

function form__controller__default_mode()
{
    if (!is_dir($_ENV["path_app"]."/forms")) {
        form__controller__setup_engine();
    } else {
        die("Установка выполнена");
    }
}

function form__controller__select2()
{
    $form="";
    $where="";
    $tpl="";
    $val="";
    $form=$_ENV["route"]["form"];
    $custom="form_controller_select2_{$form}";
    if (is_callable($custom)) {
        return $custom();
    } else {
        $result=array();
        $single=false;
        $where="";
        if (!isset($_POST["id"]) and !isset($_POST["value"]) and !isset($_POST["where"])) {
            return;
        }
        if (isset($_POST["where"]) and $_POST["where"]>"") {
            $where=$_POST["where"];
        }
        if (isset($_POST["tpl"]) and $_POST["tpl"]>"") {
            $tpl=$_POST["tpl"];
        }
        if (isset($_POST["value"]) and $_POST["value"]>"") {
            $val=$_POST["value"];
        }
        if (isset($_POST["id"]) and $_POST["id"]>"") {
            $id=$_POST["id"];
            $single=true;
        }
        $cacheId=md5("select2".$form.$where);
        if (isset($_ENV["cache"][$cacheId])) {
            $list=$_ENV["cache"][$cacheId];
        } else {
            $list=wbItemList($form, $where);
            $_ENV["cache"][$cacheId]=$list;
        }
        foreach ($list as $key => $r) {
            $data=wbSetValuesStr($tpl, $r);
            $res=preg_match("/{$val}/ui", $data);
            if ($res) {
                $obj=$app->fromString($data);
                $oid=$obj->find("option")->attr("value");
                $otx=$obj->text();
                if ($single) {
                    if ($id==$oid) {
                        $result[]=array("id"=>$oid,"text"=>$otx,"item"=>$r);
                        break;
                    }
                } else {
                    $result[]=array("id"=>$oid,"text"=>$otx,"item"=>$r);
                }
            } else {
                unset($list[$key]);
            }
        }
        if ($single and count($result)>0) {
            $result=$result[0];
        }
        header('Content-Type: application/json');
        echo wbJsonEncode($result);
        die;
    }
}

function form__controller__setup_engine()
{
    $out=wbGetTpl("/engine/tpl/setup.htm", true);
    if (isset($_GET["params"]["lang"])) {
        $_SESSION["lang"]=$_ENV["lang"]=$_GET["params"]["lang"];
    } else {
        unset($_SESSION["lang"],$_ENV["lang"]);
    }
    if (is_dir($_ENV["path_tpl"])) {
        $out->wbSetFormLocale();
        $out->find("#setup")->remove();
        return $out;
    } elseif (isset($_POST["setup"]) and $_POST["setup"]=="done" and !is_dir($_ENV["path_app"]."/form")) {
        unset($dom,$_ENV["errors"]);
        wbRecurseCopy($_ENV["path_engine"]."/_setup/", $_ENV["path_app"]);
        wbTableCreate("pages");
        wbTableCreate("users");
        wbTableCreate("todo");
        wbTableCreate("news");
        wbTableCreate("orders");
        $user=array("id"=>$_POST["login"],"password"=>md5($_POST["password"]),"email"=>$_POST["email"],"role"=>"admin","login_url"=>"/admin/","logout_url"=>"/login/","isgroup"=>"on","active"=>"on","super"=>"on","lang"=>$_SESSION["lang"]);
        $settings=array("id"=>"settings","header"=>$_POST["header"],"email"=>$_POST["email"],"lang"=>$_SESSION["lang"]);
        wbItemSave("users", $user);
        wbItemSave("admin", $settings);
        header('Location: '.'/');
        die;
    }
    $out->find("#error")->remove();
    $out->fetch();
    return $out;
    die;
}
