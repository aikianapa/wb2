<?php
function yamap__init(&$dom) {
		$out = $dom->app->fromFile(__DIR__ ."/yamap_ui.php");
		$field = $dom->attr("name");
		if ($dom->tag() !== "input") {
				$out->find(".yamap_editor")->remove();
				if ($field > "") {
						$out->find(".yamap_canvas")->html(json_encode($dom->data[$field]));
				} else {
						$out->find(".yamap_canvas")->html($dom->html());
				}
		} else {
				if ($field > "") {
					$out->find("div.yamap .yamap_editor")->attr("name",$field);
				}
		}
		foreach($dom->attributes() as $at => $val) {
				if (substr($at,0,7) !== "data-wb" AND substr($at,0,5) !== "class") $out->find(".yamap")->attr($at,$val);
		}
		$out->fetch($dom->data);
		$dom->after($out)->remove();
}

?>
