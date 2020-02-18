<div id="cms-interface" data-prop="interface">
  <h3>{{_lang.header}}</h3>

  <form data-wb="role=formdata&form=admin&item=interface">

    <div class="form-group row">
      <label class="form-control-label col-12">{{_lang.menu}}</label>
      <div class="col-12">
          <input name="menu" data-wb="role=tree" placeholder="{{_lang.menu}}">
      </div>
    </div>
    <button data-wb="role=save&form=admin&item=interface">Save</button>
  </form>
</div>
<script type="text/locale">
[en]
header = "Interface"
menu = "Menu"
[ru]
header = "Интерфейс"
menu = "Меню"
</script>
