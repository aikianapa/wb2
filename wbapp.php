<?php
use Nahid\JsonQ\Jsonq;
use Rct567\DomQuery\DomQuery;
use Adbar\Dot;

class wbDom extends DomQuery
{

    /* =======================================================
                        WEB BASIC EXTENSIONS
    ======================================================= */

    public function fetch($Item=null)
    {
        if ($this->fetchStrict()) return $this;
        if ($Item == null and isset($this->data)) {
            $Item = $this->data;
        } elseif ($Item !== null) {
            $this->data = $Item;
        }
        $this->fetchParams();
        if ($this->params->allow === false) {
          $this->remove(); return $this;
        }
        $this->setAttributes();
        $wbtags = $this->children();
        if ($this->where()) {
                foreach ($wbtags as &$wb) {
                    $wb->locale = $this->locale;
                    $wb->parent = $this;
                    $result = $wb->fetchNode();
                    if ($result) $wb->replace($result);
                }
                $this->find(".wb-out-inner")->each(function ($hide) {
                    $hide->after($hide->html())->remove();
                    $hide->remove();
                });
                $this->find("script[type='text/locale']")->remove();
        } else {
            $this->remove();
        }
        return $this;
    }

    public function fetchNode()
    {
        $this->app = $this->parent->app;
        $this->data = $this->parent->data;
        if ($this->fetchStrict()) return $this;
        $this->fetchParams();
        if ($this->params->allow === false) {
          $this->remove();
          return $this;
        }
        if ($this->where()) {
            if ($this->params->tpl == "true") $this->addTpl();
            if ($this->hasRole()) {
                $func="tag".ucfirst($this->role);
                $func($this);
            }
            $this->fetch();
            $this->setValues();
            if (count($this->attributes())) $this->fetchTargets();

        } else {
            $this->remove();
        }
        if ($this->params->hide == "true") {
            $this->after($this->html());
            $this->remove();
        }
    }


    public function fetchTargets() {
      $attrs = array_keys($this->attributes());
      $tags = ["append","prepend","after","before","html","text","remove"];
      $tags = array_intersect($attrs,$tags);
      if (!count($tags)) return;
      foreach($tags as $i => $t) {
          $find = $this->closest(":root")->find($this->attr($t));
          if ($find->length) {
              $this->removeAttr($t);
              if ($t == "remove") {
                  $find->$t();
              } else {
                  $find->$t($this);
              }
              $this->remove();
          }
      }
    }

    public function fetchParams()
    {
        if (isset($this->params)) return $this;
        $this->setAttributes();
        $wbd = $this->attr("data-wb");
        if (substr($wbd, 0, 1) == "{" and substr($wbd, -1, 1) == "}") {
            $params=json_decode($wbd, true);
        } else {
            parse_str($wbd, $params);
        }
        $attributes = $this->attributes;
        if ($attributes->length) {
            foreach ($attributes as $attr) {
                $tmp=$attr->name;
                if (strpos(" ".$tmp, "data-wb-")) {
                    $tmp=str_replace("data-wb-", "", $tmp);
                    $params[$tmp]=$attr->value;
                }
            }
        }
        $this->params=(object)$params;

        if (isset($this->params->role)) {
            $this->role = $this->params->role;
        } else {
            $this->role = false;
        }

        if ($this->params->allow > "") {
            $allow = wbArrayAttr($this->params->allow);
            if ($allow && !in_array($this->app->vars("_env.user.role"),$allow)) {
                $this->params->allow = false;
            } else {
                $this->params->allow = true;
            }
        }
        if ($this->params->disallow > "") {
            $disallow = wbArrayAttr($this->params->disallow);
            if ($disallow && !in_array($this->app->vars("_env.user.role"),$disallow)) {
                $this->params->allow = true;
            } else {
                $this->params->allow = false;
            }
        }
        if ($this->params->disabled > "") {
            $disabled = wbArrayAttr($this->params->disabled);
            if ($disabled && in_array($this->app->vars("_env.user.role"),$disabled)) {
                $this->attr("disabled",true);
            }
        }
        if ($this->params->enabled > "") {
            $enabled = wbArrayAttr($this->params->enabled);
            if ($enabled && !in_array($this->app->vars("_env.user.role"),$enabled)) {
                $this->attr("disabled",true);
            }
        }
        return $this;
    }

    public function fetchStrict()
    {
        if ($this->hasClass("wb-done")
            or $this->hasClass("contenteditable")
            or in_array($this->tag(), ["textarea","template"])
          ) {
            return true;
        }
        return false;
    }


