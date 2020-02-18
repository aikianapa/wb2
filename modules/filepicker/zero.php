<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Filepicker - Multi file uploader</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.0/cropper.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/fileicons.css">
    <link rel="stylesheet" href="assets/css/filepicker.css">
    <style>
        .pagination li { display: inline; }
        .pagination li.active { font-weight: bold; }
        .progress {
            height: 20px;
            overflow: hidden;
            border-radius: 2px;
            background-color: #f5f5f5;
        }
        .progress-bar {
            float: left;
            width: 0%;
            height: 100%;
            color: #fff;
            font-size: 12px;
            text-align: center;
            background-color: #1abc9c;
            -webkit-transition: width 0.6s ease;
            transition: width 0.6s ease;
        }
        .crop-container,
        .camera-container {
            max-width: 500px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <p><a href="index.php">Back to the full demo</a></p>

    <div id="filepicker">
        <!-- Button Bar -->
        <div class="button-bar">
            <input type="file" name="files[]" multiple>
            <button type="button" class="camera-show">Camera</button>
            <button type="button" class="delete-all">Delete all</button>
        </div>

        <!-- Camera container -->
        <div class="camera-container" style="display: none;">
            <div class="camera-preview"></div>
            <button type="button" class="camera-hide">Cancel</button>
            <button type="button" class="camera-capture">Take picture</button>
        </div>

        <!-- Crop container -->
        <div class="crop-container" style="display: none;">
            <div class="crop-loading">Loading image...</div>
            <div class="crop-preview"></div>

            <p>
                <button type="button" class="crop-rotate-left" title="Rotate left">&#8592;</button>
                <button type="button" class="crop-flip-horizontal" title="Flip horizontal">&#8596;</button>
                <!-- <button type="button" class="crop-flip-vertical" title="Flip vertical">&#8597;</button> -->
                <button type="button" class="crop-rotate-right" title="Rotate right">&#8594;</button>
            </p>
            <p>
                <button type="button" class="crop-hide">Cancel</button>
                <button type="button" class="crop-save">Save</button>
            </p>
        </div>

        <!-- Files -->
        <table>
            <thead>
                <tr>
                    <th class="column-preview">Preview</th>
                    <th class="column-name">Name</th>
                    <th class="column-size">Size</th>
                    <th class="column-date">Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="files"></tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-container"></div>

        <!-- Drop Window -->
        <div class="drop-window">
            <div class="drop-window-content">
                <h3>Drop files to upload</h3>
            </div>
        </div>
    </div><!-- end of #filepicker -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.0/cropper.min.js"></script>

    <script src="assets/js/filepicker.js"></script>
    <script src="assets/js/filepicker-ui.js"></script>
    <script src="assets/js/filepicker-drop.js"></script>
    <script src="assets/js/filepicker-crop.js"></script>
    <script src="assets/js/filepicker-camera.js"></script>

    <script>
        /*global $*/
        $('#filepicker').filePicker({
            url: 'uploader/index.php',
            plugins: ['ui', 'drop', 'camera', 'crop'],
            camera: {
                showBtn: $('.camera-show'),
                container: $('.camera-container')
            },
            crop: {
                container: $('.crop-container')
            }
        });
    </script>

    <!-- Upload Template -->
    <script type="text/x-tmpl" id="uploadTemplate">
        <tr class="upload-template">
            <td class="column-preview">
                <div class="preview">
                    <span class="fa file-icon-{%= o.file.extension %}"></span>
                </div>
            </td>
            <td class="column-name">
                <p class="name">{%= o.file.name %}</p>
                <span class="text-danger error">{%= o.file.error || '' %}</span>
            </td>
            <td colspan="2">
                <p>{%= o.file.sizeFormatted || '' %}</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active"></div>
                </div>
            </td>
            <td>
                {% if (!o.file.autoUpload && !o.file.error) { %}
                    <a href="#" class="start">Upload</a>
                {% } %}
                <a href="#" class="cancel">Cancel</a>
            </td>
        </tr>
    </script><!-- end of #uploadTemplate -->

    <!-- Download Template -->
    <script type="text/x-tmpl" id="downloadTemplate">
        {% o.timestamp = function (src) {
            return (src += (src.indexOf('?') > -1 ? '&' : '?') + new Date().getTime());
        }; %}
        <tr class="download-template">
            <td class="column-preview">
                <div class="preview">
                    {% if (o.file.versions && o.file.versions.thumb) { %}
                        <a href="{%= o.file.url %}" target="_blank">
                            <img src="{%= o.timestamp(o.file.versions.thumb.url) %}" width="64" height="64"></a>
                        </a>
                    {% } else { %}
                        <span class="fa file-icon-{%= o.file.extension %}"></span>
                    {% } %}
                </div>
            </td>
            <td class="column-name">
                <p class="name">
                    {% if (o.file.url) { %}
                        <a href="{%= o.file.url %}" target="_blank">{%= o.file.name %}</a>
                    {% } else { %}
                        {%= o.file.name %}
                    {% } %}
                </p>
                {% if (o.file.error) { %}
                    <span class="text-danger">{%= o.file.error %}</span>
                {% } %}
            </td>
            <td class="column-size"><p>{%= o.file.sizeFormatted %}</p></td>
            <td class="column-date">
                {% if (o.file.time) { %}
                    <time datetime="{%= o.file.timeISOString() %}">
                        {%= o.file.timeFormatted %}
                    </time>
                {% } %}
            </td>
            <td>
                {% if (o.file.imageFile && !o.file.error) { %}
                    <a href="#" class="crop">Crop</a>
                {% } %}
                {% if (o.file.error) { %}
                    <a href="#" class="cancel">Cancel</a>
                {% } else { %}
                    <a href="#" class="delete">Delete</a>
                {% } %}
            </td>
        </tr>
    </script><!-- end of #downloadTemplate -->

    <!-- Pagination Template -->
    <script type="text/x-tmpl" id="paginationTemplate">
        {% if (o.lastPage > 1) { %}
            <ul class="pagination pagination-sm">
                <li {% if (o.currentPage === 1) { %} class="disabled" {% } %}>
                    <a href="#!page={%= o.prevPage %}" data-page="{%= o.prevPage %}" title="Previous">&laquo;</a>
                </li>

                {% if (o.firstAdjacentPage > 1) { %}
                    <li><a href="#!page=1" data-page="1">1</a></li>
                    {% if (o.firstAdjacentPage > 2) { %}
                       <li class="disabled"><a>...</a></li>
                    {% } %}
                {% } %}

                {% for (var i = o.firstAdjacentPage; i <= o.lastAdjacentPage; i++) { %}
                    <li {% if (o.currentPage === i) { %} class="active" {% } %}>
                        <a href="#!page={%= i %}" data-page="{%= i %}">{%= i %}</a>
                    </li>
                {% } %}

                {% if (o.lastAdjacentPage < o.lastPage) { %}
                    {% if (o.lastAdjacentPage < o.lastPage - 1) { %}
                        <li class="disabled"><a>...</a></li>
                    {% } %}
                    <li><a href="#!page={%= o.lastPage %}" data-page="{%= o.lastPage %}">{%= o.lastPage %}</a></li>
                {% } %}

                <li {% if (o.currentPage === o.lastPage) { %} class="disabled" {% } %}>
                    <a href="#!page={%= o.nextPage %}" data-page="{%= o.nextPage %}" title="Next">&raquo</a>
                </li>
            </ul>
        {% } %}
    </script><!-- end of #paginationTemplate -->
</body>
</html>
