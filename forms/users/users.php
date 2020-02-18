<?php

function users__list() {
	$app = new wbApp();
	$_form = $app->vars->get("_route.form");
	$_mode = $app->vars->get("_route.mode");
	$_item = $app->vars->get("_route.item");
	$out = $app->getForm($_form,$_mode);
	$flag=""; $where=""; $Item=array();
	if ($app->vars->get("_route.params.groups") == "true") {
			$where = 'isgroup = "on"';
	} else {
			$where = 'isgroup = ""';
	}
	if ($_item > "") $where .= 'AND role="'.$_item.'"';
	$out->data = [
			"result" 	=> $app->itemList($_form,$where,"id"),
			"_table"	=> $_form
	];
	$out->fetch();
  return $out;
}

function _usersBeforeItemSave($Item) {
	if (wbLoopCheck(__FUNCTION__,func_get_args())) {return $Item;} else {wbLoopProtect(__FUNCTION__,func_get_args());}
	// clear menu/dashboard config
	if (isset($_SESSION["user_id"]) AND $Item["id"]==$_SESSION["user_id"] AND $_SESSION["user_lang"]!==$Item["lang"]) {
		$_SESSION["lang"]=$_SESSION["user_lang"]=$Item["lang"];
	}
	if (isset($Item["newpwd"])) {
			$Item["password"] = md5($Item["newpwd"]);
			unset($Item["newpwd"]);
	}
	return $Item;
}

?>