    public function dataProcessor() {
        $data=array();
        $grps=array();
        $total=array();
        $grand=array();
        if ($this->params->group) $groups = wbAttrToArray($this->params->group);
        if ($this->params->total) $totals = wbAttrToArray($this->params->total);
        if ($this->params->supress == "true") {
            $sup=true;
        } else {
            $sup=false;
        }
        $lines=$this->find("tr");
        $index=0;
        foreach ($lines as $tr) {
            unset($grp_id,$grpidx);
            $fields=$tr->find("td[data-wb-fld]:not([data-wb-eval])");
            $Item=array();
            foreach($fields as $field) {
                $Item[$field->attr("data-wb-fld")]=$field->text();
            }
            $fields=$tr->find("[data-wb-eval]");
            foreach($fields as $field) {
                $evalStr=wbSetValuesStr($field,$Item);
                eval ("\$tmp = ".$field->text().";");
                $field->text($tmp);
            }
            foreach($groups as $group) {
                $grp_text=$tr->find("[data-wb-fld='{$group}']")->text();
                if (!isset($grp_id)) {
                    $grp_id=$grp_text;
                }
                else {
                    $grp_id.="|".$grp_text;
                }
                if (!isset($grpidx)) {
                    $grpidx=$group;
                }
                else {
                    $grpidx.="|".$group;
                }
                if (!isset($grps[$grp_id])) {
                    $grps[$grp_id]=array("data"=>array(),"total"=>array());
                }
                $grps[$grp_id]["grpidx"]=$grpidx;
                $grps[$grp_id]["data"][]=$index;
                if (isset($totals)) {
                    foreach($totals as $totfld) {
                        $totval=$tr->find("[data-wb-fld='{$totfld}']")->text()*1;
                        if (!isset($grps[$grp_id]["total"][$totfld])) {
                            $grps[$grp_id]["total"][$totfld]=0;
                        }
                        $grps[$grp_id]["total"][$totfld]+=$totval;
                        if (!isset($grand[$totfld])) {
                            $grand[$totfld]=0;
                        }
                        if ($group==$groups[0]) $grand[$totfld]+=$totval;
                    }
                }
            }
            $index++;
        }
        ksort($grps);
        $grps=array_reverse($grps,true);
        $tbody = $this->app->fromString("<tbody type='result'></tbody>");
        $ready=array();
        foreach($grps as $grpid => $grp) {
            $inner="";
            $count=count($grp["data"])-1;
            foreach($grp["data"] as $key => $idx) {
                if (!in_array($idx,$ready)) {
                    $tpl = $this->find("tr:eq({$idx})")->outerHtml();
                    if ($sup==false) $tbody->append($tpl);
                }
                if ($key==$count AND count($grp["total"])>0) {
                    // выводим тоталы группы
                    $trtot=wbFromString("<tr>".$this->find("tr:eq({$idx})")->html()."</tr>");
                    $totchk=array();
                    foreach($grp["total"] as $fld => $total) {
                        $trtot->find("td[data-wb-fld={$fld}]")->html($total);
                        $totchk[]=$fld;
                    }
                    $trtot->find("tr")->attr("data-wb-group",$grp["grpidx"]);
                    $trtot->find("tr")->attr("data-wb-group-value",$grpid);
                    $trtot->find("tr")->addClass("data-wb-total success");
                    $grpchk=explode("|",$grp["grpidx"]);
                    $tmp=$trtot->find("td:not([data-wb-fld])");
                    foreach($tmp as $temp) {
                        $temp->html("");
                    }
                    $tdflds=$trtot->find("td[data-wb-fld]");
                    foreach($tdflds as $tdfld) {
                        $data_fld=$tdfld->attr("data-wb-fld");
                        if (!in_array($data_fld,$grpchk) && !in_array($data_fld,$totchk)) {
                            $tdfld->html("");
                        }
                        if (in_array($data_fld,$grpchk)) {
                            $tdfld->addClass("data-wb-group");
                        }
                        if (in_array($data_fld,$totchk)) {
                            $tdfld->addClass("data-wb-total");
                        }
                    }
                    $inner.=$trtot->outerHtml();

                }
                $ready[]=$idx;
            }
            $tbody->append($inner);
        }
        // выводим общий итог
        if (isset($grp["total"]) && count($grp["total"])>0) {
            $grtot=wbFromString("<tr>".$this->find("tr:eq({$idx})")->html()."</tr>");
            $grtot->find("tr")->addClass("data-wb-grand-total info");
            $tmp=$grtot->find("td");
            foreach($tmp as $temp) {
                $temp->html("");
            }
            $grflds=$grtot->find("td[data-wb-fld]");
            foreach($grflds as $grfld) {
                $data_fld=$grfld->attr("data-wb-fld");
                if (in_array($data_fld,$totchk)) {
                    $grfld->html($grand[$data_fld]);
                    $grfld->addClass("data-wb-total");
                }
            }
            $tbody->append($grtot);
        }
        $this->html($tbody->innerHtml());
    }



