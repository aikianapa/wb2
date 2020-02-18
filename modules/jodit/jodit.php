<?php
use Adbar\Dot;
$app->addEditor("jodit",__DIR__,"Jodit editor");
function jodit__init(&$dom) {
	if (isset($_ENV["route"]["params"][0]) AND $_ENV["route"]["mode"] !== "tree_getform") {
		$mode=$_ENV["route"]["params"][0];
		$call="jodit__{$mode}";
		if (is_callable($call)) {$out=@$call();}
		die;
	} else {
		$out = $dom->app->fromFile(__DIR__ ."/jodit_ui.php",true);
    $id = "jd-".$dom->app->newId();
		$textarea = $out->find(".jodit");
		$textarea->attr("id",$id);
    $ats = $dom->attributes();
    foreach( $ats as $at => $val) {
        if (!strpos(" ".$at,"data-wb")) {
            $textarea->attr($at,$val);
        }
    }

        $out->setValues($dom->data);
        $out->fetch();
				if ($dom->params->value) {
						$item = new Dot();
						$item->setReference($dom->data);
						$out->find(".jodit")->html($item->get($dom->params->value));
				}
        $out->find(".jodit")->append($dom->html());

        return $out->outerHtml();
	}
}
?>
