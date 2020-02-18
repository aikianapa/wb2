<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta data-wb="role=variable" var="base" value="/engine/modules/cms/tpl">
  <meta data-wb="role=snippet">
  <meta data-wb="role=snippet&load=fontawesome4">
  <meta http-equiv="refresh" content="0; url=/signin" data-wb-disallow="admin" />
	<title>WebBasic v.2</title>
</head>

<body class="body cms">
	<div class="d-flex" id="wrapper" data-wb-allow="admin">
		<!-- sidebar -->
		<div class="sidebar sidebar-darken">

			<!-- sidebar menu -->
			<div class="sidebar-menu sticky-top">
				<!-- menu -->
        <meta data-wb="role=include&url=/module/cms/tpl/sidemenu">
			</div>
		</div>

		<!-- website content -->
		<div class="content">

			<!-- navbar top fixed -->
			<nav class="navbar navbar-expand-lg sticky-top navbar-lighten">
				<!-- navbar sidebar menu toggle -->
				<span class="navbar-text">
					<a href="#" id="sidebar-toggle" class="navbar-bars">
						<i class="fa fa-bars" aria-hidden="true"></i>
					</a>
				</span>
        <!-- navbar title -->
				<a class="navbar-brand navbar-link" href="#">&nbsp; Web Basic v.2</a>
				<!-- navbar dropdown menu-->
				<div class="collapse navbar-collapse">
					<div class="dropdown dropdown-logged dropdown-logged-lighten">
						<a href="#" data-toggle="dropdown" class="dropdown-logged-toggle dropdown-link">
							<span class="dropdown-user">Accgit</span>
							<img data-wb="role=thumbnail" src="img/avatar.png" alt="avatar" class="dropdown-avatar">
						</a>
						<div class="dropdown-menu dropdown-logged-menu dropdown-menu-right border-0 dropdown-menu-lighten">
							<a class="dropdown-item dropdown-logged-item" href="#"><i class="fa fa-user-o" aria-hidden="true"></i>Profil</a>
							<a class="dropdown-item dropdown-logged-item" href="/signout"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
						</div>
					</div>
				</div>
			</nav>

			<!-- content container -->
			<div class="container-fluid" id="content">
      <div class="content-box content-lighten">
        <meta data-wb="role=module&load=filepicker"  data-wb-uploader="/engine/modules/_uploader/uploader.php" data-wb-path="/uploads/test/">
        <meta data-wb="role=module&load=datetimepicker" name="date" class="form-control">
        <div data-wb="role=include&snippet=source" data-wb-name="werty"></div>
      </div>

<button class="btn btn-warning" data-wb="role=ajax&url=/ajax/eventtest">Event</button>

				<!-- one box -->
				<div class="content-box content-lighten">
					<h3>Website content</h3>
					<p>Here is content this website page.</p>
				</div>

				<!-- two box -->
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<div class="content-box content-lighten">
							<h3>Website content</h3>
							<p>Here is content this website page.</p>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="content-box content-lighten">
							<h3>Website content</h3>
							<p>Here is content this website page.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- javascript library -->
	<script type="wbapp">
      wbapp.loadScripts([
        "{{_var.base}}/sidebar/js/perfect-scrollbar/perfect-scrollbar.min.js",
        "{{_var.base}}/sidebar/js/main.js",
        "{{_var.base}}/sidebar/js/sidebar.menu.js"
      ],"cms-js");
  </script>
	<script type="wbapp">
		$(document).on("cms-js",function(){
      wbapp.loadStyles([
					"{{_var.base}}/css/bootstrap.min.css",
					"{{_var.base}}/sidebar/js/perfect-scrollbar/perfect-scrollbar.css",
          "{{_var.base}}/cms.less"
      ]);

			new PerfectScrollbar('.list-scrollbar');
      $('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</body>

</html>
