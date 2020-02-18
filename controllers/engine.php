<?php
function engine__controller(&$app)
{
    wbTrigger("func", __FUNCTION__, "before");
    $call=__FUNCTION__ ."_".$_ENV["route"]["mode"];
    if (is_callable($call)) {
        $app->dom=$call($app);
    } else {
        echo __FUNCTION__ .": {$_ENV['sysmsg']['err_func_lost']} ".$call."()";
        die;
    }
    wbTrigger("func", __FUNCTION__, "after");
    return $app->dom;
}

function engine__controller_include()
{
    include_once($_ENV["path_app"].$_SERVER["SCRIPT_NAME"]);
    die;
}
