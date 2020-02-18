<?php
  function tagSave(&$dom) {
        $params = $dom->params;
        unset($params->role);
        if (!$params->method OR !in_array(strtolower($params->method),["get","post"])) {
            $params->method = "ajax";
        } else {
            $dom->closest("form")->attr("method",$params->method);
        }
        if (!$dom->attr("id")) $id = "sv-".$dom->app->newId();
        $dom->attr("id",$id);
        $callback = "wbapp.save($(this),".json_encode($params).");";

        if (!$dom->is("[contenteditable]") && !$dom->is(":input")) {
          if ($params->method == "ajax") $callback .= "return false;";
          $dom->attr("onClick",$callback);
        } else if ($dom->is(":input")) {
            $callback = "wbapp.save($(this),".json_encode($params).");";
            $dom->attr("onChange",$callback);
        } else {
          $dom->addClass("contenteditable");
          $params->editor_id = $id;
          $callback = "wbapp.save($(this),".json_encode($params).");";
          if ($dom->is(":input") || $params->editor>"") {
              $dom->attr("onChange",$callback);
          } else {
              $dom->attr("onBlur",$callback);
          }
          if ($params->editor) {
              $dom->addClass($params->editor)->removeAttr("contenteditable");
              $scripts = '["/engine/modules/'.$params->editor.'/'.$params->editor.'.js"]';
              $dom->append('<script type="wbapp">wbapp.loadScripts('.$scripts.',"'.$params->editor.'-js");</script>');
          }
        }
        $dom->removeAttr("data-wb");
        $dom->addClass("wb-done");
  }

  function tagSave_Remove(&$app) {
    $method = $app->vars("_route.params.0");
    $params = (object)$app->vars->get("_{$method}.params");
    $form    = $params->form;
    $item    = $params->item;
    $res = $app->itemRemove($form,$item);
    echo json_encode(["error"=>false]);
    die;
  }

  function ajax__save($form=null)
  {
      $app = new wbApp();
      $method = $app->vars("_route.params.0");
      $params = (object)$app->vars->get("_{$method}.params");
      if (!$params->item) $params->item = $app->newId();
      $data    = $app->vars("_{$method}.data");
      $data_id = $app->vars("_{$method}.data.id");
      if (!$data_id OR $data_id == "" OR $data_id == "_new") {
          $data_id = $params->item;
          if ((array)$data === $data) $data["id"] = $data_id;
          $app->vars("_{$method}.data.id",$params->item);
      }

      $form    = $params->form;
      $item    = $params->item;

      if ($params->remove == "true") tagSave_Remove($app);

      $source = $app->itemRead($form,$item);
      if ($source) {$new = false;} else {$new = true;}

      if (!$source) $source = [];

      if ($params->field) {
          $app->vars("data",[]);
          $app->vars("data.".$params->field,$data);
          $data = array_replace_recursive($source,$app->vars("data"));
      } else if ($data_id > "" && $data_id !== $item) {
          $check = $app->itemRead($form,$data_id);
          if ($check) return json_encode(["error"=>true,"msg"=>"Item {$data_id} exist"]);
          $data = array_replace_recursive($source,$app->vars("_post.data"));
          $params->rename = true;
      }

      $eFunc="{$form}__formsave";
      $aFunc="{$form}_formsave";
      if (is_callable($aFunc)) {
          $ret=$aFunc();
      } elseif (is_callable($eFunc)) {
          $ret=$eFunc();
      } else {
          if ($params->rename == true) {
              $data["id"] = $item;
              $data["_removed"] = false;
              $res=$app->itemSave($form, $data);
              $app->itemRename($form, $item, $data_id);
              $data["id"] = $data_id;
          } else {
              $res=$app->itemSave($form, $data);
          }
          $app->tableFlush($form);
          $ret=array();
          if (isset($_GET["copy"])) {
              $old=str_replace("//", "/", $_ENV["path_app"]."/uploads/{$form}/{$_GET["copy"]}/");
              $new=str_replace("//", "/", $_ENV["path_app"]."/uploads/{$form}/{$_GET["item"]}/");
              recurse_copy($old, $new);
          }
          if ($res) {
              $ret = ["error"=>0,"id"=>$res,"new"=>$new];

          } else {
              $ret["error"]=1;
              $ret["text"]=$res;
          }
      }
      return json_encode($ret);
  }

?>
