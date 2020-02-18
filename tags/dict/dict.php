<?php
    function tagDict(&$dom) {
        if (!$dom->app) $dom->app = new wbApp();
        $out = $dom->app->fromFile( __DIR__ ."/dict_ui.php");
        $out->data = $dom->data;
        $out->app = $dom->app;
        $name = $dom->attr("name");
        $out->find(".wb-dict")->attr("name",$name);
        $out->fetch();
        $dom->removeAttr("data-wb");
        $dom->html($out);
    }

?>
