<?php
function tagFormdata(&$dom,&$Item=null) {
    $mode="show";
    if ($Item==null && isset($dom->data)) $Item=$dom->data;
    if ($Item==null && !isset($dom->data)) $Item=[];
    if (isset($dom->params->form)) $dom->params->table = $dom->params->form;
    if (isset($dom->params->table)) $table = $dom->params->table;
    if (isset($dom->params->mode)) $mode = $dom->params->mode;
    $_parent = $Item;
    // get items from table

        if (isset($dom->params->form) AND in_array($dom->params->form,$_ENV["tables"])) {
            $Item=wbItemRead($dom->params->form,$dom->params->item);
        } else if (isset($dom->params->form) AND !in_array($dom->params->form,$_ENV["tables"])) {
            $Item=[];
        }

        if (isset($dom->params->table) AND in_array($dom->params->table,$_ENV["tables"])) {
            $Item=wbItemRead($dom->params->table,$dom->params->item);
        } else if (isset($dom->params->table) AND !in_array($dom->params->table,$_ENV["tables"])) {
            $Item=[];
        }
// get items from array
        if (isset($dom->params->from) AND isset($Item[$dom->params->from])) {
            $Item=$Item[$dom->params->from];
        } else if (isset($dom->params->from) AND !isset($Item[$dom->params->from])) {
            $Item=[];
        }

        if (isset($vars) AND $vars>"") $Item=wbAttrAddData($vars,$Item);
        if (isset($json) AND $json>"") $Item=json_decode($json,true);


        if ($dom->params->field > "") {
            $dom->data = $Item;
            $Item = $dom->data = $dom->data($dom->params->field);
        }
        if ($dom->params->vars>"") $Item=wbAttrAddData($dom->params->vars,$Item);
        if ($dom->params->call>"" AND is_callable($dom->params->call)) {
            $call = $dom->params->call;
            $Item=$call($Item);
        }

        if (isset($table)) {
				      $Item=wbCallFormFunc("BeforeShowItem",$Item,$table,$mode);
				      $Item=wbCallFormFunc("BeforeItemShow",$Item,$table,$mode);
		    }
        if (isset($clear) AND $clear=="true") {$clear=true;} else {$clear=false;}
        $Item["_parent"] = $_parent;
        wbItemBeforeShow($Item);
        $dom->data = $Item;
        $dom->removeAttr("data-wb");
}
?>
