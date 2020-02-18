<?php
function less__init(&$dom)
{
    require __DIR__ . "/vendor/leafo/lessphp/lessc.inc.php";
    if ($dom instanceof \wbDom AND $dom->tag() == "style") {
      $lessc = new lessc;
      $less = wbSetValuesStr($dom->innerHtml(),$dom->data);
      return $lessc->compile($less);
    } else {
      if (isset($_ENV["route"]["params"][0])) {
          $pos = count($_ENV["route"]["params"])-1;
          $_ENV["route"]["params"][$pos] = substr($_ENV["route"]["params"][$pos],0,-3)."less";
          $less = $_ENV["path_app"]."/".implode("/",$_ENV["route"]["params"]);
          if (is_file($less)) {
              $lessc = new lessc;
              header("Content-type: text/css");
              echo $lessc->compileFile($less);
          }
      }
      die;
    }
}
?>