    public function addTpl($real = true) {
      if ($this->params->tpl !== "true") return;
      if ($this->attr("data-wb-tpl") > "" AND $this->attr("data-wb-tpl") !== "true") return;
          $this->params->route = $this->app->vars("_route");
          $params = json_encode($this->params);
          $tplId = md5($params);
          if ($real) {
              $tpl = $this->outerHtml();
              $this->after("
              <template id='{$tplId}' data-params='{$params}'>
                  $tpl
              </template>");
              $this->attr("data-wb-tpl",$tplId);
          }
      return $tplId;
    }

    public function data($key = NULL, $val = NULL) {
        if (!((array)$this->data === $this->data)) $this->data = [];
        $dot = new Dot();
        $vars = ["_data"  => &$this->data];
        $dot->setReference($vars);
        $count = func_num_args();
        $args = func_get_args();
        if ($count == 0 ) return $dot->get("_data");
        if ($count == 1 AND !((array)$key === $key)) return $dot->get("_data.".$args[0]);
        if ($count == 1 AND  ((array)$key === $key)) {
            $this->data = $key;
        }
        if ($count == 1 AND  ((object)$key === $key)) {
            $this->data = wbObjToArray($key);
        }
        if ($count == 2) return $dot->set("_data.".$args[0],$args[1]);
    }

    public function tagdata(string $key=null, $val=null)
    {
        $doc_hash = spl_object_hash($this->document);

        if ($val !== null) { // set data for all nodes
            if (!isset(self::$node_data[$doc_hash])) {
                self::$node_data[$doc_hash] = array();
            }
            foreach ($this->nodes as $node) {
                if (!isset(self::$node_data[$doc_hash][self::getNodeId($node)])) {
                    self::$node_data[$doc_hash][self::getNodeId($node)] = (object) array();
                }
                self::$node_data[$doc_hash][self::getNodeId($node)]->$key = $val;
            }
            return $this;
        }

        if ($node = $this->getFirstElmNode()) { // get data for first element
            if (isset(self::$node_data[$doc_hash]) && isset(self::$node_data[$doc_hash][self::getNodeId($node)])) {
                if ($key === null) {
                    return self::$node_data[$doc_hash][self::getNodeId($node)];
                } elseif (isset(self::$node_data[$doc_hash][self::getNodeId($node)]->$key)) {
                    return self::$node_data[$doc_hash][self::getNodeId($node)]->$key;
                }
            }
            if ($key === null) { // object with all data
                $data = array();
                foreach ($node->attributes as $attr) {
                    if (strpos($attr->nodeName, 'data-') === 0) {
                        $val = $attr->nodeValue[0] === '{' ? json_decode($attr->nodeValue) : $attr->nodeValue;
                        $data[substr($attr->nodeName, 5)] = $val;
                    }
                }
                return (object) $data;
            }
            if ($data = $node->getAttribute('data-'.$key)) {
                $val = $data[0] === '{' ? json_decode($data) : $data;
                return $val;
            }
        }
    }


        public function beautifyHtml($step=0)
        {
            $this->children()->after("{{_line_}}");
            $this->children()->before("{{_line_}}".str_repeat("{{_tab_}}", $step));
            $childs=$this->children();
            foreach ($childs as $child) {
                if ($child->find("*")->length) {
                    $child->append(str_repeat("{{_tab_}}", $step));
                }
                $step++;
                $child->beautifyHtml($step);
                $step--;
                $str=trim($child->outerHtml());
                $child->after($str);
                $child->remove();
            }

            if ($step==0) {
                $result=trim($this->outerHtml());
                $result=trim(str_replace(array(" {{_tab_}}","{{_tab_}}","{{_line_}}"), array("{{_tab_}}","    ","\n"), $result));
                return $result;
            }
        }


    public function attrsCopyTo(&$dom)
    {
        $attrs = $this->attributes();

        foreach ($attrs as $attr => $value) {
            if ($attr !== "data-wb" && $attr !== "class" && $attr !== "name") {
                $dom->children(":first-child")->attr($attr, $value);
            } else if ($attr == "class") {
                $dom->children(":first-child")->addClass($value);
            } else if ($attr == "name") {
                $dom->find("[name]:first")->attr("name",$value);
            }
        }


        if ($attrs->name and $attrs->value) {
            $inp = $dom->find("[name='{$attrs->name}']");
            if ($inp) {
                $inp->attr("value", $attrs->value);
                if ($inp->is("[type=checkbox]") and $attrs->value == "on") {
                    $inp->attr("checked", true);
                }
            }
        }
        return $this;
    }

    public function attributes()
    {
        $attributes = [];
        $attrs = $this->attributes;
        foreach ((object)$attrs as $attr) {
            $attributes[$attr->nodeName] = $attr->nodeValue;
        }
        return $attributes;
    }

    public function parents($selector = null)
    {
        $res = true;
        $dom = $this;
        $parent = null;
        while ($res) {
            $dom = $dom->parent;
            if ($dom && $dom->is($selector)) {
                return $dom;
            } else {
                return $dom;
            }
        }
        //return $this->app->fromString("");
    }

    public function outerHtml($clear = false)
    {
        if ($clear) {
            $this->find(".wb-done")->removeClass("wb-done");
            $this->find("[wb-exclude-id]")->removeAttr("wb-exclude-id");
        }
        return $this->prop('outerHTML');
    }

    public function clear()
    {
        $this->html("");
        return $this;
    }

    public function replace($content)
    {
        $this->after($content);
        $this->remove("*");
    }


    public function clone()
    {
        $app = $this->app;
        $data = $this->data;
        $result = $this->createChildInstance();
        foreach ($this->nodes as $node) {
            $cloned_node = $node->cloneNode(true);
            if ($cloned_node instanceof \DOMElement && $cloned_node->hasAttribute('dqn_tmp_id')) {
                $cloned_node->removeAttribute('dqn_tmp_id');
            }
            $result->addDomNode($node->cloneNode(true));
        }
        $result->app = $app;
        $result->data = $data;
        return $result;
    }


    public function tag()
    {
        return $this->tagName;
    }

    public function setLocale($ini=null)
    {
        if (!$this->app) $this->app = new wbApp();

        $locale=null;
        $loc = [];
        if ((array)$ini === $ini) $loc = $ini;
        if ($this->find("[type='text/locale']")->length or $ini !== null or $loc == $ini) {
            $obj=$this->find("[type='text/locale']");
            if ($ini !== null and is_string($ini) and is_file($ini)) {
                $loc=parse_ini_string(file_get_contents($ini), true);
            } elseif (!count($loc)) {
                if ($obj->is("[data-wb*='role=include']") and $ini==null) {
                    $obj->fetchParams();
                    $obj->fetchNode();
                }
                $loc = parse_ini_string($obj->text(),true);
            }
            $obj->remove();
            if (count($loc)) {
                if (isset($loc["_global"]) and $loc["_global"]==false) {
                    $global=false;
                } else {
                    $global=true;
                }

                foreach ($loc as $lang => $variables) {
                    if (!isset($locale[$lang])) $locale[$lang]=array();
                    if (!isset($_ENV["locale"][$lang])) $_ENV["locale"][$lang]=array();
                    if ((array)$variables === $variables) {
                    foreach ($variables as $var => $val) {
                        $locale[$lang][$var]=$val;
                        if ($global==true) {
                            $_ENV["locale"][$lang][$var]=$val;
                        }
                    }
                    }
                }
            }
        }
        $this->locale = $locale;
        return $this;
    }

    public function hasRole($role=null)
    {
        if ($role == null && isset($this->params->role)) {
            return $this->params->role;
        } elseif ($role !== null and $role == $this->params->role) {
            return true;
        } else {
            return false;
        }
    }

    public function hasAttr($attr)
    {
        $attrs = array_keys($this->attributes());
        return in_array($attr,$attrs);
    }

    public function setValues($Item=null)
    {
        $tplInner = [];
        $tpls = $this->find("template");
        foreach($tpls as $tpl) {$tplInner[] = $tpl->html(); $tpl->html("");}

        $outer = $this->outerHtml();
        if (trim($outer) == "") return $this;

        if ($Item == null) $Item = $this->data;
        if (!((array)$Item === $Item)) $Item=[];

        //if ($this->locale) $this->setLocale($this->locale);
        if ($this->attributes->length) $this->setAttributes($Item);
        $inner = $this->html();
        if (strpos(" ".$inner,"<!--[##")) {
            // Scripts set data
            foreach($_ENV["saved_scripts"] as $id => &$script) {
                $script = wbSetValuesStr($script, $Item);
            }
        }

        if (strpos($inner,"}}")) {
          $inner = wbSetValuesStr($inner, $Item);
          $this->html($inner);
        }
        $list=$this->find("input,select,textarea");
        if (!$list->length) return $this;
        foreach ($list as $inp) {
            if (!$inp->hasClass("wb-value")) {
                $inp->data = $Item;
                $inp->setAttributes();
                $from=$inp->attr("data-wb-from");
                $name=$inp->attr("name");
                $def=$inp->attr("value");
                if ($inp->is("textarea")) {
                    $def=$inp->html();
                    if (isset($Item[$name])) {
                        if ((array)$Item[$name] === $Item[$name]) {
                            $inp->html(wbJsonEncode($Item[$name]));
                        } else {
                            $inp->html(($Item[$name]));
                        }
                    } else {
                        $inp->html(($def));
                    }
                    $inp->addClass("wb-value");
                } else if ($inp->is("select") and isset($Item[$name])) {
                          if ($inp->is("[multiple]")) {
                              $value=json_decode($Item[$name], true);
                              if ((array)$value === $value) {
                                  foreach ($value as $val) {
                                      if ($val>"") {
                                          $inp->find("option[value=".$val."]")->attr("selected", true);
                                      }
                                  }
                              }
                              $value=$value[0];
                              $inp->addClass("wb-value");
                          } else {
                              $value=$Item[$name];
                              $inp->find("option[value='".$value."']")->attr("selected", true);
                          }
                          $inp->attr("data-wb-value",$value);
                } else {
                    if (substr($name, -2)=="[]") $name=substr($name, 0, -2);
                    if (substr($def, 0, 3)=="{{_") $def="";

                    if (isset($Item[$name]) and $inp->attr("value")=="") {
                        if ((array)$Item[$name] === $Item[$name]) {
                            $Item[$name]=wbJsonEncode($Item[$name]);
                        }
                        $value=$Item[$name];
                    } else {
                        $value=$def;
                    }
                    if ($value!=="") {
                        $inp->attr("value", $value);
                        $inp->addClass("wb-value");
                    } else {
                        if (!$inp->hasAttr("value") and isset($Item[$name])) {
                            $tmpname=$Item[$name];
                            if ((array)$tmpname === $tmpname) {
                                $Item[$name]=wbJsonEncode($tmpname);
                            }
                            $inp->attr("value", $Item[$name]);
                            $inp->addClass("wb-value");
                        }
                    }

                    if ($inp->attr("type")=="checkbox") {

                        $inp->attr("value", $Item[$name]);
                        if ($inp->attr("value")=="on" or $inp->attr("value")=="1" or $inp->attr("value")=="true") {
                            $inp->attr("checked", true);
                            $inp->attr("value", "on");
                            $inp->addClass("wb-value");
                        }
                    }
                };
            }
        }

        $tpls = $this->find("template");
        foreach($tpls as $t => $tpl) {$tpl->html($tplInner[$t]);}

        return $this;
    }

    public function clearValues($rep="")
    {
        $out = $this->html();
        $out = preg_replace('/\{\{([^\}]+?)\}\}+|<script.*text\/template.*?>.*?<\/script>(*SKIP)(*F)|<template.*?>.*?<\/template>(*SKIP)(*F)/isumx', $rep, $out);
        $out = str_replace(["%7B%7B","%7D%7D"],['{{','}}'],$out);
        $this->html($out);
        return $this;
    }


    public function setAttributes($Item=null, $clear = true)
    {
      $attributes=$this->attributes;
      if (!$attributes->length) return $this;
        if ($Item == null) $Item = $this->data;
        if (is_object($Item)) $Item=wbObjToArray($Item);
            foreach ($attributes as $at) {
                  $atname=$at->name;
                //if ($atname !== "data-wb-if") {
                    if (strpos($atname, "}}")) $atname=wbSetValuesStr($atname, $Item);
                    $atval=html_entity_decode($this->attr($atname));
                    $atval=str_replace(["%7B%7B","%7D%7D"],['{{','}}'],$atval);
                    if ($atval>"" && strpos($atval, "}}")) {
                        $fld=str_replace(array("{{","}}"), array("",""), $atval);
                        if (isset($Item[$fld]) and $this->is(":input")) {
                            $atval=$Item[$fld];
                            if ((array)$atval === $atval) {
                                $atval=wbJsonEncode($atval);
                            }
                        } else {
                            $atval=wbSetValuesStr($atval, $Item);
                        }
                        if ($clear == true) {
                            $atval=preg_replace('/\{\{([^\}]+?)\}\}+/', '', $atval);
                        }
                        $this->attr($atname, $atval);
                    };
                //}
            }
        return $this;
    }

    public function where($Item=null)
    {
        $res = true;
        $where=$this->params->where;
        $this->removeAttr("data-wb-where");
        if ($where == "") return $res;
        if ($Item == null) $Item=$this->data;
        $res = wbWhereItem($Item, $where);
        return $res;
    }
}

class wbApp
{
    public $settings;
    public $route;
    public $data;
    public $dom;
    public $template;

