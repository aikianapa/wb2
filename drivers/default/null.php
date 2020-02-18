<?php
function nullItemRead($form = null, $id = null) {
  $item = [];
  if ($item == null) wbError('func', __FUNCTION__, 1006, func_get_args());
  return $item;
}

function nullItemList($form = 'pages', $where = '', $sort = null)
{
  $list = array();
  if (!form) {
      wbError('func', __FUNCTION__, 1001, func_get_args());
      return array();
  }
  return $list;
}

function nullItemRemove($form = null, $id = null, $flush = true)
{

    if (!$form) {
        wbError('func', __FUNCTION__, 1001, func_get_args());
        return null;
    }
    if (is_array($id)) {
        foreach ($id as $iid) $res = wbItemRemove($form, $iid, false);
    } elseif (is_string($id) or is_numeric($id)) {
            $item = wbItemRead($form, $id);
            if ($item == null) return $res;
            if (is_array($item)) {
                $item['_removed'] = true;
                $item=wbTrigger('form', __FUNCTION__, 'BeforeItemRemove', func_get_args(), $item);
                $_ENV['cache'][md5($form.$_ENV["lang"].$_SESSION["lang"])][$id] = $item;
            }
            $res = wbItemSave($form, $item, $flush);

    }
    return $res;
}

function nullItemSave($form, $item = null, $flush = true)
{
      $item = wbItemSetTable($form, $item);
      $res = null;
      if (!$form) {
          wbError('func', __FUNCTION__, 1001, func_get_args());
          return null;
      }

      if (!isset($item['id']) or '_new' == $item['id'] or $item['id'] == "") {
          $item['id'] = wbNewId();
      }

      wbTrigger('form', __FUNCTION__, 'AfterItemSave', func_get_args(), $item);
      $res = true;
      return $res;
}

function nullTableFlush($form)
{
    // Сброс кэша в общий файл
    $res = false;
    return $res;
}

function nullTableCreate($form, $engine)
{
  if ($engine) return jsonTableCreate($form, $engine);
  if ($form) {
  } else {
      wbError('func', __FUNCTION__, 1002, func_get_args());
  }
}

function nullTableRemove($form, $engine) {
      if (wbRole('admin')) {
                if (!$form) { // не удалилось
                    wbError('func', __FUNCTION__, 1003, func_get_args());
                }
                $res = $form;
            if (!$form) { // не существует
                wbError('func', __FUNCTION__, 1001, func_get_args());
            }
      }
}

function nullTableList($engine = false)
{
    if ($engine) return jsonTableList(true);
    $list = [];
    return $list;
}


?>
