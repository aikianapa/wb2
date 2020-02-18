<?php session_start(); ?>
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
    <style>.crop-btn { width: 93px; } .delete-btn { width: 99px; }</style>
</head>
<body>
    <?php require __DIR__.'/_navbar.php'; ?>

    <div class="container">
        <div class="demo-container col-md-9 col-md-offset-2">
            <?php demo_nav(); ?>

            <?php
                // Here you can fetch the avatar source from your database.
                // In this case it's from the session and it contains both, the original and the avatar version.
                $avatar = isset($_SESSION['avatar']) ? 'files/avatar/' . $_SESSION['avatar'] : null;
                $original = isset($_SESSION['avatar']) ? 'files/' . $_SESSION['avatar'] : null;
            ?>

            <p>
                <!-- Display the avatar -->
                <img src="<?php echo $avatar ? $avatar : 'https://www.gravatar.com/avatar/?d=mm&s=300'; ?>" class="avatar" width="200">
            </p>

            <div id="filepicker">
                <!-- Button Bar -->
                <div class="button-bar">
                    <div>
                        <div class="btn btn-success fileinput">
                            <i class="fa fa-arrow-circle-o-up"></i> Upload
                            <input type="file" name="files[]">
                        </div>

                        <button type="button" class="btn btn-primary camera-show">
                            <i class="fa fa-camera"></i> Camera
                        </button>
                    </div>

                    <div>
                        <!-- Here we set the `data-fileurl` attribute with the original image source
                             that it will be used when cropping the image. -->
                        <button type="button" class="btn btn-info crop-btn"
                            <?php echo !$avatar ? 'style="display: none;"' : ''; ?>
                            data-fileurl="<?php echo $original; ?>">
                            <i class="fa fa-crop"></i> Crop
                        </button>

                        <!-- Here we set the `data-fileurl` attribute with the original image source
                             that will be used when deleting the file. -->
                        <button type="button" class="btn btn-danger delete-btn"
                            <?php echo !$avatar ? 'style="display: none;"' : ''; ?>
                            data-fileurl="<?php echo $original; ?>">
                            <i class="fa fa-trash-o"></i> Delete
                        </button>
                    </div>
                </div>

                <!-- Drop Window -->
                <div class="drop-window fade">
                    <div class="drop-window-content">
                        <h3><i class="fa fa-upload"></i> Drop files to upload</h3>
                    </div>
                </div>
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
    <script src="assets/js/filepicker-drop.js"></script>
    <script src="assets/js/filepicker-crop.js"></script>
    <script src="assets/js/filepicker-camera.js"></script>

    <script>
        /*global $*/

        var cropBtn = $('.crop-btn');
        var deleteBtn = $('.delete-btn');

        $('#filepicker').filePicker({
            url: 'uploader/avatar.php',
            acceptedFiles: /(\.|\/)(gif|jpe?g|png)$/i,
            plugins: ['drop', 'camera', 'crop'],
            crop: {
                aspectRatio: 1, // Square
                showBtn: cropBtn
            }
        })
        .on('add.filepicker', function (e, data) {
            var file = data.files[0];

            if (file.error) {
                e.preventDefault();
                alert(file.error);
            }
        })
        .on('done.filepicker', function (e, data) {
            // Here the file has been uploaded.
            var file = data.files[0];

            if (file.error) {
                alert(file.error);
            } else {
                // Show the crop modal.
                $(this).filePicker().plugins.crop.show(file.url);
            }
        })
        .on('cropsave.filepicker', function (e, file) {
            // Here the image has been cropped.

            // Update the avatar image.
            $('.avatar').attr('src', file.versions.avatar.url +'?'+ new Date().getTime());

            // Update the button fileurl.
            cropBtn.data('fileurl', file.url).show();
            deleteBtn.data('file', file.name).show();
        })
        .on('fail.filepicker', function () {
            alert('Oops! Something went wrong.');
        });

        // When clicking on the delete button delete the file.
        deleteBtn.on('click', function () {
            // Delete the file.
            $('#filepicker').filePicker().delete($(this).data('file'));

            // Reset default avatar.
            $('.avatar').attr('src', 'https://www.gravatar.com/avatar/?d=mm&s=300');

            // Hide crop and delete buttons.
            cropBtn.hide();
            deleteBtn.hide();
        });
    </script>

</body>
</html>
