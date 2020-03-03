<?php
function qrscan__init(&$dom) {
    if (is_a($dom,"wbdom")) {
      $out = $dom->app->fromFile(__DIR__ ."/qrscan_ui.php");
      $out->data = $dom->data;
      $out->data["_base"] = $_ENV["modules"]["qrscan"]["dir"]."/";
      $out->fetch();
      if ($dom->is("input")) {
          $dom->replace($out);
      }
    } else {
      // вызов из url
    }


}

?>
