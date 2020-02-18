<?php
function datetimepicker__init(&$dom)
{
    $out=$dom->app->fromFile(__DIR__ ."/datetimepicker_ui.php");
    $inph = $out->children("input[type=hidden]");
    $inpv = $inph->next("input");
    $attributes = $dom->attributes();
    if ($dom->params->type > "") {$attributes["type"] = $dom->params->type;}
    foreach($attributes as $attr => $val) {
        $attr = mb_strtolower($attr);
        if (substr($attr,0,7) !== "data-wb") {
            if ($attr == "class") {
                $inpv->addClass($val);
            } else if ($attr == "style") {
                $inpv->attr("style",trim($inpv->attr("style")." ".$val));
            } else {
                $inpv->attr($attr,$val);
                if (in_array($attr,["name","value"])) $inph->attr($attr,$val);
            }
        }
    }
    $dom->replace($out);
}
?>
