<?php
use Adbar\Dot;
$app->addEditor("summernote",__DIR__,"Summernote editor");
function summernote__init(&$dom) {
	if (isset($_ENV["route"]["params"][0]) AND $_ENV["route"]["mode"] !== "tree_getform") {
		$mode=$_ENV["route"]["params"][0];
		$call="summernote__{$mode}";
		if (is_callable($call)) {$out=@$call();}
		die;
	} else {
		$out = $dom->app->fromFile(__DIR__ ."/summernote-ui.php",true);
		$textarea = $out->find(".summernote");
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

						$out->find("textarea")->html($item->get($dom->params->value));
				}
        $out->addClass("wb-done");
				return $out;
	}
}
?>
