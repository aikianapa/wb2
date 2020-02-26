<?php
function tagTree(&$dom, $Item=null) {
    if ($dom->hasClass("wb-done")) return;
    if ($Item == null) $Item=$dom->data;
    if (!is_array($Item)) $Item=array($Item);
        $name = $dom->params->name;
        $from = $dom->params->from;
        $form = $dom->params->form;
        $item = $dom->params->item;
        $type = $dom->params->type;
        $field = $dom->params->field;
        $srcData = $Item;
        if (!$name) $name=$dom->params->name = $dom->attr("name");

        if (!$dom->params->from AND !$dom->params->field) {$field = $name;}
        if ($dom->is("select") AND !$dom->params->form) {$field = "tree";}

        if ($form > "" AND $item>"") $Item = $dom->app->itemRead($form,$item);

        if ($form == "" AND $item > "") {
            $Item = $dom->app->treeRead($item);
            $field = "tree";
        }

        if ($from > "") $Item = $Item[$from];

        if ($field > "") {
            if (!isset($Item[$field])) {
                $id = $dom->app->newId();
                $Item = [$id=>["id"=>$id,"name"=>""]];
                $tree["dict"] = [];
            } else {
                $tree["dict"] = $Item[$field]["dict"];
                $Item = $Item[$field]["data"];
                unset($Item["data"]);
                unset($Item["dict"]);
            }
        }


        if (isset($dom->params->dict)) {
            $dictdata=wbItemRead("tree",$dom->params->dict);
            if (!isset($Item[$name])) $Item[$name]=$dictdata["tree"];
            unset($dictdata);
        }

        if (($dom->hasAttr("name") OR $dom->is("input")) AND !$dom->is("select") ) {
            $Item = wbItemToArray($Item);
            $inp = tagTreeInput($dom,["data"=>$Item,"dict"=>$tree["dict"]]);
            $dom->after($inp);
            $dom->addClass("wb-out-inner");


        } elseif ($dom->is("select")) {
            $select = new tagTreeSelect();
            $select->dom = &$dom;
            $select->tree = $Item;
            $select->params = null;
            $select->stage();
            //tagTreeUl($dom,$Item,null,$srcVal);
        } else {
            tagTreeUl($dom,$Item,null,$srcVal);
        }
        $dom->addClass("wb-done");
        $dom->removeAttr("data-wb");
    }

class tagTreeSelect {
  function stage() {
      if ($this->dom->is("select")) {
          $params = $this->dom->params;
          $this->tpl = $this->dom->html();
          $this->dom->html("");
          $this->lvl = 0;
          $this->idx = 0;
          $this->limit = -1;
          $this->level = -1;
          $this->parent = $params->parent;
          if (!$params->parent) $this->parent = true;
          if ($params->parent == "true") $this->parent = true;
          if ($params->parent == "false") $this->parent = false;

          if (!$params->children) $this->children = true;
          if ($params->children == "true") $this->children = true;
          if ($params->children == "false") $this->children = false;

          if ($params->level) $this->level = intval($params->level);

          $this->select = &$this->dom;
          $app = &$this->dom->app;
          if ($params->branch !== null AND $params->branch == "") {
              $this->tree = [];
          } else if ($params->branch > "") {
              $this->tree = wbTreeFindBranch($this->tree,$params->branch);
          }
          if ($this->dom->hasAttr("placeholder")) $this->dom->html("<option value='' class='placeholder'>".$this->dom->attr("placeholder")."</option>");
      } else {
          $app = $this->dom->app;
      }
      $flag = false;
      if ((array)$this->tree === $this->tree) {
        if ($rand==true) shuffle($tree);
        foreach($this->tree as $i => $item) {
            if (!((array)$item === $item)) $item = (array)$item;
            $line=$app->fromString($this->tpl);
            $line->fetch($item);
            if ($this->parent === "disabled") {
                $line->attr("disabled",true);
                $this->parent = null;
            }

            if ($this->dom->tag() == "option") {
                $line->prepend("<span>".str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$this->lvl)."</span>");
            }

            $item["_parent"]=&$Item;
            if ($this->children == false && $this->lvl > 1) return;
            $this->lvl++;
            $item=(array)$srcVal + (array)$item;
            $item["_idx"]=$this->idx;
            $item["_ndx"]=$this->idx+1;
            $item["_lvl"]=$this->lvl;

            if ($this->parent !== false && $item["active"] == "on")  $flag = true;
            if ($this->level > "" && $this->level !== $this->lvl) $flag = false;
            if ($app->vars("_post._filter") && $flag) $flag = $app->filterItem($item);
            if ($flag == true) $this->select->append($line);

            if ((isset($item["children"]) AND is_array($item["children"]) AND count($item["children"]))) {

                if ($this->lvl > 0) $this->parent = true;
                $option = new tagTreeSelect();
                $option->dom = $line;
                $option->lvl = $this->lvl;
                $option->idx = $item["_idx"];
                $option->tree = $item["children"];
                $option->tpl = $this->tpl;

                $option->parent = $this->parent;
                $option->children =  $this->children;
                $option->level = $this->level;
                $option->select = &$this->select;

                if ($item["active"] == "on") $option->stage();

            }
            $this->idx++;
            $this->lvl--;
        }
      }
  }
}


