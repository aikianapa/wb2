<?php
function smartid__init(&$dom) {
    if (!$dom->is("input")) {
        $dom->replace("<span class='form-control text-danger'>SmartID Error: required &lt;input&gt; tag</span>");
        return;
    }
    $dom->addClass("wb-smartid");
    $dom->attr("required","true");
    $dom->removeAttr("data-wb");

    $dom->after('<script type="wbapp">wbapp.loadScripts(["/engine/modules/smartid/smartid.js"],"smartid-js");</script>');
}
?>