    public function __construct()
    {
        $this->vars = new Dot();
        $vars = [
          "_env"  => &$_ENV,
          "_get"  => &$_GET,
          "_srv"  => &$_SERVER,
          "_post" => &$_POST,
          "_req"  => &$_REQUEST,
          "_var"  => &$_ENV["variables"],
          "_route"=> &$_ENV["route"],
          "_sett" => &$_ENV["settings"],
          "_sess" => &$_SESSION,
          "_cookie"=>&$_COOKIE,
          "_cook"  =>&$_COOKIE,
          "_mode" => &$_ENV["route"]["mode"],
          "_form" => &$_ENV["route"]["form"],
          "_item" => &$_ENV["route"]["item"],
          "_param"=> &$_ENV["route"]["param"],
          "_locale"=> &$_ENV["locale"]
      ];
        $this->vars->setReference($vars);
        $this->Init($this);
        $this->lang = $this->vars->get("_env.lang");
        $vars["_lang"] = &$_ENV["locale"][$this->lang];
        $this->vars->setReference($vars);
    }

    public function __call($func, $params)
    {
        $wbfunc="wb".$func;
        $_ENV["app"] = &$this;
        if (is_callable($wbfunc)) {
            $prms = [];
            foreach($params as $k => $i) $prms[] = '$params['.$k.']';
            eval('$res = $wbfunc('.implode(",",$prms).');');
            if ($func=="Init") {
                $this->settings();
                $this->getRoute();
            }
            return $res;
        } elseif (!is_callable($func)) {
            die("Function {$wbfunc} not defined");
        } else {
            $par = [];
            for($i=0; $i<count($params); $i++) $par[] = '$params['.$i.']';
            eval('$res = $func('.implode(",",$par).');');
            return $res;
        }
    }


