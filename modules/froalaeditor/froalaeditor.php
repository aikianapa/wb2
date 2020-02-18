<?php
use Adbar\Dot;
$app->addEditor("froalaeditor",__DIR__,"Froala editor");
$app->addModule("froalaeditor",__DIR__,"Froala editor");
function froalaeditor__init(&$dom) {
	if (!$dom->data) $dom->data = [];
	if (isset($_ENV["route"]["params"][0]) AND $_ENV["route"]["mode"] !== "tree_getform") {
		$mode=$_ENV["route"]["params"][0];
		$call="froalaeditor__{$mode}";
		if (is_callable($call)) {$out=@$call();}
		die;
	} else {
		$out = $dom->app->fromFile(__DIR__ ."/froalaeditor-ui.php",true);
    $id = $dom->app->newId();
    $out->find(".froalaeditor")->attr("id","fr-{$id}");
        $ats = $dom->attributes;
        foreach( $ats as $at ) {
            if (!strpos(" ".$at->name,"data-wb")) {
                $out->find(".froalaeditor")->attr($at->name,$at->value);
            }
        }
        $out->fetch();
				$item = new Dot();
				$item->setReference($dom->data);
				if ($dom->attr("data-value")>"") {
						$out->find("textarea")->html($item->get($dom->attr("data-value")));
				} else {
					$out->find("textarea")->html($item->get($dom->attr("name")));
				}
        $out->addClass("wb-done");
        return $out;
	}
}
?>
