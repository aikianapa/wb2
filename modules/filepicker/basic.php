<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Filepicker - Multi file uploader</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,600,500,700">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.0/cropper.min.css">
    <link rel="stylesheet" href="assets/css/demo.css">
    <link rel="stylesheet" href="assets/css/filepicker.css">
    <style>.files { padding-left: 20px; margin-top: 20px; }</style>
</head>
<body>
    <?php require __DIR__.'/_navbar.php'; ?>

    <div class="container">
        <div class="demo-container col-md-9 col-md-offset-2">
            <?php demo_nav(); ?>

            <div id="filepicker">
                <!-- Button Bar -->
                <div class="button-bar">
                    <div class="btn btn-success fileinput">
                        <i class="fa fa-arrow-circle-o-up"></i> Upload
                        <input type="file" name="files[]" multiple>
                    </div>

                    <button type="button" class="btn btn-primary camera-show">
                        <i class="fa fa-camera"></i> Camera
                    </button>
                </div>

                <!-- Files -->
                <ul class="files"></ul>
            </div><!-- end of #filepicker -->
        </div>
    </div>

    <!-- Crop Modal -->
    <div id="crop-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" data-dismiss="modal">&times;</span>
                    <h4 class="modal-title">Make a selection</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning crop-loading">Loading image...</div>
                    <div class="crop-preview"></div>
                </div>
                <div class="modal-footer">
                    <div class="crop-rotate">
                        <button type="button" class="btn btn-default btn-sm crop-rotate-left" title="Rotate left">
                            <i class="fa fa-undo"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm crop-flip-horizontal" title="Flip horizontal">
                            <i class="fa fa-arrows-h"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-default btn-sm crop-flip-vertical" title="Flip vertical">
                            <i class="fa fa-arrows-v"></i>
                        </button> -->
                        <button type="button" class="btn btn-default btn-sm crop-rotate-right" title="Rotate right">
                            <i class="fa fa-repeat"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success crop-save" data-loading-text="Saving...">Save</button>
                </div>
            </div>
        </div>
    </div><!-- end of #crop-modal -->

    <!-- Camera Modal -->
    <div id="camera-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" data-dismiss="modal">&times;</span>
                    <h4 class="modal-title">Take a picture</h4>
                </div>
                <div class="modal-body">
                    <div class="camera-preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left camera-hide" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success camera-capture">Take picture</button>
                </div>
            </div>
        </div>
    </div><!-- end of #camera-modal -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.0/cropper.min.js"></script>

    <script src="assets/js/filepicker.js"></script>
    <script src="assets/js/filepicker-crop.js"></script>
    <script src="assets/js/filepicker-camera.js"></script>

    <script>
        /*global $*/

        $('#filepicker').filePicker({
            url: 'uploader/index.php',
            plugins: ['camera', 'crop']
        })
        .on('done.filepicker', function (e, data) {
            var list = $('.files');

            // Iterate through the uploaded files
            $.each(data.files, function (i, file) {
                if (file.error) {
                    list.append('<li>' + file.name + ' - ' + file.error + '</li>');
                } else {
                    list.append('<li>' + file.name + '</li>');
                }
            });
        })
        .on('fail.filepicker', function () {
            alert('Oops! Something went wrong.');
        });
    </script>

</body>
</html>