    public function filterItem($item) {
        if ($this->vars("_post._filter")) $filter = $this->vars("_post._filter");
        if (!isset($filter)) return true;
        $vars = new Dot();
        $vars->setReference($item);
        foreach($filter as $fld => $val) {
            $val = preg_replace('/^\%(.*)\%$/', "", $val);
            if ($val !== "" AND in_array(substr($fld,-5),["__min","__max"])) {
                if (substr($fld,-5) == "__min" AND $val > $vars->get(substr($fld,0,-5))) return false;
                if (substr($fld,-5) == "__max" AND $val < $vars->get(substr($fld,0,-5))) return false;
            } else if ($val !== "" AND (string)$val === $val AND $vars->get($fld) !== $val) {
                return false;
            } else if ((array)$val === $val AND !in_array($vars->get($fld),$val) ) {
                return false;
            }

        }
        return true;
    }


    public function fieldBuild($dict, $data=[])
    {
        if (is_array($dict)) $dict = wbArrayToObj($dict);

        $this->dict = $dict;
        $this->data = $data;
        $this->tpl = $this->getForm('snippets', $dict->type);
        $this->tpl->dict = $dict;
        $this->tpl->data = $data;
        $this->tpl->setAttributes($dict);
        $this->tpl->find("input")->attr("name",$this->dict->name);
        $this->dict->prop->style > "" ? $this->tpl->find("[style]")->attr("style", $this->dict->prop->style) : $this->tpl->find("[style]")->removeAttr("style");
        $func = __FUNCTION__ . "_". $dict->type;
        if (!method_exists($this, $func)) {
            $func = __FUNCTION__ . "_". "common";
        }
        return $this->$func();
    }

