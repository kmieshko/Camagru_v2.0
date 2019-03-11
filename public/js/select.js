var upload = document.getElementById("upload");
var camera = document.getElementById("camera");
var frames = document.getElementById('frames');
var tmpBlock = document.getElementById('saved-frames');
var width = 560;
var height = 420;
var res_img;
var select = '';
var zoom = 'fitted';
var preview = document.createElement('img');
preview.style.height = height;
preview.style.width = width;
preview.src = "/public/icons/preview.png";

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
                inputSaveUpl.classList.remove('invisible');
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
                    inputSaveUpl.classList.add('not-active');
                    inputFitted.disabled = false;
                    inputScale.disabled = false;
                    inputFitted.classList.add('active');
                    inputScale.classList.add('active');
                    inputFitted.classList.remove('not-active');
                    inputScale.classList.remove('not-active');

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
                            inputSaveUpl.classList.add('invisible');
                            selectUpload.setAttribute('class', 'invisible');
                            frames.setAttribute('class', 'invisible');
                            inputClear.setAttribute('class', 'invisible');
                            inputSaveUpl.disabled = true;
                            select = '';

                            while (selectUpload.firstChild) {
                                selectUpload.removeChild(selectUpload.firstChild);
                            }
                            var newInput = document.createElement('input');
                            newInput.type = "file";
                            newInput.name = "fileToUpload";
                            newInput.id = "fileToUpload";
                            newInput.className = "fileToUpload";
                            var newLabel = document.createElement('label');
                            newLabel.setAttribute('for', 'fileToUpload');
                            newLabel.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">\n' +
                                                    '<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>\n' +
                                                '</svg>\n' +
                                                '<span>Choose a file…</span>';
                            selectUpload.appendChild(newInput);
                            selectUpload.appendChild(newLabel);

                            var div_added = document.createElement('div');
                            div_added.className = "added_img";
                            div_added.innerHTML = "<p>Image succesfully added to the gallery </p>";
                            var result = document.createElement('img');
                            result.src = res_img.src;
                            div_added.appendChild(result);
                            document.querySelector('.site-content').appendChild(div_added);

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
                            div_added.appendChild(choose);

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
    inputSnap.classList.add('not-active');
    inputSnap.classList.remove('active');

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
        inputClear.classList.add('invisible');
        inputSnap.disabled = true;
        inputSaveCam.disabled = true;
        inputSnap.classList.add('not-active');
        inputSnap.classList.remove('active');
        inputSaveCam.classList.add('not-active');
        inputSaveCam.classList.remove('active');
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
        inputSaveCam.classList.add('active');
        inputSaveCam.classList.remove('not-active');
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
                    selectCamera.classList.add('invisible');
                    inputClear.classList.add('invisible');
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    for (var i = 0; i < tmpBlock.childNodes.length;) {
                        var child = tmpBlock.childNodes[i];
                        child.remove();
                    }
                    select = '';
                    inputSaveCam.disabled = true;
                    inputSaveCam.classList.add('not-active');

                    if (!frameButtons.getAttribute('class')) {
                        frameButtons.classList.add('invisible');
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
                    frames.classList.add('invisible');
                    video.remove();
                    var newVideo = document.createElement('video');
                    newVideo.id = 'video';
                    newVideo.width = width;
                    newVideo.height = height;
                    newVideo.className = "droppable";
                    selectCamera.firstChild.nextSibling.appendChild(newVideo);

                    var div_added = document.createElement('div');
                    div_added.className = "added_img";
                    div_added.innerHTML = "<p>Image succesfully added to the gallery </p>";
                    var result = document.createElement('img');
                    result.src = res_img.src;
                    div_added.appendChild(result);
                    document.querySelector('.site-content').appendChild(div_added);

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
                    div_added.appendChild(choose);

                    inputGoToGallery.onclick = function() {
                        choose.remove();
                        div_added.remove();
                        location.replace('/main/index');
                    };

                    inputAddNewImage.onclick = function () {
                        choose.remove();
                        div_added.remove();
                        document.getElementById("select").classList.remove('invisible');
                    };
                }
            };
        }
    };
};