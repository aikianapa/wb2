<?php
function module__controller(&$app) {
	wbTrigger("func",__FUNCTION__,"before");
	$call=__FUNCTION__ ."__".$_ENV["route"]["mode"];
	if (is_callable($call)) {
		$app->dom = $call($app);
	} else {
		module__controller__init($app);
	}
	wbTrigger("func",__FUNCTION__,"after");
	return $app->dom;
}

function module__controller__init(&$app) {
 	$module = $app->vars->get("_env.route.mode");
	if ($app->vars->get("_env.route.name")) $module = $app->vars->get("_env.route.name");
	$aModule=$_ENV["path_app"]."/modules/{$module}/{$module}.php";
	$eModule=$_ENV["path_engine"]."/modules/{$module}/{$module}.php";
	if (is_file($aModule)) {include_once($aModule);} elseif (is_file($eModule)) {include_once($eModule);}
	$out = null;
	$call=$module."_init"; if (is_callable($call)) {$out=@$call($app);}
	$call=$module."__init"; if (is_callable($call)) {$out=@$call($app);}

	if ($out!==null) {
			$dom=$out;
	} else {
			$dom = wbPage404($app);
			//$dom = $app->fromString($_ENV['sysmsg']['err_mod_lost'].": [{$module}]",true);
	}
	if (!is_object($dom)) {$dom = $app->fromString($dom);}
	$app->dom = $dom;
	return $dom;
}


?>
