<div class="element-content mt-2">
  <h6 class="element-header row">
      <div class="col-12 col-sm-6">
           <button class="btn btn-sm btn-primary" data-wb="role=ajax&url=/form/{{_form}}/list/?groups=true&html=#content">
             <i class="fa fa-users"></i> {{_LANG[lsgroups]}}
           </button>

           <button class="btn btn-sm btn-success ml-2" data-wb="role=ajax&url=/form/{{_form}}/list/&html=#content">
             <i class="fa fa-user"></i> {{_LANG[lsusers]}}
           </button>
      </div>
      <div class="col-12 col-sm-6 text-right">
           <button class="btn btn-sm btn-primary mr-2" data-wb="role=ajax&url=/form/{{_form}}/edit/_new?group=true&append=#content">
             <i class="fa fa-users"></i> {{_LANG[addgroup]}}
           </button>
           <button class="btn btn-sm btn-success" data-wb="role=ajax&url=/form/{{_form}}/edit/_new&append=#content">
             <i class="fa fa-user"></i> {{_LANG[add]}}
           </button>
      </div>
    </h6>
    <div id="usersList-top" class="sr-only" style="top:-50px;"></div>
  <div class="element-box row" >


    <div class="col-sm-3">
      <label class="content-left-label">{{_LANG[groups]}}</label>
      <div class="list-group" id="{{_form}}Catalog" data-wb="role=foreach&form=users" data-wb-if='isgroup="on" AND active="on"'>
        <a href="#usersList-top" data-wb="role=ajax&url=/form/users/list/{{id}}/" title="{{name}}"
          data-wb-html="#content" class="list-group-item list-group-item-action">
          {{id}}
        </a>
      </div>
    </div>


    <div class="col-sm-9">

      <div class="table-responsive">
        <table class="table table-lightborder table-hover table-striped">
          <thead class="thead-dark">
            <tr data-wb-where='"{{_route.params.groups}}"!="true"'>
              <th>{{_lang.id}}</th>
              <th class="d-none d-sm-table-cell">{{_lang.firstname}}</th>
              <th class="d-none d-sm-table-cell">{{_lang.email}}</th>
              <th class="text-center">{{_lang.status}}</th>
              <th class="text-right">{{_lang.action}}</th>
            </tr>
            <tr data-wb-where='"{{_route.params.groups}}"="true"'>
              <th>{{_lang.group}}</th>
              <th class="d-none d-sm-table-cell">{{_lang.name}}</th>
              <th class="text-center">{{_lang.status}}</th>
              <th class="text-right">{{_lang.action}}</th>
            </tr>
          </thead>
          <tbody data-wb="role=foreach&from=result&size={{_sett.page_size}}" id="{{_form}}List">
            <tr data-watcher="item={{id}}&watcher=#{{_form}}List">
              <td class="nowrap w-20">{{id}}</td>
              <td class="d-none d-sm-table-cell w-auto" data-wb-where='"{{isgroup}}"!="on"'>{{first_name}}</td>
              <td class="d-none d-sm-table-cell w-auto" data-wb-where='"{{isgroup}}"="on"'>{{name}}</td>
              <td class="d-none d-sm-table-cell w-auto" data-wb-where='"{{isgroup}}"!="on"'>{{email}}</td>
              <td class="text-center w-20">
                  <label class="switch"><input type="checkbox" name="active" data-wb="role=save&form={{_form}}&item={{id}}&field=active"><span></span></label>
              </td>
              <td class="text-right w-auto" data-wb="role=include&form=common_actions"></td>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/locale" data-wb="role=include&form=users_common.ini"></script>
