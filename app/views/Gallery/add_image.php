<?php if (isset($_SESSION['user'])) { ?>

    <center>
        <div id="select">
            <input type="button" id="camera" value="Camera">
            <input type="button" id="upload" value="Upload Image">
        </div>
    </center>

    <div class="center">
        <div id="select-upload" class="invisible">
            <input type="file" name="fileToUpload" id="fileToUpload" class="fileToUpload">
            <label for="fileToUpload">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                    <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                </svg>
                <span>Choose a file&hellip;</span>
            </label>
        </div>
        <div id="frames" class="invisible">
            <img src="/public/frames/1.png" class="draggable">
            <img src="/public/frames/2.png" class="draggable">
            <img src="/public/frames/3.png" class="draggable">
            <img src="/public/frames/4.png" class="draggable">
        </div>
        <div id='canvas-upload-block'>
            <canvas id='canvas-upload' width="560" height="420" class="invisible"></canvas>
        </div>
        <div id="fitted-scale" class="invisible">
            <input id="scale" type="button" value="Scale Image" class="active">
            <input id="fitted" type="button" value="Fitted Image" class="active">
        </div>
        <div>
            <input id="save-upl" type="button" value="Save Photo" class="invisible not-active" disabled>
        </div>

        <div id="select-camera" class="invisible">
            <div>
                <video width="560" height="420" id="video" class="droppable"></video>
            </div>
            <div>
                <canvas width="560" height="420" id="canvas-camera"></canvas>
            </div>
            <div class="snap-save">
                <input id="snap" type="button" value="Snap Photo" disabled>
                <input id="save-cam" type="button" value="Save Photo" disabled class="not-active">
            </div>
        </div>
        <div id="frame-buttons" class="invisible">
            <center>
                <div><input id="save-frame" type="button" value="Save Frame"></div>
                <div><input id="scale-plus" type="button" value="scale +"></div>
                <div><input id="scale-minus" type="button" value="scale -"></div>
            </center>
        </div>
        <input id="clear" type="button" class="invisible" value="Clear All">
        <div id="saved-frames"></div>

        <script src="/public/js/select.js"></script>
        <script src="/public/js/dragndrop.js"></script>
    </div>
<?php } else $_SESSION['error'] = 'Adding pictures are allowed only for registered users.';?>
