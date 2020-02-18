<div class="cms-actions btn-group">
  <a class="btn bg-transparent" type="button" data-wb="role=ajax&url=/form/{{_form}}/edit/{{id}}&append=#content">
    <i class='fa fa-pencil'></i>
  </a>
  <a aria-expanded="false" aria-haspopup="true" class="btn bg-transparent dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" type="button">
    <span class="sr-only">Toggle Dropdown</span>
    <i class='fa fa-ellipsis-v'></i>
  </a>
  <div class="dropdown-menu dropdown-menu-right">
    <a class="dropdown-item" href="javascript:void(0);"
      data-wb="role=ajax&url=/form/{{_table}}/edit/{{id}}&append=#content">
      <i class='fa fa-pencil'></i> <span>{{_lang.edit}}</span>
    </a>
    <a class="dropdown-item" href="javascript:void(0);"
      data-wb="role=ajax&url=/form/{{_form}}/rename/{{id}}/?confirm=true&append=#content">
      <i class="fa fa-i-cursor"></i> <span>{{_lang.rename}}</span></a>
    <!--a class="dropdown-item" href="#"> <i class="fa fa-pencil"></i> Дублировать</a-->
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="javascript:void(0);"
      data-wb="role=ajax&url=/form/{{_form}}/remove/{{id}}/?confirm=true&append=#content">
      <i class='fa fa-trash'></i> <span>{{_lang.remove}}</span>
    </a>
  </div>
</div>

<script type="text/locale">
[ru]
        edit    = "Изменить"
        remove  = "Удалить"
        rename  = "Переименовать"
[en]
        edit    = "Edit"
        remove  = "Remove"
        rename  = "Rename"
</script>
