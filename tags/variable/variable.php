<?php
function tagVariable(&$dom,$__Item=[]) {

    foreach($dom->params as $__var => $__val) {
        $$__var = $__val;
    }

    foreach($dom->attributes as $__atr) {
        if (!strpos(" ".$__atr->name,"data-wb")) {
            $__v = $__atr->name;
            $$__v = $__atr->value;
        }
    }

    if (!$var OR $var == "") return;
    if (isset($_ENV["variables"][$var])) {unset($_ENV["variables"][$var]);}
		if (!isset($where) OR (isset($where) AND wbWhereItem($__Item,$where)) OR isset($if)) {
			if (!isset($if) OR (isset($if) AND wbWhereItem($__Item,$if))) {
				$_ENV["variables"][$var]=$value;
			} else {
				$_ENV["variables"][$var]=$else;
			}
			if (isset($_ENV["variables"][$var])) {
				$__tmp=substr($_ENV["variables"][$var],0,1);
				if ($__tmp=="{" OR $__tmp=="]" AND is_array(json_decode($_ENV["variables"][$var],true))) {
					$__array=json_decode($_ENV["variables"][$var],true);
					if ((array)$__array === $__array) $_ENV["variables"][$var]=$__array;
				}
			}

			if (isset($__oconv) AND $__oconv>"") {
				$_ENV["variables"][$var]=wbOconv($_ENV["variables"][$var],$__oconv);
			}
		} else {
			$dom->remove();
		}
		if ($hide!=="false") $dom->remove();
    }
