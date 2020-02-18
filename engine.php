<?php
$sessdir = $_SERVER["DOCUMENT_ROOT"]."/uploads/_sess/";
if (!is_dir($sessdir)) mkdir($sessdir,0755,true);
ini_set('display_errors', 1);
session_start([
    "save_path" => $sessdir,
    "gc_probability" => 5,
    "gc_divisor" => 80,
    "gc_maxlifetime" => 100,
    "cookie_lifetime" => 100
]);
ob_start();
require_once __DIR__."/functions.php";
$app = new wbApp();
if (!$app->cache["check"]) {
    $app->data = array();
    $exclude  = in_array($app->vars->get("_route.controller"),array("module","ajax","thumbnails"));
	if ($app->vars("_route.form") !== "default_form") {
		$app->dom = $app->LoadController();
	} else {
		if (!is_dir($_ENV["path_app"]."/form")) {$app->dom = $app->LoadController();} else {
			$app->dom = $app->FromString(wbErrorOut(404));
		}
	}
	if (!is_object($app->dom)) {$app->dom=$app->fromString($app->dom,true);}
  $app->dom->fetchTargets();
	//if ($_ENV["route"]["controller"]!=="module") {
		// чтобы вставки модуля не пометились .wb-done
	//	$app->dom->fetch($app->data,true);
		//$hide=$app->dom->find("[data-wb-hide]");
		//foreach($hide as $h) {$h->tagHideAttrs();}
	//}
	//$app->dom->wbTargeter();
	//$app->dom->wbClearClass();

  $app->dom->find("[append]","[prepend]","[after]","[before]","[html]","[text]")->fetchTargets();

    if (is_callable("wbBeforeOutput") AND !$exclude)  {
        $html = wbBeforeOutput();
        if (is_object($html)) $html = $html->outerHtml();
    } else {
        $html = $app->dom->outerHtml();
    }
	if ($cache["save"]==true) wbPutContents($cache["path"],$html);

} else if ($cache["check"]===true) {
	$html=$cache["data"];
}
session_write_close();
echo $html;
ob_flush();
?>
