<?php
function tagAjax(&$dom) {
    $dom->removeAttr("data-wb");
    $dom->attr("data-ajax",json_encode($dom->params));
    $dom->after("<script type='wbapp'>wbapp.loadScripts(['/engine/tags/ajax/ajax.js']);</script>");
    return $dom;
}
?>