    public function fieldBuild_multiinput()
    {
        $mult = $this->tpl;
        $mult->data = $this->data;
        $mult->dict = $this->dict;
        tagMultiInput($mult);
        return $mult;
    }

    public function fieldBuild_forms()
    {
        $form = $this->tpl;
        $form->data = $this->data;
        $form->dict = $this->dict;
        $form->find("meta")->setAttributes($form->dict->prop);
        $form->fetch();
        return $form;
    }

    public function fieldBuild_common()
    {
        $this->tpl->setValues();
        return $this->tpl->fetch();
    }

    public function addEvent($name,$params=[]) {
        $evens = json_decode(base64_decode($this->vars("_cookie.events")),true);
        $events[$name] = $params;
        $events = base64_encode(json_encode($events));
        setcookie("events", $events,time()+3600,"/"); // срок действия сутки
    }


    public function fieldBuild_enum()
    {
        $lines=[];
        if ($this->dict->prop->enum > "") {
            $arr=explode(",", $this->dict->prop->enum);
            foreach ($arr as $i => $line) {
                $lines[$line] = ['id' => $line, 'name' => $line];
            }
        }
        $res = $this->tpl->fetch(["enum" => $lines]);
        $value = $this->data[$this->dict->name];
        $res->find("option[value='{$value}']")->attr("selected",true);
        return $res;
    }


    public function fieldBuild_module()
    {
        $this->tpl->setAttributes($this->dict);
        $this->tpl->fetch();
    }

