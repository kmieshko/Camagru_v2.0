<?php if (isset($_SESSION['user'])) { ?>
<center>
    <div id="select">
        <input type="button" id="camera" value="Camera">
        <input type="button" id="upload" value="Upload Image">
    </div>

    <div id="select-upload" class="invisible">
        <label>Image File: </label>
        <input type="file" name="fileToUpload" id="fileToUpload">
    </div>

    <div id="frames" class="invisible">
        <img src="/public/frames/1.png" class="draggable">
        <img src="/public/frames/2.png" class="draggable">
        <img src="/public/frames/3.png" class="draggable">
        <img src="/public/frames/4.png" class="draggable">
    </div>

    <canvas id='canvas-upload' width="640" height="480" class="invisible"></canvas>
    <div id="fitted-scale" class="invisible">
        <input id="scale" type="button" value="Scale Image">
        <input id="fitted" type="button" value="Fitted Image">
    </div>
    <div>
        <input id="save-upl" type="button" value="Save Photo" class="invisible" disabled>
    </div>

    <div id="select-camera" class="invisible">
        <div>
            <video width="640" height="480" id="video" class="droppable"></video>
        </div>
        <div>
            <canvas width="640" height="480" id="canvas-camera"></canvas>
        </div>
        <div>
            <input id="snap" type="button" value="Snap Photo" disabled>
            <input id="save-cam" type="button" value="Save Photo" disabled>
        </div>
    </div>

    <div id="frame-buttons" class="invisible">
        <input id="save-frame" type="button" value="Save Frame">
        <input id="scale-plus" type="button" value="Frame Scale +">
        <input id="scale-minus" type="button" value="Frame Scale -">
    </div>
    <input id="clear" type="button" class="invisible" value="Clear">
    <div id="saved-frames"></div>

    <script src="/public/js/select.js"></script>
    <script src="/public/js/dragndrop.js"></script>
</center>
<?php } else $_SESSION['error'] = 'Adding pictures are allowed only for registered users.';?>