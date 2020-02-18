<?php
    function tagMultiInput(&$dom) {
        if (!$dom->app) $dom->app = new wbApp();
        $row = $dom->app->fromFile(__DIR__ ."/multiinput_row.php")->outerHtml();
        $wrp = $dom->app->fromFile(__DIR__ ."/multiinput_wrapper.php");
        if (isset($dom->dict)) {
              $dom->html("");
              $dict = $dom->dict;
              $data = $dom->data;
              if (is_string($data)) $data=json_decode($data,true);
              $name = $dom->dict->name;
              if (isset($dom->dict->prop->multiflds)) {
                  if (is_string($data[$name])) $data[$name]=json_decode($data[$name],true);
                  if (!isset($data[$name])) $data[$name]=[[]];
                  foreach($data[$name] as $item) {
                      $wrap = $wrp->clone();
                      $fldset = "";
                      foreach($dom->dict->prop->multiflds as $multi) {
                          $field = $dom->app->fieldBuild($multi,$item);
                          if ($multi->prop->class > "") {
                              $fldset .= $field;
                          } else {
                              $fldset .= $wrap->html($field)->outerHtml();
                          }

                      }
                      $fldset = str_replace("{{template}}",$fldset,$row);
                      $template = $dom->app->fromString($fldset)->first(".wb-multiinput-row")->html();
                      $dom->append($fldset);
                  }
              } else  {
                  $fldset = "";
                  $field = $dom->app->getForm('snippets', "string");
                  $template = $field->setAttributes($dict)->outerHtml();
                  if (!isset($data[$name])) $data[$name]=[];
                  if (!((array)$data[$name] === $data[$name])) $data[$name] = json_decode($data[$name],true);
                  foreach($data[$name] as $item) {
                    $field->setAttributes($dom->dict);
                    if ((array)$item === $item) $item=wb_json_encode($item);
                    $field->attr("value",$item);
                    $fldset .= str_replace("{{template}}",$field,$row);
                  }
                  $dom->append($fldset);
                  if (!isset($template)) $template = $fldset;
              }

              $value = $data[$dict->name];
              if ($dict->prop->class > "") $dom->addClass($dict->prop->class);
              if ($dict->prop->style > "") $dom->attr("style",$dict->prop->style);

        } else {
            $name = $dom->attr("name");
            if (!isset($dom->data[$name])) {
                $value = [];
            } else {
                $value = $dom->data[$name];
            }
        }

        if (!isset($template)) $template=$dom->html();

        $template = $dom->app->fromString($template);
        $template->find(":input")->attr("value","");
        $template->find(".wb-multiinput-row:not(:first-child)")->remove();
        $template->find(".wb-value")->removeClass("wb-value");

        $tplId=wbNewId();
        $dom->attr("data-wb-tpl",base64_encode(wbSetValuesStr($template->outerHtml())));
        $dom->addClass("wb-multiinput col wb-done");

        if (!isset($dom->dict)) tagMultiInputSetData($dom,$value);
        $dom
            ->append("<textarea name='{$name}' type='json' class='wb-multiinput-data' style='display:none;'></textarea>\n\r")
            ->append('<script data-remove="multiinput-js">wbapp.loadScripts(["/engine/js/php.js","/engine/js/jquery-ui.min.js","/engine/tags/multiinput/multiinput.js"],"multiinput-js");</script>'."\n\r")
            ->removeAttr("data-wb");
    }

    function tagMultiInputSetData(&$dom,$Data=[[]]) {

        $tpl = $dom->html();
        $mtplid = $dom->attr("data-wb-tpl");
        $name = $dom->attr("name");
        $row = $dom->app->fromFile(__DIR__ ."/multiinput_row.php")->outerHtml();
        $str = "";
        if (is_array($Data)) {
            foreach($Data as $i => $item) {
                $line = $dom->app->fromString($dom->html());
                if ((array)$item === $item) {
                    $line->fetch($item);
                } else {
                    $line->find("[name='{$name}']")->attr("value",$item);
                }

                $str .= str_replace("{{template}}",$line->outerHtml(),$row);
            }
        }
        $dom->html($str);
    }

function ajax__multiinput_getform() {
    $app = new wbApp();
    if (!isset($_ENV["route"]["params"][0])) return false;
    $out = file_get_contents(__DIR__ . "/" . $_ENV["route"]["params"][0] . ".php");
    return wb_json_encode(["content"=>$out]);
}
?>
