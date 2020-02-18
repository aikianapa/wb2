<?php
wbRouterAdd("/module/geoplugin/(:any)/(:any)","/module/geoplugin/item:$1/fld:$2");
wbRouterAdd("/module/geoplugin/(:any)","/module/geoplugin/item:$1");
$app->addModule("geoplugin",__DIR__,"Geo plugin");

function geoplugin__init(&$dom) {
  if (get_class($dom) == "wbApp") {
      $app = $dom;
  } else {
      $app = $dom->app;
  }

  $client  = @$_SERVER['HTTP_CLIENT_IP'];
  $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
  $remote  = @$_SERVER['REMOTE_ADDR'];

  if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
  elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
  else $ip = $remote;

  $call = "geoplugin__".$app->vars("_route.item");
  header('Content-Type: charset=utf-8');
  header('Content-Type: application/json');


  $ip = "31.148.111.255";
  if (is_callable($call)) {$result = @$call($ip);}

  if ($app->vars("_route.fld") > "") {
      $result = json_decode($result);
      $fldname = "geoplugin_".$app->vars("_route.fld");
      $result = json_encode(["{$app->vars("_route.fld")}" => $result->$fldname]);
  }
  echo $result;
  die;
}

function geoplugin__info($ip) {
  return file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip);
}

function geoplugin__nearby($ip) {
  return file_get_contents("http://www.geoplugin.net/extras/nearby.gp?format=json&ip=".$ip);
}
?>
