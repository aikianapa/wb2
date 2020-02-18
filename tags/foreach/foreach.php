<?php
use Adbar\Dot;
function tagForeach(&$dom,$Item=null) {
        if ($dom->hasClass("wb-done")) return $dom;
        if (!$dom->app) $dom->app = new wbApp();

        $oddeven="even";
        $ndx=0;
        $n=0;
        $page=1;
        $limit = -1;
        $size=false;
        $inner="";
        $empty=$dom->children("empty")->fetch($Item)->html();
        $dom->children("empty")->remove();
        $dom->tpl = $dom->html();
        $dom->foreach = $dom->clone();
        $dom->html("");

        if ($dom->app->vars("_post._watch_route")) $dom->app->vars("_route",$dom->app->vars("_post._watch_route"));


        if ($Item == null) $Item = $dom->data;

        if ($dom->params->count) {
            $srcItem = $dom->data;
            $fcount=wbArrayAttr($dom->params->count);
            $Item=array();
            if (count($fcount) == 1) {
                $fcount[0] = intval($fcount[0]);
                for($i=1; $i<=$fcount[0]*1; $i++) {
                    $Item[$i]=$srcItem;
                    $Item[$i]["_ndx"] = $i;
                };
            }
            elseif (count($fcount) == 2) {
                if ($fcount[0]<=$fcount[1]) {
                    for($i=$fcount[0]; $i<=$fcount[1]; $i++) {
                        $Item[$i]=$srcItem;
                        $Item[$i]["_ndx"] = $i;
                    };
                } else {
                    for($i=$fcount[0]; $i>=$fcount[1]; $i--) {
                        $Item[$i]=$srcItem;
                        $Item[$i]["_ndx"] = $i;
                    };
                }
            }
            elseif (count($fcount) > 2) {
                foreach($fcount as $i) {
                    if (is_numeric($i)) {
                        $Item[$i]=[
                            "_parent" => $srcItem,
                            "_id" => $i,
                            "id" => $i
                        ];
                    }
                }
            }
        }

      if ($dom->params->call) {
          $Item = $dom->app->callFunc($dom->params->call."");
      }

// get items from table
        if (isset($dom->params->form) AND in_array($dom->params->form,$_ENV["forms"])) {
            $Item=wbItemList($dom->params->form);
        } else if (isset($dom->params->form) AND !in_array($dom->params->form,$_ENV["forms"])) {
            $Item=[];
        }
        if (isset($dom->params->table) AND in_array($dom->params->table,$_ENV["forms"])) {
            $Item=wbItemList($dom->params->table);
        } else if (isset($dom->params->table) AND !in_array($dom->params->table,$_ENV["forms"])) {
            $Item=[];
        }
// get items from array
        if ($dom->params->from) {
            $data = new Dot($Item);
            if ($data->get($dom->params->from)>"") {
                $Item = $data->get($dom->params->from);
            } else if ($dom->app->vars->get($dom->params->from)) {
                $Item = $dom->app->vars->get($dom->params->from);
            }
        } else if ($dom->params->from AND !isset($Item[$dom->params->from])) {
            $Item=[];
        }
        // get items from enum
                if ($dom->params->enum) {
                  $Item=json_decode($dom->params->enum);
                }
// sort items
        if ($dom->params->sort) $Item=wbArraySort($Item,$dom->params->sort);
// randomize items
        if ($dom->params->rand AND ($dom->params->rand=="true" OR $dom->params->rand=="1" )) shuffle($Item);
// get page size
        if ($dom->params->size AND intval($dom->params->size) > 0) $size=intval($dom->params->size);
// get page number
        if ($dom->params->page) $page=intval($dom->params->page);
        if ($dom->app->vars("_post._watch_page")) $page=intval($dom->app->vars("_post._watch_page"));
// get list limit
        if ($dom->params->limit) $limit = intval($dom->params->limit);
        $dom->data = $Item;
        if (!is_array($Item) ) $Item=(array)$Item;
        $n=0; $count = 0;

        if ($dom->app->vars("_post._watch_item")) {
            $Item = [$dom->app->vars("_post._watch_item") => $Item[$dom->app->vars("_post._watch_item")]];
        }

        $tplId = $dom->addTpl(false);
        if ($dom->attr("data-filter") > "") {
            $filter=json_decode($dom->attr("data-filter"),true);
            if (!isset($_POST["_filter"])) {
                $_POST["_filter"] = $filter;
            }
        }

        foreach($Item as $key => $val) {
            if (!((array)$val === $val)) $val = ["_value"=>$val];
              $val["_parent"] = $Item;
              $flag = true;
              if ($size) {
                  $minpos=$size*$page-($size*1)+1;
                  $maxpos=($size*$page);
              }
                  $val["_data"] = $val;
                  $val["_odev"]=$oddeven;
                  $val["_key"]=$key;
                  $val["_ndx"]=$ndx+1;
                  $val["_id"] = $val["id"] = $key;
                  wbItemBeforeShow($val);
                  if ($flag AND $dom->params->if) $flag=wbWhereItem($val,$dom->params->if);
                  if ($dom->app->vars("_post._filter") && $flag) $flag = $dom->app->filterItem($val);
                  if ($flag) {
                    $n++;
                    //echo $val["id"]." {$pages} "."\n";
                      if ($oddeven=="even") {
                          $oddeven="odd";
                      } else {
                          $oddeven="even";
                      }
                      $tmptpl=$dom->app->fromString($dom->tpl,true);
                      $tmptpl->data = $val;
                      $tmptpl->fetch();
                      $tmptpl = $tmptpl->html();
                      if (trim($tmptpl) > "" ) {
                            $count++;
                            if (!$size OR ($n>=$minpos AND $n<=$maxpos)) {
                              $inner.=$tmptpl;
                              $ndx++;
                            }
                      }
                      if ($ndx == $limit) break;
                  } else {
                      $val["_ndx"]--;
                  }

              if (isset($dom->params->limit) AND $n >= $dom->params->limit*1) break;

        }

        $dom->params->count = $count;
        if ($count==0) $inner= $empty;

        if ($dom->tag()=="select") {
            $dom->html($inner);
            if (isset($result) AND !is_array($result)) $dom->outerHtml("");
            if (isset($srcItem[$dom->attr('name')])) $dom->attr('value',$srcItem[$dom->attr('name')]);
            $plhr=$dom->attr("placeholder");
            if ($plhr>"") {
                $dom->prepend("<option value=''>$plhr</option>");
            }
        } else {
            $dom->html(wbClearValues($inner));
            if ($step>0) {
                $dom->replaceWith($steps->html());
                foreach ($dom->find(".{$tplid}") as $tid) {
                    $tid->removeClass($tplid);
                };
                unset($tid);
            } else {
                $dom->html($inner);
            }
            if ($dom->attr("data-wb-group")>"" OR $dom->attr("data-wb-total")>"") {
                //$dom->wbTableProcessor();
                $size=false;
            }
            if ($size AND !$dom->hasClass("pagination")) {
                $find=$dom->params->find;
                tagPagination($dom);
            }
        }
        $dom->addClass("wb-done");
        $dom->removeAttr("data-wb");
        return $dom;
    }
?>