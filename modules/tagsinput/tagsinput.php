<?php
    function tagsinput__init(&$dom) {
        if ($dom->attr("data-wb") == "") return;
        if (!$dom->app) $dom->app = new wbApp();
        $name = $dom->attr("name");
        $out=$dom->app->fromFile(__DIR__ ."/tagsinput_ui.php");
        $dom->attrsCopyTo($out);
        $out->attr("name",$name);

        $out->attr("value",$value);
        $dom->after($out);
        $dom->remove();
    }

?>
