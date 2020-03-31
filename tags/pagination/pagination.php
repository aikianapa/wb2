<?php
function tagPagination(&$dom)
{
    if ($dom->hasClass(".pagination")) return;

    ini_set('max_execution_time', 900);
    ini_set('memory_limit', '1024M');
    $pages = ceil($dom->params->count/$dom->params->size);
    $page = $dom->page;

    if (!$page) $page = 1;

    $dom->pages = $pages;
    $foreach = $dom->params;
    $tplId = md5(json_encode($foreach));
    $foreach->route = $dom->app->vars("_route");
    $dom->attr("data-wb-pages", $pages);
    if ($dom->params->tpl !== "false") $dom->attr("data-wb-tpl", $tplId);
    $tpl = $dom->foreach->outerHtml();
    $class="ajax-".$tplId;

    /*
        if (is_object($dom->parent("table")) && $dom->parent("table")->find("thead th [data-wb-sort]")->length) {
                $dom->parent("table")->find("thead")->attr("data-wb-size",$size);
                $dom->parent("table")->find("thead")->attr("data-wb",$class);
            }
    */
    if ($pages>0 or $dom->params->sort>"") {
        $pag=$dom->app->fromFile(__DIR__ . "/pagination_ui.php");
        //$pag->wrapInner("<div></div>");
        $step=1;
        $flag=floor($page/10);
        if ($flag<=1) {
            $flag=0;
        } else {
            $flag*=10;
        }
        //$inner="";
        $pagination=array("id"=>$class,"size"=>$size,"count"=>$count,"cache"=>$cacheId,"find"=>$find,"pages"=>array());
        if (!isset($_ENV["route"]["params"]["form"]) or $_ENV["route"]["params"]["form"]=="") {
            $form=$tplId;
        } else {
            $form=$_ENV["route"]["params"]["form"];
        }

        $pagarr=_tagPaginationArr($page, $pages);

        foreach ($pagarr as $i => $p) {
            $pn=$p;
            if ($p=="..." and $pagarr[$i-1]<$page) {
                $pn=intval($page/2);
            }
            if ($p=="..." and $pagarr[$i-1]>=$page) {
                $pn=intval($page+($pages-$page)/2);
            }


            $href=$_ENV["route"]["controller"]."/".$_ENV["route"]["mode"]."/".$form."/".$pn;
            $pagination["pages"][$i]=array(
                                             "page"=>$p,
                                             "href"=>$href,
                                             "flag"=>$flag,
                                             "data"=>"{$class}-{$pn}"
                                         );
        }

        $more = explode(":",$dom->params->more);

        $pag->data = $pagination ;
        $pag->setAttributes();
        $pag->fetch();
        $pag->find("[data-page={$page}]")->addClass("active");
        $pag->find("ul")->attr("data-wb-route", json_encode($_ENV["route"]));
        if ($more[0] > "") {
            $pag->find("[data-page='next']")->remove();
            $pag->find("[data-page='prev']")->remove();
            $pag->find("[data-page!='more']")->css("display","none");
            if (isset($more[1]) && $more[1]>" ") $pag->find("[data-page='more'] .page-link")->html($more[1]);
            if ($more[0] !== "true") $pag->find("[data-page='more']")->attr("data-trigger",$more[0])->css("display","none");
        } else {
            $pag->find("[data-page='more']")->remove();
        }


        if ($pages < 2) {
            $style=$pag->find("ul")->attr("style");
            $pag->find("ul")->attr("style", $style.";display:none;");
        }
        if ($dom->is("tbody")) {
            $target = $dom->closest("table");
        } else {
            $target = &$dom;
        }

        if ($dom->params->more == "" and ($dom->params->pos == "top" or $dom->params->pos == "both")) {
            $target->before($pag);
        }
        if ($dom->params->pos !== "top") {
            $target->after($pag);
        }
    }
    $dom->find("[data-page='{$page}']")->addClass("active");
    $dom->removeAttr("data-wb");
    $dom->append("
        <script type='wbapp' removable>
        wbapp.loadScripts(['/engine/tags/pagination/pagination.js'],'pagination-js');
        </script>
        <template id='{$tplId}' data-params='".json_encode($foreach)."'>
            $tpl
        </template>
        ");
    return $dom;
}


    function _tagPaginationArr($c, $m)
    {
        $current = $c;
        $last = $m;
        $delta = 4;
        $left = $current - $delta;
        $right = $current + $delta + 1;
        $range = array();
        $rangeWithDots = array();
        $l = -1;

        for ($i = 1; $i <= $last; $i++) {
            if ($i == 1 || $i == $last || $i >= $left && $i < $right) {
                array_push($range, $i);
            }
        }

        for ($i = 0; $i<count($range); $i++) {
            if ($l != -1) {
                if ($range[$i] - $l === 2) {
                    array_push($rangeWithDots, $l + 1);
                } elseif ($range[$i] - $l !== 1) {
                    array_push($rangeWithDots, '...');
                }
            }

            array_push($rangeWithDots, $range[$i]);
            $l = $range[$i];
        }

        return $rangeWithDots;
    }

    function ajax__pagination()
    {
        $app = new wbApp();
        if ($app->vars("_post.params.route")) {
            $app->vars("_route", $app->vars("_post.params.route"));
        }

        $res=array();
        foreach ($_POST as $key =>$val) $$key=$val;

        if (!isset($page)) $page=1;
        if (!isset($find)) $find='';


        $tpl = $app->FromString($tpl, true);
        $fe = $tpl->children(".wb-html")->children(":first-child");
        $fe->attr("data-wb-page", $page);
        $tpl->fetch();
        $tpl->find(".pagination[id]")->attr("id", "ajax-".$tplid);
        $fe = $tpl->children(".wb-html")->children(":first-child");
        $res["data"]=$fe->html();
        $res["pages"]=$pages;
        return json_encode($res);
    }