    public function addEditor($name, $path, $label = null)
    {
        $this->addTypeModule("editor", $name, $path, $label);
    }

    public function addModule($name, $path, $label = null)
    {
        $this->addTypeModule("module", $name, $path, $label);
    }

    public function addDriver($name, $path, $label = null)
    {
        $this->addTypeModule("driver", $name, $path, $label);
    }

    public function addTypeModule($type, $name, $path, $label = null)
    {
        $types = [
             "module"=>"_env.modules.{$name}"
            ,"editor"=>"_env.editors.{$name}"
            ,"driver"=>"_env.drivers.{$name}"
            ,"uploader"=>"_env.drivers.{$name}"
        ];
        $dir = dirname($path,1);
        $dir = substr($dir,strlen($_SERVER["DOCUMENT_ROOT"]));

        if (in_array($type, array_keys($types))) {
          if ($label == null) $label = $name;
          if ( !$this->vars($types[$type])) {
                $this->vars($types[$type], [
                   "name"=>$name
                   ,"path"=>$path
                   ,"dir"=>$dir
                   ,"label"=>$label
                 ]);
          } else if ($label !== $name) {
              $this->vars($types[$type].".label",$label);
          }
        } else {
            throw new \Exception('Wrong module type: '.$type.' Use available types: '.implode(", ", array_keys($types)));
        }
    }

    function loadController()
    {
        if ($this->vars("_route.controller")) {
            $path = '/controllers/'.$this->vars("_route.controller").'.php';
            if (is_file($this->vars("_env.path_engine").$path)) include_once $this->vars("_env.path_engine").$path;
            if (is_file($this->vars("_env.path_app").$path)) include_once $this->vars("_env.path_app").$path;
            $ecall = $this->vars("_route.controller").'__controller';
            $acall = $this->vars("_route.controller").'_controller';
            if (is_callable($acall)) return $acall($this);
            if (is_callable($ecall)) return $ecall($this);
            echo "Controller not found: {$this->vars("_route.controller")}";
            die;
        }
    }

    public function json($data)
    {
        $json = new Jsonq();
        if (is_string($data)) {
            $data=wbItemList($data);
        } elseif (!is_array($data)) {
            $data=(array)$data;
        }
        return $json->collect($data);
    }

    public function dot(&$array=[])
    {
        $dot = new Dot();
        $dot->setReference($array);
        return $dot;
    }

    public function settings()
    {
        $this->settings=$_ENV["settings"];
        return $this->settings;
    }

    public function vars() {
      $count = func_num_args();
      $args = func_get_args();
      if ($count == 0 ) return;
      if ($count == 1) return $this->vars->get($args[0]);
      if ($count == 2) return $this->vars->set($args[0],$args[1]);
    }


    public function getRoute()
    {
        $this->route=$_ENV["route"];
        return $this->route;
    }

    public function variable($name, $value="__wbVarNotAssigned__")
    {
        if ($value=="__wbVarNotAssigned__") {
            return $this->data[$name];
        }
        $this->data[$name]=$value;
        return $this->data;
    }

    public function ___data($data="__wbVarNotAssigned__")
    {
        if ($data=="__wbVarNotAssigned__") {
            return $this->data;
        }
        $this->data=$data;
        return $this->data;
    }

    public function template($name="default.php")
    {
        $this->template=wbGetTpl($name);
        $this->dom = clone $this->template;
        return $this->dom;
    }

    public function fromString($string="", $isDocument = false)
    {
        if ($string==null) $string="";
        if ($isDocument == true && !strpos(" ".$string, "<html")) {
            $string = "<html class='wb-html'>{$string}</html>";
        }
        $dom=new wbDom($string);
        $dom->app = $this;
        $dom->setLocale();
        return $dom;
    }

