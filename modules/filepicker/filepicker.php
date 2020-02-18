<?php
function filepicker__init(&$dom) {
	$app = new wbApp();
	if ($dom->params->mode == "single") {
			$out=$app->fromFile(__DIR__ ."/filepicker_ui_single.php",true);
	} else {
			$out=$app->fromFile(__DIR__ ."/filepicker_ui_multi.php",true);
	}

	if ($dom->params->name) $out
			->find(".filepicker-data")
			->attr("name",$dom->params->name);
	if ($dom->attr("name") >"") $out
			->find(".filepicker-data")
			->attr("name",$dom->attr("name"));

	if ($dom->params->path) $out
			->find("input[name=upload_url]")
			->attr("value",$dom->params->path);
	if ($dom->params->ext) $out
					->find("input[name=upload_url]")
					->after("<input type='hidden' name='upload_ext' value='{$dom->params->ext}'>");
	$out->data = $dom->data;
	$out->fetch();
	$dom->replace($out);
}
?>
