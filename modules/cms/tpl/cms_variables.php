<form data-wb="role=formdata&form=admin&item=settings">
  <div data-wb="role=multiinput" name="variables">
      <div class="col">
          <input class="form-control" placeholder="{{_lang.variable}}" type="text" name="var"> </div>
      <div class="col">
          <input class="form-control" placeholder="{{_lang.value}}" type="text" name="value"> </div>
      <div class="col">
          <input class="form-control" placeholder="{{_lang.description}}" type="text" name="header"> </div>
  </div>
  <button type="button" class="mt-3 btn btn-primary pull-right" name="button" data-wb="role=save&form=admin&item=settings">
    <i class="fa fa-save"></i> {{_lang.btn_save}}
  </button>
</form>