    public function getForm($form = null, $mode = null, $engine = null)
    {
        $_ENV['error'][__FUNCTION__] = '';
        if (null == $form) {
            $form = $this->vars->get("_route.form");
        }
        if (null == $mode) {
            $mode = $this->vars->get("_route.mode");
        }
        $modename = $mode;
        if (strtolower(substr($modename, -4)) == ".ini") {
            $ini = true;
        } else {
            $ini = false;
        }
        if (!in_array(strtolower(substr($modename, -4)), [".php",".ini",".htm",".tpl"])) {
            $modename = $modename.".php";
        }

        $aCall = $form.'_'.$mode;
        $eCall = $form.'__'.$mode;

        $loop=false;
        foreach (debug_backtrace() as $func) {
            if ($aCall==$func["function"]) {
                $loop=true;
            }
            if ($eCall==$func["function"]) {
                $loop=true;
            }
        }

        if (is_callable($aCall) and $loop == false) {
            $out = $aCall();
        } elseif (is_callable($eCall) and false !== $engine and $loop == false) {
            $out = $eCall();
        }

        if (!isset($out)) {
            $current = '';
            $flag = false;
            $path = array("/forms/{$form}_{$modename}", "/forms/{$form}/{$form}_{$modename}", "/forms/{$form}/{$modename}");
            foreach ($path as $form) {
                if (false == $flag) {
                    if (is_file($_ENV['path_engine'].$form)) {
                        $current = $_ENV['path_engine'].$form;
                        $flag = $engine;
                    }
                    if (is_file($_ENV['path_app'].$form) && false == $flag) {
                        $current = $_ENV['path_app'].$form;
                        $flag = true;
                    }
                }
            }
            unset($form);
            if ('' == $current) {
                $out=null;
                $current = "{$_ENV['path_engine']}/forms/common/common_{$modename}";
                if (is_file($current)) {
                    $out = $this->fromFile($current, true);
                }
                $current = "{$_ENV['path_app']}/forms/common/common_{$modename}";
                if (is_file($current)) {
                    $out = $this->fromFile($current, true);
                }
                if ($out == null) {
                    $current = wbNormalizePath("/forms/{$form}_{$modename}");
                    $out = wbErrorOut(wbError('func', __FUNCTION__, 1012, array($current)), true);
                }
            } else {
                if ($ini) {
                    $out = file_get_contents($current);
                    $out = $this->fromString($out, true);
                } else {
                    $out = $this->fromFile($current);
                }
            }
        }
        if (is_object($out)) $out->path = $current;
        return $out;
    }


    public function fromFile($file="", $isDocument = false)
    {
        $res = "";
        if ($file=="") {
            return $this->fromString("", $isDocument);
        } else {
            //session_write_close(); Нельзя, иначе проблемы с логином
            $url=parse_url($file);
            if (isset($url["scheme"])) {
                session_write_close();
                $context = stream_context_create(array(
                     'http'=>array(
                             'method'=>"POST",
                             'header'=>	"Accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7\r\n" .
                             "Cache-Control: no-cache\r\n" .
                             'Content-Type:' . " application/x-www-form-urlencoded\r\n" .
                             'Cookie: ' . $_SERVER['HTTP_COOKIE']."\r\n" .
                             'Connection: ' . " Close\r\n\r\n",
                             'content' => http_build_query($_POST)
                     )
                 ));
                $res=@file_get_contents($file, false, $context);
                session_start();
            } else {
                if (!is_file($file)) {
                    $file = str_replace($_ENV["path_app"], "", $file);
                    $file=$_ENV["path_app"].$file;
                    return null;
                } else {
                    $fp = fopen($file, "r");
                    flock($fp, LOCK_SH);
                    $res=file_get_contents($file, false, $context);
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
            }
            $dom = $this->fromString($res, $isDocument);
            $dom->path = str_replace($_ENV["dir_app"],"",dirname($file,1));
            return $dom;
        }
    }

    public function getTpl($tpl = null, $path = false)
    {
        $out = null;

        if (true == $path) {
            if (!$cur and is_file($_ENV['path_app']."/{$tpl}")) {
                $cur = wbNormalizePath($_ENV['path_app']."/{$tpl}");
            }
        } else {
            if (!$cur and is_file($_ENV['path_tpl']."/{$tpl}")) {
                $cur = wbNormalizePath($_ENV['path_tpl']."/{$tpl}");
            }
            if (!$cur and is_file($_ENV['path_engine']."/tpl/{$tpl}")) {
                $cur = wbNormalizePath($_ENV['path_engine']."/tpl/{$tpl}");
            }
        }
        $out = $this->fromFile($cur,true);
        if (!$out) {
            if ($path !== false) {
                $cur = wbNormalizePath($path."/{$tpl}");
            } else {
                $cur = wbNormalizePath($_ENV['path_tpl']."/{$tpl}");
            }
            $cur=str_replace($_ENV["path_app"], "", $cur);
            wbErrorOut(wbError('func', __FUNCTION__, 1011, array($cur)));
        }
//        $locale=$out->setLocale($ini);
//        if ($locale!==null) {
//            wbEnvData("tpl->{$tpl}->locale", $locale);
//        }
        //return $out->find(".wb-html");
        return $out;
    }


    public function dom($data="__wbVarNotAssigned__", $clear=true)
    {
        if ($data!=="__wbVarNotAssigned__") {
            $this->data($data);
        }
        $tpl=wbFromString($this->dom);
        $tpl->wbSetData($this->data, $clear);
        $this->dom=$tpl->outerHtml();
        return $this->dom;
    }
}
