<div id="content" class="app-content box-shadow-z2 pjax-container" role="main">
  <style data-wb="role=module&load=less">
  @size: 0.8rem;
  @half: 0.4rem;
  #add-todo-form {
    padding-top: 1em;
  }

  #todoListWrapper {
    .sticky-top {
      top: 50px;
      padding-top: 1rem;
      background: #ffffff;
      z-index: 1;
    }
    .todo-list [type=datetimepicker] {
      border: 0;
      background: transparent;
      display: inline-block;
      width: auto;
    }
    .card {
      font-size: @size;
      .contenteditable:hover {
        background: transparent;
      }
      &.border-gray {
        .form-control {
          text-decoration: line-through;
        }
        .card-text {
          text-decoration: line-through;
        }
      }
      form {
        margin: 0;
      }
      .form-control {
        height: @size;
        font-size: calc (@size + .15rem);
        padding: 0;
        margin: 0;
      }
      input[type=checkbox] {
        width: @size;
        height: calc (@size + @half);
      }
      margin-bottom: @size;
      .card-header {
        padding: calc(@half / 2) @size;
        background-color: transparent;
      }
      .card-body {
        margin: 0;
        padding: calc(@half / 2) @size;
      }
      .fa {
        font-size: calc( @size + @half / 2);
      }
    }
  }

  #content .nav .dropdown-menu {
    margin-left: -140px;
  }
  </style>


  <div id="todoListWrapper">
    <div class="sticky-top row">
        <div class="col-12">

            <h5 class="element-header">
            {{_lang.checklist}}
            </h5>

        </div>

        <div class="col-12">
          <form id="add-todo-form">

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text btn">

                  <div class="dropdown dropright"  id="todo-status-menu">
                    <i class="fa fa-ellipsis-v dropdown-toggle" data-toggle="dropdown" style="width:24px">
                    </i>

                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);">
                      <i class="fa fa-dot-circle-o text-success" data-status="success"></i> {{_lang.active}}</a>

                      <a class="dropdown-item" href="javascript:void(0);">
                      <i class="fa fa-dot-circle-o text-danger" data-status="danger"></i> {{_lang.danger}}</a>

                      <a class="dropdown-item" href="javascript:void(0);">
                      <i class="fa fa-circle-o text-gray" data-status="gray"></i> {{_lang.archive}}</a>
                    </div>
                  </div>

                </span>
              </div>
              <input type="text" id="add-todo" name="task" class="form-control" minlength="3" placeholder="{{_lang.addtask}}...">
              <div class="input-group-append">
                <span class="input-group-text btn" data-wb="role=save&form={{_form}}&item=_new&watcher=#{{_form}}List">
                  <i class="fa fa-plus"></i>&nbsp;{{_lang.add}}
                </span>
              </div>
            </div>
          </form>

        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <ul id="{{_form}}List" class="todo-list list-group" data-wb="role=foreach&form=todo&sort=time&size=99999">
          <li class="card border-{{status}} text-{{status}}" data-watcher="item={{id}}&watcher=#{{_form}}List"
            data-wb-where='"{{_creator}}" = "{{_env.user.id}}"'>
            <form>
              <div class="card-header">
                <i data-wb="role=save&form={{_form}}&item={{id}}&remove=true&method=ajax&watcher=#{{_form}}List"
                  class="pull-right fa fa-trash-o fa-2x"></i>
                <div class="row">
                  <div class="col-auto">
                    <input type="checkbox" name="done" data-wb="role=save&form={{_form}}&item={{id}}&field=done&watcher=#{{_form}}List">
                  </div>
                  <div class="col-auto">
                    <i class="fa fa-clock-o fa-2x"></i>
                    <input type="datetimepicker" data-wb="role=module&load=datetimepicker" onchange="wbapp.save($(this),{form:'{{_form}}',item:'{{id}}',field:'time',method:'ajax',watcher:'#{{_form}}List'})"
                      class="text-{{status}}" name="time">
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="card-text" data-wb="role=save&form={{_form}}&item={{id}}&field=task" contenteditable>{{task}}</div>
              </div>
            </form>
          </li>
        </ul>
      </div>
    </div>


  </div>
  <script type="wbapp">
    wbapp.loadScripts(["/engine/forms/todo/js/todo.js"],"todo-js");
  </script>
	<script type="text/locale">
	[en]
		checklist	= "Check List"
		add 		= "Add"
		addtask		= "Add task"
		from		= "from"
		active		= "Active"
		danger		= "Expired"
		archive		= "Done"
	[ru]
		checklist	= "Список дел"
		add 		= "Добавить"
		addtask		= "Добавить задачу"
		from		= "из"
		active		= "В работе"
		danger		= "Просрочено"
		archive		= "Выполнено"
	</script>
</div>
