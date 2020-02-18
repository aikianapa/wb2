<?php
require 'vendor/autoload.php';
mysqlConnect();

function mysqlConnect() {
    if (isset($_ENV["mysql_connection"])) return $_ENV["mongodb_connection"];
    $ini = parse_ini_file (__DIR__ ."/mysql.ini");
    $db = new MysqliDb($ini["host"], $ini["user"], $ini["pwd"], $ini["dbname"]);
    $result = $db->query("SHOW TABLES");
      $users  = $result->fetchAll();


    return $db;
}

function mysqlItemRead($form = null, $id = null) {
  if ($form == "admin") return false;
  $item = [];


  if ($item == null) wbError('func', __FUNCTION__, 1006, func_get_args());
  return $item;
}

function mysqlItemList($form = 'pages', $where = '', $sort = null)
{
  $list = array();
  if (!form) {
      wbError('func', __FUNCTION__, 1001, func_get_args());
      return array();
  }
  return $list;
}

function mysqlItemRemove($form = null, $id = null, $flush = true)
{
    if ($form == "admin") return false;
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

function mysqlItemSave($form, $item = null, $flush = true)
{
      if ($form == "admin") return false;
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

function mysqlTableFlush($form)
{
    // Сброс кэша в общий файл
    return true;
}

function mysqlTableCreate($form, $engine)
{
  if ($form == "admin") return false;
  if ($engine) return jsonTableCreate($form, $engine);
  if ($form) {
  } else {
      wbError('func', __FUNCTION__, 1002, func_get_args());
  }
}

function mysqlTableRemove($form, $engine) {
      if ($form == "admin") return false;
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

function mysqlTableList($engine = false)
{
    if ($engine) return jsonTableList(true);
    $list = [];
    return $list;
}


?>
