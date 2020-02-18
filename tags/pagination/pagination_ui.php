<nav>
<ul id='{{id}}' class="pagination justify-content-center mb-40" data-wb="role=foreach&from=pages&tpl=false">
    <li class="page-item" data-page='{{page}}'>
        <a class="page-link" href="#{{page}}" data-wb-ajaxpage='/{{href}}/'>{{page}}</a>
    </li>
</ul>

<li prepend="#{{id}}" class="page-item" data-page="prev">
    <a href="#prev" class="page-link" href tabindex="-1">&laquo;</a>
</li>

<li append="#{{id}}" class="page-item" data-page="next">
    <a href="#next" class="page-link" href tabindex="-1">&raquo;</a>
</li>

<li append="#{{id}}" class="page-more" data-page="more">
    <a href="#more" class="page-link" href tabindex="-1">{{_lang.more}}</a>
</li>

<script type="text/locale">
[en]
  more = "More"
[ru]
  more = "Далее"
</script>
</nav>
