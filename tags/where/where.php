<?php
function tagWhere(&$dom) {
  $res = true;
  $where=$dom->params->data;
  if ($where == "") {
      $where = $dom->attr("data");
      $dom->removeAttr("data");
  } else {
      $dom->removeAttr("data-wb-data");
  }
  $dom->removeAttr("data-wb");
  if ($where == "") return $res;
  $res = wbWhereItem($dom->data, $where);
  if (!$res) $dom->remove();
}
?>
