<html>
  <head>
    <title>QR Scanner</title>
    <base href="{{_base}}">
    <meta data-wb="role=snippet&load=jquery">
    <meta data-wb="role=snippet&load=fontawesome4">
    <meta data-wb="role=snippet">
    <script type="text/javascript" src="instascan.min.js"></script>
    <link rel="stylesheet"  href="qrscan.less">
  </head>
  <body>
    <div class="container d-flex h-100 w-100  ">
        <div class="row align-items-center mx-auto">
            <div class="col-12 text-center">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                <i class="fa fa-qrcode fa-2x"></i> QR scan
              </button>
            </div>
        </div>
    </div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <!-- modal-sm = modal small | modal-lg = modal large | modal-xl = modal extra large  -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content  modal-wide">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                  <video id="modQrscan"></video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>



    <script type="text/javascript">
      let scanner = new Instascan.Scanner({ video: document.getElementById('modQrscan') });
      scanner.addListener('scan', function (content) {
        wbapp.alert(content);
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
    </script>
  </body>
</html>
