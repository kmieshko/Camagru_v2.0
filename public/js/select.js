var upload = document.getElementById("upload");
var camera = document.getElementById("camera");
var frames = document.getElementById('frames');
var tmpBlock = document.getElementById('saved-frames');
var width = 480;
var height = 360;
var res_img;
var select = '';
var zoom = 'fitted';
var preview = document.createElement('img');
preview.style.height = height;
preview.style.width = width;
preview.src = "/public/images/preview.png";

var inputScale;
var inputFitted;
var inputSaveUpl;

upload.onclick = function () {

    select = 'upload';
    document.getElementById("select").className = 'invisible';
    var selectUpload = document.getElementById('select-upload');
    selectUpload.removeAttribute('class');

    var divFittedScale = document.getElementById('fitted-scale');
    var inputChoose = document.getElementById('fileToUpload');
    var canvas = document.getElementById('canvas-upload');
    inputScale = document.getElementById('scale');
    inputFitted = document.getElementById('fitted');
    var context = canvas.getContext('2d');
    inputSaveUpl = document.getElementById('save-upl');

    inputChoose.addEventListener('change', function (e) {
        var reader = new FileReader();
        reader.onload = function (event) {
            var img = new Image();
            img.onload = function () {
                canvas.setAttribute("class", "droppable");
                inputSaveUpl.removeAttribute("class");
                frames.removeAttribute('class');
                context.clearRect(0, 0, canvas.width, canvas.height);
                if (!divFittedScale.getAttribute('class')) divFittedScale.setAttribute('class', 'invisible');
                var result = ScaleImage(img.width, img.height, width, height, true);
                context.drawImage(img, 0, 0, img.width, img.height, result.targetleft, result.targettop, result.width, result.height);
                if (img.height / img.width !== 3 / 4) {
                    divFittedScale.removeAttribute('class');
                    inputScale.onclick = function () {
                        zoom = 'scale';
                        var result = ScaleImage(img.width, img.height, width, height, false);
                        context.clearRect(0, 0, canvas.width, canvas.height);
                        context.drawImage(img, 0, 0, img.width, img.height, result.targetleft, result.targettop, result.width, result.height);
                    };

                    inputFitted.onclick = function () {
                        zoom = 'fitted';
                        var result = ScaleImage(img.width, img.height, width, height, true);
                        context.clearRect(0, 0, canvas.width, canvas.height);
                        context.drawImage(img, 0, 0, img.width, img.height, result.targetleft, result.targettop, result.width, result.height);
                    };
                }

                inputClear.onclick = function () {
                    for (var i = 0; i < tmpBlock.childNodes.length;) {
                        var child = tmpBlock.childNodes[i];
                        child.remove();
                    }
                    inputClear.setAttribute('class', 'invisible');
                    inputSaveUpl.disabled = true;
                    inputFitted.disabled = false;
                    inputScale.disabled = false;
                };

                inputSaveUpl.onclick = function () {
                    for (var i = 0; i < tmpBlock.childNodes.length;) {
                        var child = tmpBlock.childNodes[i];
                        var childCoords = getCoords(child);
                        var canvasCoords = getCoords(canvas);
                        context.drawImage(child, childCoords.left - canvasCoords.left, childCoords.top - canvasCoords.top, parseInt(child.style.width), parseInt(child.style.height));
                        child.remove();
                    }
                    res_img = convertCanvasToImage(canvas);

                    var xhr = new XMLHttpRequest();
                    var body = "image=" + res_img.src;
                    xhr.open("POST", '/gallery/save-image', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send(body);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            context.clearRect(0, 0, canvas.width, canvas.height);
                            canvas.setAttribute('class', 'invisible');
                            if (!divFittedScale.getAttribute('class')) divFittedScale.setAttribute('class', 'invisible');
                            inputSaveUpl.setAttribute('class', 'invisible');
                            selectUpload.setAttribute('class', 'invisible');
                            document.getElementById("select").removeAttribute('class');
                            frames.setAttribute('class', 'invisible');
                            inputClear.setAttribute('class', 'invisible');
                            inputSaveUpl.disabled = true;
                            select = '';

                            document.getElementById('fileToUpload').remove();
                            var newInput = document.createElement('input');
                            newInput.type = "file";
                            newInput.name = "fileToUpload";
                            newInput.id = "fileToUpload";
                            selectUpload.appendChild(newInput);
                        }
                    };
                };
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
        }, false);
};

var inputSnap;
var inputSaveCam;

camera.onclick = function () {

    select = 'camera';
    document.getElementById("select").className = 'invisible';
    frames.removeAttribute('class');

    var selectCamera = document.getElementById('select-camera');
    selectCamera.removeAttribute('class');
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas-camera');
    var context = canvas.getContext('2d');
    context.drawImage(preview, 0, 0, width, height);
    inputSnap = document.getElementById('snap');
    inputSaveCam = document.getElementById('save-cam');
    inputSnap.disabled = true;

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({video: true}).then(function (stream) {
            video.srcObject = stream;
            video.play();
        });
    }

    inputClear.onclick = function () {
        context.clearRect(0, 0, canvas.width, canvas.height);
        context.drawImage(preview, 0, 0, width, height);
        for (var i = 0; i < tmpBlock.childNodes.length;) {
            var child = tmpBlock.childNodes[i];
            child.remove();
        }
        inputClear.setAttribute('class', 'invisible');
        inputSnap.disabled = true;
        inputSaveCam.disabled = true;
    };

    inputSnap.onclick = function () {
        context.drawImage(video, 0, 0, width, height);
        for (var i = 0; i < tmpBlock.childNodes.length; i++) {
            var child = tmpBlock.childNodes[i];
            var childCoords = getCoords(child);
            var videoCoords = getCoords(video);
            context.drawImage(child, childCoords.left - videoCoords.left, childCoords.top - videoCoords.top, parseInt(child.style.width), parseInt(child.style.height));
        }
        res_img = convertCanvasToImage(canvas);
        inputSaveCam.disabled = false;
    };

    inputSaveCam.onclick = function () {
        if (res_img) {
            var xhr = new XMLHttpRequest();
            var body = "image=" + res_img.src;
            xhr.open("POST", '/gallery/save-image', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(body);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    video.pause();
                    video.srcObject.getTracks()[0].stop();
                    selectCamera.setAttribute('class', 'invisible');
                    inputClear.setAttribute('class', 'invisible');
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    for (var i = 0; i < tmpBlock.childNodes.length;) {
                        var child = tmpBlock.childNodes[i];
                        child.remove();
                    }
                    select = '';
                    inputSaveCam.disabled = true;

                    if (!frameButtons.getAttribute('class')) {
                        frameButtons.setAttribute('class', 'invisible');
                        var img = document.createElement('img');
                        img = document.getElementById("frames").appendChild(img);
                        img.src = (clone.element).getAttribute("src");
                        document.getElementsByClassName('draggable').item(0).remove();
                        for (var i = 0; i < frames.getElementsByTagName('img').length; i++) {
                            var child = frames.getElementsByTagName('img').item(i);
                            console.log(child);
                            child.setAttribute('class', 'draggable')
                        }
                    }
                    frames.setAttribute('class', 'invisible');
                    video.remove();
                    var newVideo = document.createElement('video');
                    newVideo.id = 'video';
                    newVideo.width = width;
                    newVideo.height = height;
                    newVideo.className = "droppable";
                    selectCamera.firstChild.nextSibling.appendChild(newVideo);

                    var div_added = document.createElement('div');
                    div_added.className = "added_img";
                    div_added.innerHTML = "Image succesfully added to the gallery";
                    var result = document.createElement('img');
                    result.src = res_img.src;
                    div_added.appendChild(result);
                    document.body.insertBefore(document.body.appendChild(div_added), document.getElementById('select'));

                    var choose = document.createElement('div');
                    choose.id = 'choose';
                    var inputAddNewImage = document.createElement('input');
                    var inputGoToGallery = document.createElement('input');
                    choose.appendChild(inputAddNewImage);
                    choose.appendChild(inputGoToGallery);
                    inputAddNewImage.type = "button";
                    inputAddNewImage.value = "Add New Image";
                    inputGoToGallery.type = "button";
                    inputGoToGallery.value = "Go To Gallery";
                    document.body.insertBefore(document.body.appendChild(choose), document.getElementById("select"));

                    inputGoToGallery.onclick = function() {
                        choose.remove();
                        div_added.remove();
                        location.replace('/main/index');
                    };

                    inputAddNewImage.onclick = function () {
                        choose.remove();
                        div_added.remove();
                        document.getElementById("select").removeAttribute('class');
                    };
                }
            };
        }
    };
};

function convertCanvasToImage(canvas) {
    var new_image = new Image();
    new_image.src = canvas.toDataURL("image/png");
    return new_image;
}