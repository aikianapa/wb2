<?php
    function tagInclude(&$dom) {
        if (!$dom->app) $dom->app = new wbApp();
        $res=0;

        foreach(["snippet","form","modal","editor","source","gallery","template","module","file","url"] as $val) {
            if ($dom->params->$val > "") $__include = $val;
        }

        $types = ["form","file","snippet","template","url"];
        foreach($dom->params as $param) {
            if ($param > "" AND in_array($param,$types))
            {
                $src = $dom->params->$param;
                $__include = $param;
                break;
            }
        }
        switch($__include) {
        case "url":
            $url = $dom->app->vars("_route.hostp").$dom->params->url;
            $out = $dom->app->fromFile($url);
            $out->data = $dom->data;
            $out->fetch();
            $dom->replace($out);
            return $dom;
            break;
        case "file":
            if (!$dom->attr("src")) {
                $file = $dom->params->file;
            } else {
                $file = $dom->attr("src");
            }
            if (!is_file($file)) {$file = $_ENV["dir_app"].$file;}
            $dom->removeAttr("data-wb");
            $out = $dom->app->fromFile($file);
            $out->data = $dom->data;
            $out->fetch();
            tagIncludeContent($dom,$out);
            $dom->html($out);
            return $dom;
            break;
        case "form":
            $form=$dom->params->form;
            $mode=$dom->params->mode;
            if (!$mode) {
                $arr=explode("_",$dom->params->form);
                if (isset($arr[0])) $form=$arr[0];
                if (isset($arr[1])) {
                    unset($arr[0]);
                    $mode=implode("_",$arr);
                }
            }
            $engine=explode(":",$form);
            if (isset($engine[1]) AND ($engine[0]=="engine" OR $engine[0]=="e")) {
                $form=$engine[1];
                $engine=true;
            } else {
                $engine=false;
            }
            $out=$dom->app->getForm($form,$mode,$engine);
            if (!is_object($out)) $out=$dom->app->fromString("Include: {$form} {$mode}",true);
            $out->data = $dom->data;
            $out->fetch();
            tagIncludeContent($dom,$out);
            $dom->removeAttr("data-wb");
            if ($dom->is("meta")) {
                $dom->replace($out);
            } else {
                $dom->html($out->outerHtml());
            }
            return $out;
            break;
        case "snippet":
                // ======================
            $out=$dom->app->getForm("snippets",$dom->params->snippet);
            $dom->attrsCopyTo($out);
            $out = $dom->app->fromString($out->outerHtml(),true);
            $out->data = $dom->data;
            $out->fetch();
            tagIncludeContent($dom,$out);
            $out = $out->html();
            $dom->removeAttr("data-wb");
            $dom->replace($out);
            return $out;
            break;
        case "template":
                // ======================
            $out=$dom->app->getTpl($dom->params->template);
            $out->data = $dom->data;
            $out->fetch();
            tagIncludeContent($dom,$out);
            $dom->removeAttr("data-wb");
            if ($dom->tag() == "meta") {
                $dom->replace($out->outerHtml());
            } else {
                $dom->html($out->outerHtml());
            }
            return $out;
            break;
        }

        $out = "<br>================ include ================<br>";
        $dom->html($out);
        //return $out;
    }

    function tagIncludeContent(&$dom,&$out) {
      if (trim($dom->html()) == "") return;
      if ($dom->params->content == "before") {
          $out->prepend($dom->html());
      } else if ($dom->params->content == "after") {
          $out->append($dom->html());
      } else {
          $out->find(".modal-body")->append($dom->html());
      }
    }
?>
