<?php
function codemirror__init(&$dom) {
    if ($dom->hasClass("wb-done")) return $dom;
	if (isset($_ENV["route"]["params"][0])) {
		$mode=$_ENV["route"]["params"][0];
		$call="codemirror__{$mode}";
		if (is_callable($call)) {$out=@$call();}
		die;
	} else {
		$out = $dom->app->fromFile(__DIR__ ."/codemirror-ui.php");
    		$textarea = $out->find(".codemirror");
        $textarea->attr("name",$dom->params->name);
        $out->data = $dom->data;
        $out->fetch();
        $out->addClass("wb-done");
        $dom->replace($out);
	}
}
?>
