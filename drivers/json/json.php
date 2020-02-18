<?php
function jsonItemRead($form = null, $id = null) {
  $file = jsonTable($form);
  if (isset($_ENV['cache'][md5($file.$_ENV["lang"].$_SESSION["lang"])][$id])) {
      $item = $_ENV['cache'][md5($file.$_ENV["lang"].$_SESSION["lang"])][$id];
  } else {
      $list = wbItemList($form);
      if (isset($list[$id])) {
          $item = $list[$id];
      } else {
          wbError('func', __FUNCTION__, 1006, func_get_args());
          $item = null;
      }
  }
  return $item;
}

function jsonItemList($form = 'pages', $where = '', $sort = null)
{
  if (is_string($where)) {
      $where = $cWhere = wbSetValuesStr($where);
  } else {
      $cWhere=null;
  }
  $list = array();
  $file = jsonTable($form);
  if (!is_file($file)) {
      wbError('func', __FUNCTION__, 1001, func_get_args());
      return array();
  }
  if ($cWhere !== null and isset($_ENV['cache'][md5($file.$where.$sort.$_ENV["lang"].$_SESSION["lang"])])) {
      $list = $_ENV['cache'][md5($file.$where.$sort.$_ENV["lang"].$_SESSION["lang"])];
  } else {
      $list = wb_file_get_contents($file);
      $list = json_decode($list, true);
      if ((array)$list === $list) {
          foreach ($list as $key => $item) {
              $item['_form'] = $item['_table'] = $form;
              if (isset($item['images']) && $_ENV["route"]["mode"]!=="edit") {
                  $item = wbImagesToText($item);
              }
              $item = wbTrigger('form', __FUNCTION__, 'AfterItemRead', func_get_args(), $item);
              if (
          ('_' == substr($item['id'], 0, 1) and 'admin' !== $_SESSION['user_role'])
          or
          (null == $item)

          or (isset($item['_removed']) and true == $item['_removed'])
      ) {
                  unset($list[$key]);
              } elseif (is_string($where) and $where > '' and !wbWhereItem($item, $where)) {
                  unset($list[$key]);
              } elseif (!is_string($where)) {
                  // не реализовано
          //call_user_func_array($where,$list[$key]);
          //unset($list[$key]);
              } else {
                  $list[$key] = $item;
              }
          }
      }
  }

  if (!is_array($list)) $list = array();
  if (null !== $sort) $list = wbArraySortMulti($list, $sort);
  if ($cWhere !== null)  $_ENV['cache'][md5($file.$where.$sort.$_ENV["lang"].$_SESSION["lang"])] = $list;
  return $list;
}