function tagTreeInput($dom,$data=array()) {
    $tpl=$dom->app->fromFile(__DIR__ ."/tree_ui.php");
    tagTreeUl($tpl,$data["data"],null);
    $data=wbJsonEncode($data);
    $tpl->append("
        <textarea type='json' class='wb-tree-data wb-value' name='{$dom->params->name}'>{$data}</textarea>
        <script type='wbapp'>
        wbapp.loadScripts(['/engine/tags/tree/tree.js','/engine/js/jquery-ui.min.js'],'tree-js');
        </script>
        ");
    return $tpl;
}

function tagTreeUl(&$dom,$Item=array(),$param=null,$srcVal=array()) {
        $limit=-1;
        $lvl=0;
        $level=-1;
        $idx=0;
        $tree=$Item;
        if ($dom->params->branch > "") {$branch = $dom->params->branch;} else {$branch = 0;}
        if ($dom->params->parent == "false") {$parent = 0;} else {$parent=1;}
        if ($dom->params->limit > "") {$limit = intval($dom->params->limit);}
        $parent_id="";
        $pardis=0;
        if ($dom->params->children == "false") {$children = 0;} else {$children=1;}
        if ($dom->params->rand == "true") {$rand = true;} else {$rand=false;}

        $srcItem = $Item;
        $tag = $dom->tag();
        if ($param==null) {
            $name=$dom->attr("name");
            if (isset($from)) $name=$from;
            $tree = wbItemToArray($Item);
            if (isset($call) AND $call > "" AND is_callable($call)) {
                $tree=@$call();
            }
            if (isset($rand) AND $rand=="true") {$rand=true;}
            if (!isset($limit) OR $limit=="false" OR intval($limit) < 0) {
                $limit=-1;
            }
            else {
                $limit=$limit*1;
            }
            $tpl = $dom->html();
        } else {
            foreach($param as $k =>$val) $$k=$val;
            $tree=$Item;
        }
        if (!isset($level)) $level="";
        $dom->html("");
        if ($branch !== 0) {
    			if ($tree==NULL AND $branch>"") {
    				$tree=wbTreeFindBranch($Item["children"],$branch);
    			} else {
    				$tree=wbTreeFindBranch($tree,$branch);
    			}
        }


        $idx=0;

        if ((array)$tree === $tree) {
			if ($rand==true) shuffle($tree);
            foreach($tree as $i => $item) {
                if (!((array)$item === $item)) $item = (array)$item;
                $item["_parent"]=$Item;
                $lvl++;
                $item=(array)$srcVal + (array)$item;
                $item["_pid"]=$parent_id;
                $item["_idx"]=$idx;
                $item["_ndx"]=$idx+1;
                $item["_lvl"]=$lvl-1;
                if ($parent_id>"") $item["%id"]=$parent_id;
                $line=$dom->app->fromString($tpl);
                $child=$dom->app->fromString($tpl,true);
                $line->fetch($item);
                if ($parent==0 OR (isset($item["children"]) AND is_array($item["children"]) AND count($item["children"]))) {
                    if ($pardis==1 AND ($limit!==$lvl-1)) {
                        $line->attr("disabled",true);
                    }

                    if ($lvl>1) $parent=1;
                    tagTreeUl($child,$item["children"],array("name"=>$name,"tag"=>$tag,"lvl"=>$lvl,"tpl"=>$tpl,"idx"=>$idx,"level"=>$level,"parent_id"=>$item["id"],"pardis"=>$pardis,"parent"=>$parent,"children"=>$children,"limit"=>$limit),$srcVal);
                    if (($limit==-1 OR $lvl<=$limit)) {

                            if ($parent !== 1) {
                                $lvl--;
                                $line->html($child->find(".wb-html")->html());
                            } else {
                                if ($children == 1) $line->append("<{$tag}>".$child->find(".wb-html")->html()."</{$tag}>");
                            }
                    }
                }
                $idx++;
                $lvl--;
                if (isset($line)) $dom->append($line->outerHtml());
            }
        }
    }

function tagTreeForm($dict=[],$data=[]) {
    $app = new wbApp();
    $fldset = $app->fromFile(__DIR__ . "/tree_fldset.php");
    $out = "";
    if ((array)$dict === $dict) {
        foreach($dict as $fld) {
          $set = $fldset->clone();
          $set->fetch($fld)->clearValues();
          $set->find("label")->html($fld["label"]);
          $set->find("div.col-12")->append($app->fieldBuild($fld,$data["data"]));
          $out .= $set->outerHtml()."\n";
          //$out .= wbFieldBuild($fld,$data)."\n";
        }
    }
    return $out;
}

function tagTreeProp($type=null) {
    $app = new wbApp();
    $out = $app->fromFile(__DIR__ . "/tree_prop.php");
    if ($type == null) {
        $type = $_POST["type"];
        $com = $app->fromString($out->find("[type=common]")->html(),true);
        $com->fetch($_POST["dict"]);

    }
    if ($out->find("[type={$type}]")->length) {
      $out = $app->fromString($out->find("[type={$type}]")->html(),true);
      $out->fetch($_POST["dict"]);
      if (isset($com)) $out->find("form")->append($com->find("form")->html());
    } else {
      $out = $com;
    }
    $out->fetch($_POST["dict"]);
    return wb_json_encode(["content"=>$out->html()]);
}

function ajax__tree_getform() {
    // build form to edit branch
    $app = new wbApp();
    if ($app->vars->get("_route.params.0") == "prop") return tagTreeProp();
    if ($app->vars->get("_route.params.0") == "lang") return tagTreeProp("lang");

    if ($app->vars->get("_route.params.0") == "dict") {
        $dict = $app->fromFile(__DIR__ . "/tree_dict.php");
        $dict->fetch($_POST);
        return wb_json_encode(["content"=>$dict->outerHtml(),"post"=>$_POST]);
    }

    $data = tagTreeForm($_POST["dict"],$_POST["data"]);
    $data = $app->fromString($data);
    $data->fetch($_POST["data"]);
    if ($app->vars->get("_route.params.0") == "data") return wb_json_encode(["content"=>$data->outerHtml(),"post"=>$_POST]);
    $out = $app->fromFile(__DIR__ . "/tree_edit.php");
    $out->fetch($_POST["data"]);
    $out->find(".treeData > form")->html($data);
    return wb_json_encode(["content"=>$out->outerHtml(),"post"=>$_POST]);
}

function ajax__tree_update() {
    $app = new wbApp();
    $tpl=$app->fromFile(__DIR__ ."/tree_ui.php",false);
    $tpl->fetch($_POST);
    return wb_json_encode(["content"=>$tpl->html()]);
}
?>
