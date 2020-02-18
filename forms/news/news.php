<?php
function _newsAfterItemRead($Item=null) {
    if ($Item!==null) {
        if ($_ENV["route"]["mode"]=="show") {
            if (!isset($Item["title"]) OR $Item["title"]=="") {$Item["title"]=$Item["header"];}
            if ($Item["title"]=="") {$Item["title"]=$_ENV["settings"]["header"];}
        }
    }
	return $Item;
}

function _newsBeforeItemShow(&$Item=null) {
  $Item["date"] = date("d.m.Y H:i:s",strtotime($Item["date"]));
  $Item["descr"]=strip_tags($Item["descr"]);
  $Item["day"]=date("d",strtotime($Item["date"]));
  $Item["month"]=date("M",strtotime($Item["date"]));
  $Item["datetime"]=date("d.m.Y H:i",strtotime($Item["date"]));
  return $Item;
}
?>