function jsonItemRemove($form = null, $id = null, $flush = true)
{
    $form = jsonTable($form);
    if (!is_file($form)) {
        wbError('func', __FUNCTION__, 1001, func_get_args());
        return null;
    }
    if (is_array($id)) {
        foreach ($id as $iid) $res = wbItemRemove($form, $iid, false);
        if ($flush==true) wbTableFlush($form);
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

function jsonItemSave($form, $item = null, $flush = true)
{
      $item = wbItemInit($form, $item);
      $file = jsonTable($form);
      $res = null;
      if (!is_file($file)) {
          wbError('func', __FUNCTION__, 1001, func_get_args());
          return null;
      }

      if (!isset($_ENV['cache'][md5($file.$_ENV["lang"].$_SESSION["lang"])])) {
          $_ENV['cache'][md5($file.$_ENV["lang"].$_SESSION["lang"])] = array();
      }
      if (!isset($item['id']) or '_new' == $item['id'] or $item['id'] == "") {
          $item['id'] = wbNewId();
      }

      $_ENV['cache'][md5($file.$_ENV["lang"].$_SESSION["lang"])][$item['id']] = $item;
      wbTrigger('form', __FUNCTION__, 'AfterItemSave', func_get_args(), $item);
      $res = $item;
      if ($flush == true) wbTableFlush($form);
      return $res;
}

function jsonTableFlush($form)
{
    // Сброс кэша в общий файл
    $res = false;
    $form = jsonTable($form);
    $tname = jsonTableName($form);
    $cache = $_ENV['cache'][md5($form.$_ENV["lang"].$_SESSION["lang"])];
    if (is_file($form) and isset($_ENV['cache'][md5($form.$_ENV["lang"].$_SESSION["lang"])])) {
        $fp = fopen($form, 'rb');
        flock($fp, LOCK_SH);
        $data = file_get_contents($form);
        if (substr($data,0,1)=="{") {
            $data = json_decode($data,true);
        } else {
            $data=unserialize($data);
        }
        $flag = false;
        foreach ($cache as $key => $item) {

            $item['_table'] = $tname;
            if (isset($data[$key])) {
                $data[$key]=array_merge($data[$key],$item);
            } else {
                $data[$key]=$item;
            }
            $flag = true;
            if (isset($item['_removed']) and true == $item['_removed']) {
                if (wbRole('admin')) {
                    unset($data[$key]);
                }
            }
        }
        if (isset($_ENV["settings"]["format"]) AND $_ENV["settings"]["format"]=="serialize") {
            $data = serialize($data);
        } else {
            $data = wbJsonEncode($data);
        }

        flock($fp, LOCK_UN);
        fclose($fp);
        if ($flag) {
            $res = file_put_contents($form, $data, LOCK_EX);
            wbLog('func', __FUNCTION__, 1009, func_get_args());
        } else {
            $res = null;
        }
        unset($_ENV['cache'][md5($form.$_ENV["lang"].$_SESSION["lang"])]);
    }

    return $res;
}

function jsonTable($form = 'pages', $engine = false)
{
    wbTrigger('func', __FUNCTION__, 'before');
    $create = false;
    if (strpos($form, ":")) {
        $form=explode(":", $form);
        if ($form[1]=="engine" or $form[1]=="e") {
            $engine=true;
        } elseif ($form[1]=="create" or $form[1]=="c") {
            $create = true;
        }
        //$form=$form[0];
        $form=$form[1];
    }

    if (substr($form, 0, strlen($_ENV['dbe'])) == $_ENV['dbe']) {
        $engine = true;
    }
    if (false == $engine) {
        $db = $_ENV['dba'];
    } else {
        $db = $_ENV['dbe'];
    }
    $tname = jsonTableName($form);
    $form = jsonTablePath($tname, $engine);
    if (!is_file($form)) {
        if ($tname > '' OR $create == true) {
            wbTableCreate($tname);
        }
    }
    if (!is_file($form)) {
        wbError('func', __FUNCTION__, 1001, func_get_args());
        $form = null;
    } else {
        $_ENV[$form]['name'] = $tname;
    }
    wbTrigger('func', __FUNCTION__, 'after', func_get_args(), $form);

    return $form;
}

function jsonTableCreate($form, $engine) {

  if (false == $engine) {
      $db = $_ENV['dba'];
  } else {
      $db = $_ENV['dbe'];
  }
  $form = jsonTablePath($form, $engine);
  if (!is_file($form) and is_dir($db)) {
      $json = wbJsonEncode(null);
      $res = file_put_contents($form, $json, LOCK_EX);
      if ($res) {
          @chmod($form, 0766);
      } else {
          $form = null;
      }
  } else {
      wbError('func', __FUNCTION__, 1002, func_get_args());
  }
}

function jsonTableRemove($form, $engine) {
      if (wbRole('admin')) {
            $cache = jsonTableCachePath($form, $engine);
            $form = jsonTablePath($form, $engine);
            wbRecurseDelete($cache);
            if (is_file($form)) {
                wbRecurseDelete($cache);
                unlink($form);
                if (is_file($form)) { // не удалилось
                    wbError('func', __FUNCTION__, 1003, func_get_args());
                }
                $res = $form;
            } else { // не существует
                wbError('func', __FUNCTION__, 1001, func_get_args());
            }
      }
}


function jsonTableCachePath($form = 'data', $engine = false)
{
    if (false == $engine) {
        $db = $_ENV['dbac'];
    } else {
        $_ENV['dbec'];
    }
    $form = $db.'/'.$form;
    return $form;
}

function jsonTableList($engine = false)
{
      if (false == $engine) {
          $db = $_ENV['dba'];
      } else {
          $db = $_ENV['dbe'];
      }
      $list = wbListFiles($db);
      foreach ($list as $i => $form) {
          $tmp = explode('.', $form);
          if ('json' !== array_pop($tmp)) {
              unset($list[$i]);
          } else {
              $list[$i] = substr($form, 0, -5);
          }
      }
      return $list;
}

function jsonTablePath($form = 'data', $engine = false)
{
    if (false == $engine) {
        $db = $_ENV['dba'];
    } else {
        $db = $_ENV['dbe'];
    }
    $file = $db.'/'.$form.'.json';
    wbTrigger('func', __FUNCTION__, 'after', func_get_args(), $file);
    return $file;
}

function jsonTableName($form)
{
    $form = explode('/', $form);
    $form = array_pop($form);
    $form = str_replace('.json', '', $form);
    if (strpos($form, ":")) {
        $form=explode(":", $form);
        $form=$form[0];
    }
    return $form;
}


?>
