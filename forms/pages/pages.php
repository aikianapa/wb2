<?php
function pages__edit() {
	$app = new wbApp();
	$out = $app->getForm("pages","edit");
	$id = $app->vars("_route.item");
	$out->data = $app->itemRead("pages",$id);
	if ($id=="_new") {
		$out->data["id"]=wbNewId();
		$out->data["template"]=$app->vars->get("_sett.template");
	}
	$out->data["tpllist"]=wbListTpl();
	$out->data=wbCallFormFunc("BeforeItemEdit",$out->data);
	$out->fetch();
	$options=$out->find("select[name=template] option");
	foreach($options as $opt) {
		if (strpos($opt->attr("value"),".inc.")) $opt->remove();
	}
	return $out;
}

function _pagesAfterItemRead($Item=null) {
    if ($Item!==null) {
        if ($_ENV["route"]["mode"]=="show") {
            if (!isset($Item["title"]) OR $Item["title"]=="") {$Item["title"]=$Item["header"];}
            if ($Item["title"]=="") {$Item["title"]=$_ENV["settings"]["header"];}
        }
        if ($_ENV["route"]["mode"]=="list") {
            if (!is_array($Item["images"])) {$tmp=json_decode($Item["images"],true);} else {$tmp=$Item["images"];}
			if (is_array($tmp) AND count($tmp)) {$Item["images_count"]=count($tmp);} else {$Item["images_count"]=0;}
            unset($Item["text"]);
        }
    }
	return $Item;
}




?>
