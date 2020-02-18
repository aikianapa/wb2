<?php
function tagSnippet(&$dom) {
    if (!$dom->params->load) {
      $out = $dom->app->fromFile(__DIR__ ."/snippet_ui.php",true);
    } else {
      if (substr($dom->params->load,-4) == ".php") {
          $dom->params->role = "include";
          $dom->params->snippet = $dom->params->load;
          unset($dom->params->load);
          $out = $dom->app->tagInclude($dom);
      } else {
          $out = $dom->app->fromFile(__DIR__ ."/snippet_".$dom->params->load.".php",true);
      }
    }
    if ((string)$out === $out) $out = $dom->app->fromString($out);
    if ($out) $dom->replace($out->fetch());
    $dom->addClass("wb-done");
}
