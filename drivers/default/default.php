<?php
include_once __dir__ . "/../json/json.php"; // default driver

function wbItemRead($form = null, $id = null)
{
    wbTrigger('form', __FUNCTION__, 'BeforeItemRead', func_get_args(), array());

    if (null == $form) $form = $_ENV['route']['form'];
    if (null == $id) $id = $_ENV['route']['item'];
    if ($form=="" or $form==null) return;
    $call="wb".ucfirst($form)."ItemRead";
    if (is_callable($call)) $item=$call($form, $id);

    if (!isset($item)) {
        $drv = wbCallDriver(__FUNCTION__, func_get_args());
        if ($drv!==false) {
            $item=$drv["result"];
        } else {
            $item = jsonItemRead($form, $id);
        }
    }
    if (null !== $item) {
        $item["_form"] = $form;
        if (isset($item['images']) && $_ENV["route"]["mode"]!=="edit") {
            $item = wbImagesToText($item);
        }
        if (isset($item['_removed']) && 'remove' == $item['_removed']) {
            $item = null;
        } // если стоит флаг удаления, то возвращаем null
        $item = wbTrigger('form', __FUNCTION__, 'AfterItemRead', func_get_args(), $item);
    } else {
        $item = wbTrigger('form', __FUNCTION__, 'EmptyItemRead', func_get_args(), $item);
    }
    return $item;
}

function wbItemList($form = 'pages', $where = '', $sort = null)
{
    ini_set('max_execution_time', 900);
    ini_set('memory_limit', '1024M');
    wbTrigger('form', __FUNCTION__, 'BeforeItemList', func_get_args(), array());


    $call="wb".ucfirst($form)."ItemList";
    if (is_callable($call) and $call !== __FUNCTION__) {
        $list=$call($form, $where, $sort);
    }
    if (!isset($list)) {
        $drv=wbCallDriver(__FUNCTION__, func_get_args());
        if ($drv!==false) {
            $list = $drv["result"];
        } else {
            $list = jsonItemList($form, $where, $sort);
        }
    }
    $list = wbTrigger('form', __FUNCTION__, 'AfterItemList', func_get_args(), $list);
    $list = wbTrigger('func', __FUNCTION__, 'after', func_get_args(), $list);

    return $list;
}

function wbItemRemove($form = null, $id = null, $flush = true)
{
    $res = true;
    $drv=wbCallDriver(__FUNCTION__, func_get_args());
    if ($drv!==false) {
        $res = $drv["result"];
    } else {
        $res =  jsonItemRemove($form, $id, $flush);
    }
    //if (!$res) {wbError('func', __FUNCTION__, 1007, func_get_args());}
    wbTrigger('form', __FUNCTION__, 'AfterItemRemove', func_get_args(), $item);
    return $res;
}

function wbItemRename($form = null, $old = null, $new = null, $flush = true)
{
    if ($new == null or $new == "") $new = wbNewId();
    $item = wbItemRead($form, $old);
    if ($item) {
        $item=wbTrigger('form', __FUNCTION__, 'BeforeItemRename', func_get_args(), $item);
        $item["id"] = $new;
        $item["_removed"] = false;
        wbItemSave($form, $item, $flush);
        $path = "{$_ENV["path_app"]}/uploads/{$form}";
        if (is_dir("{$path}/{$old}")) {
            rename("{$path}/{$old}", "{$path}/{$new}");
        }
        wbItemRemove($form, $old, $flush);
        $item=wbTrigger('form', __FUNCTION__, 'AfterItemRename', func_get_args(), $item);
        return $item;
    }
    return false;
}

function wbItemSave($form, $item = null, $flush = true)
{
    $item = wbItemInit($table, $item);
    $item = wbTrigger('form', __FUNCTION__, 'BeforeItemSave', func_get_args(), $item);
    $drv=wbCallDriver(__FUNCTION__, func_get_args());
    if ($drv!==false) {
        $item = $drv["result"];
    } else {
        $item = jsonItemSave($form, $item, $flush);
    }
    return $item["id"];
}

function wbFlushDatabase()
{
    wbTrigger('func', __FUNCTION__, 'before');
    $etables = wbTableList(true);
    $atables = wbTableList();
    foreach ($etables as $key) {
        wbTableFlush($key);
    }
    foreach ($atables as $key) {
        wbTableFlush($key);
    }
}

function wbTableFlush($form)
{
    // Сброс кэша в общий файл
    $res = false;
    wbTrigger('form', __FUNCTION__, 'BeforeTableFlush', func_get_args(), $form);
    $drv=wbCallDriver(__FUNCTION__, func_get_args());

    if ($drv !== false) {
        $res = $drv["result"];
    } else {
        $res = jsonTableFlush($form);
    }
    wbTrigger('form', __FUNCTION__, 'AfterTableFlush', func_get_args(), $form);
    return $res;
}




function wbTableCreate($form = 'pages', $engine = false)
{
    wbTrigger('func', __FUNCTION__, 'before');
    $drv=wbCallDriver(__FUNCTION__, func_get_args());
    if ($drv !== false) {
        $form = $drv["result"];
    } else {
        $form = jsonTableCreate($form, $engine);
    }

    wbTrigger('func', __FUNCTION__, 'after', func_get_args(), $form);

    return $form;
}

function wbTableRemove($form = null, $engine = false)
{
    wbTrigger('func', __FUNCTION__, 'before');
    $res = false;
    $drv=wbCallDriver(__FUNCTION__, func_get_args());
    if ($drv !== false) {
        $form = $drv["result"];
    } else {
        $form = jsonTableRemove($form, $engine);
    }
    wbTrigger('func', __FUNCTION__, 'after', func_get_args(), $form);
    return $res;
}

function wbTableExist($form)
{
    if (is_file($_ENV['dba'].'/'.$form.'.json')) {
        return true;
    }
    return false;
}


function wbTableList($engine = false)
{

    wbTrigger('func', __FUNCTION__, 'before');
    $drv=wbCallDriver(__FUNCTION__, func_get_args());
    if ($drv !== false) {
        $list = $drv["result"];
    } else {
        $list = jsonTableList($engine);
    }
    wbTrigger('func', __FUNCTION__, 'after', func_get_args(), $list);
    return $list;
}
