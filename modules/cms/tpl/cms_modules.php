<div id="cms-modules" data-prop="modules">
  <h3>{{_lang.header}}</h3>
  <div id="cms-modules-top" class="sr-only" style="top:-50px;"></div>
  <div class="row">
    <div class="col-sm-5 mt-3">
      <div class="list-group" data-wb="role=foreach&from=_env.modules">
        <a href="#cms-modules-top" data-wb="role=ajax&url=/module/cms/modprop/{{_key}}&html=#cms-modules .property"
          class="list-group-item list-group-item-action">
          {{ucfirst(label)}}
        </a>
      </div>
    </div>
    <div class="col-sm-7 property mt-3">

    </div>
  </div>
</div>

<div class=" alert alert-warning" id="cms-modules-modprop-warn" data-prop="warn">
  <p class="mb-0">Module has no have settings!</p>
</div>

<div data-wb="role=include&snippet=modalinline&content=.modal-body" class="show" data-prop="modal">
  <form id="cms-modules-prop" data-wb="role=formdata&form=admin&item=settings&field=modules.{{module}}">
      <div class="formgroup row" data-wb-where='"{{module}}" !== "cms"'>
        <label class="form-control-label col-4">{{_lang.enable}}</label>
        <label class="switch col-8">
          <input type="checkbox" name="active">
          <span></span>
        </label>
      </div>
</form>
<button type="button" class="btn btn-primary" append=".modal[data-prop] > .modal-content > .modal-footer" data-wb="role=save&selector=#cms-modules-prop&form=admin&item=settings&field=modules.{{module}}">Save</button>
<script type="text/locale">
[en]
  enable = "Enable"
  header = "Modules settings"
[ru]
  enable = "Включен"
  header = "Настройки модулей"
</script>
</div>
