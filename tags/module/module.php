<?php
    function tagModule(&$dom,$Item=array()) {
        $load = $dom->params->load;
        if ($dom->app->vars("_sett.modcheck") == "on" && $dom->app->vars("_sett.modules.{$load}.active") !== "on") {
            $dom->attr("data-error","Module disabled");
            return;
        }
        $linetags=["meta","link","br","hr","input"];
        $attributes = [];
        foreach($dom->attributes as $__atr) {
            if (!strpos(" ".$__atr->name,"data-wb")) {
                $attributes[$__atr->name] = $__atr->value;
            }
        }

        $e=$_ENV["path_engine"]."/modules/{$load}/{$load}.php";
        $a=$_ENV["path_app"]."/modules/{$load}/{$load}.php";
        if (is_file($e)) require_once($e);
        if (is_file($a)) require_once($a);

        $call=$load."_init"; if (is_callable($call)) {$out=@$call($dom);}
        $call=$load."__init"; if (is_callable($call)) {$out=@$call($dom);}

        if ($dom->tag() == "style") {
            $dom->html($out);
            $dom->removeAttr("data-wb");
            return $dom;
        }
        if (!is_object($out)) $out=$dom->app->fromString($out);

        $func=$load."_afterRead";
        $_func=$load."__afterRead";
        // функция вызывается после получения шаблона модуля
        if (is_callable($func)) {
            $out = @$func($dom);
        } else if (is_callable($_func)) {
            $out = @$_func($dom);
        }
        $attrs = $dom->attributes;
        if ($attrs->length) {
            foreach($attrs as $attr) {
                if ($attr->name !== "data-wb") {
                    $out->children(":first-child")->attr($attr->name,$attr->value);
                }
            }
        }

        if ($dom->params->hide == "true" OR in_array($dom->tagName,$linetags)) {
            foreach($attributes as $atr => $val) {
                if ($atr == "class") {
                    $out->children(":first-child")->addClass($val);
                } else {
                    $out->children(":first-child")->attr($atr,$val);
                }
            }
        }

        $out->fetch($Item);

        $func=$load."_beforeShow";
        $_func=$load."__beforeShow";
        // функция вызывается перед выдачей модуля во внешний шаблон
        if (is_callable($func)) {
            $func($out,$Item);
        } else if (is_callable($_func)) {
            $_func($out,$Item);
        }
        $out->fetch($Item);
        if ($dom->params->hide == "true") {$dom->addClass("wb-out-inner");}
        if (in_array($dom->tagName,$linetags)) {
            $dom->after($out);
        } else {
            $dom->html($out);
        }
        if ($dom->params->hide == true) {$dom->addClass("wb-out-inner");}
    }
?>
