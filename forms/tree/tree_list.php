<div class="element-content mt-2">
    <h5 class="element-header">
                     {{_lang.list}}
                     <button class="btn btn-sm btn-success pull-right" data-wb="role=ajax&url=/form/{{_form}}/edit/_new&append=#content">
                       <i class="fa fa-plus"></i> {{_lang.add}}
                     </button>
    </h5>

	<div class="element-box mt-3">
	  <div class="table-responsive">
		<table class="table table-lightborder">
		  <thead class="thead-dark">
			<tr>
			  <th>{{_lang.name}}</th>
			  <th>{{_lang.header}}</th>
			  <th class="text-right">{{_lang.action}}</th>
			</tr>
		  </thead>
		  <tbody data-wb="role=foreach&form={{_form}}&size={{_sett.page_size}}&sort=id" id="{{_form}}List">
			<tr data-watcher="item={{id}}&watcher=#{{_form}}List">
			  <td class="nowrap">{{id}}</td>
			  <td>{{header}}<br><small>{{techdescr}}</small></td>
			  <td class="text-right"  data-wb="role=include&form=common_actions"></td>
			  </td>
			</tr>
		  </tbody>
		</table>
	  </div>
	</div>
</div>
<script type="text/locale" data-wb="role=include&form=tree_common.ini"></script>
