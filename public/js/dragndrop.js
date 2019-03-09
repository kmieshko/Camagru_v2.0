/**
 * elem - элемент, на котором была зажата мышь
 * avatar - аватар
 * downX/downY - координаты окна, на которых был mousedown
 * shiftX/shiftY - относительный сдвиг курсора от угла элемента
 */
var dragObject = {};
var clone = {};
var h = 50;
var w = 50;

document.onmousedown = function (event) {
    if (event.which !== 1) return;
    var element = event.target.closest('.draggable');
    if (element) {
        dragObject.element = element;
        dragObject.downX = event.pageX;
        dragObject.downY = event.pageY;
    }
};

function createAvatar() {
    var avatar = dragObject.element;
    var old = {
        parent: avatar.parentNode,
        nextSibling: avatar.nextSibling,
        position: 'relative',
        left: avatar.left || '',
        top: avatar.top || '',
        zIndex: avatar.zIndex || ''
    };
    avatar.rollback = function () {
        dragObject.avatar = document.getElementById("frames").appendChild(dragObject.avatar);
        avatar.style.position = old.position;
        avatar.style.left = old.left;
        avatar.style.top = old.top;
        avatar.style.zIndex = old.zIndex;
        avatar.style.width = w + 'px';
        avatar.style.height = h + 'px';
    };
    return avatar;
}

function getCoords(elem) {
    var box = elem.getBoundingClientRect();
    return {
        top: box.top + pageYOffset,
        left: box.left + pageXOffset
    };
}

function startDrag() {
    var avatar = dragObject.avatar;
    document.body.appendChild(avatar);
    avatar.style.zIndex = 9999;
    avatar.style.position = 'absolute';
}

document.onmousemove = function (event) {
    if (!dragObject.element) return;
    if (!dragObject.avatar) {
        dragObject.avatar = createAvatar();
        if (!dragObject.avatar) {
            dragObject = {};
            return;
        }
        var coords = getCoords(dragObject.avatar);
        dragObject.shiftX = dragObject.downX - coords.left;
        dragObject.shiftY = dragObject.downY - coords.top;
        startDrag();
    }
    dragObject.avatar.style.left = event.pageX - dragObject.shiftX + 'px';
    dragObject.avatar.style.top = event.pageY - dragObject.shiftY + 'px';
    dragObject.avatar.ondragstart = function () {
        return false;
    };
    if (!findDroppable(event))
    {
        dragObject.element.style.width = dragObject.element.naturalWidth + 'px';
        dragObject.element.style.height = dragObject.element.naturalHeight + 'px';
    }
};

function findDroppable(event) {
    dragObject.avatar.hidden = true;
    var elem = document.elementFromPoint(event.clientX, event.clientY);
    dragObject.avatar.hidden = false;
    if (elem == null) return null;
    return elem.closest('.droppable');
}

var imagesNotDraggable;
var imagesDraggable;
var i = 0;

function checkCanvasLimits(dragObject) {
    var dropElem;
    if (select == 'upload') dropElem = document.getElementById('canvas-upload');
    if (select == 'camera') dropElem = document.getElementById('video');
    var dropElemCoords = getCoords(dropElem);
    var imgLeft = parseFloat(dragObject.avatar.style.left);
    var imgTop = parseFloat(dragObject.avatar.style.top);
    var canvLeft = parseFloat(dropElemCoords.left);
    var canvTop = parseFloat(dropElemCoords.top);
    if ((imgTop > canvTop && (imgTop + dragObject.avatar.clientHeight) < (canvTop + height)) &&
        (imgLeft > canvLeft && (imgLeft + dragObject.avatar.clientWidth) < (canvLeft + width))) {
        return true;
    }
    return false;
}

document.onmouseup = function (event) {
    imagesDraggable = document.getElementById('frames').getElementsByClassName('draggable');
    imagesNotDraggable = document.getElementById('frames').getElementsByClassName('not-draggable');
    if (dragObject.avatar) {
        var dropElem = findDroppable(event);
        console.log(dropElem);
        if (!dropElem || !checkCanvasLimits(dragObject)) {
            dragObject.avatar.rollback();
            for (i = 0; i < imagesNotDraggable.length;) {
                imagesNotDraggable.item(i).setAttribute("class", "draggable");
            }
            frameButtons.setAttribute('class', 'invisible');
            if (tmpBlock.children.length > 0) {
                if (select == 'upload') {inputSaveUpl.disabled = false; inputSaveUpl.classList.add('active');}
                if (select == 'camera') inputSnap.disabled = false;
            }
        } else {

            if (select == 'camera') {
                document.getElementById('select-camera').style.position = 'relative';
                document.getElementById('select-camera').insertBefore(frameButtons, document.querySelector('.snap-save'));
                frameButtons.style.position = 'absolute';
                frameButtons.style.marginLeft = '-350px';
                frameButtons.style.left =  getCoords(document.getElementById('canvas-camera')).left + width + 10 + 'px';
                frameButtons.style.top =  getCoords(document.getElementById('canvas-camera')).top - 150 +  'px';
            } else if (select == 'upload') {
                document.getElementById('canvas-upload-block').style.position = 'relative';
                document.getElementById('canvas-upload-block').appendChild(frameButtons);
                frameButtons.style.position = 'absolute';
                frameButtons.style.marginLeft = '-350px';
                frameButtons.style.left =  getCoords(document.getElementById('canvas-upload')).left + width + 10 + 'px';
                frameButtons.style.top =  getCoords(document.getElementById('canvas-upload')).top - 200 +  'px';
            }

            for (i = 0; i < imagesDraggable.length;) {
                imagesDraggable.item(i).setAttribute("class", "not-draggable");
            }
            if (imagesNotDraggable.length > 0) {
                if (select == 'upload') {
                    inputSaveUpl.disabled = true;
                    inputSaveUpl.classList.add('not-active');
                    inputSaveUpl.classList.remove('active');
                }
            }
            if (document.getElementsByClassName('draggable').length > 0) {
                frameButtons.removeAttribute('class');
                if (select == 'camera') inputSnap.disabled = true;
            }
        }
        clone = Object.assign({}, dragObject);
        dragObject = {};
    }
};
var frameButtons = document.getElementById('frame-buttons');
var inputSaveFrame = document.getElementById('save-frame');
var inputScalePlus = document.getElementById('scale-plus');
var inputScaleMinus = document.getElementById('scale-minus');
var inputClear = document.getElementById('clear');

inputScalePlus.onclick = function () {
    var el = document.querySelector(".draggable");
    var w = el.offsetWidth + 5;
    var h = el.offsetHeight + 5;
    if (w < width / 2 || h < height / 2)
    {
        el.style.width = w + "px";
        el.style.height = h + "px";
    }
};

inputScaleMinus.onclick = function () {
    var el = document.querySelector(".draggable");
    var w = el.offsetWidth - 5;
    var h = el.offsetHeight - 5;
    if (w > 20 || h > 20)
    {
        el.style.width = w + "px";
        el.style.height = h + "px";
    }
};

inputSaveFrame.onclick = function () {
    if (select == 'camera') {
        res_img = convertCanvasToImage(document.createElement('canvas'));
        inputSnap.disabled = false;
        inputSnap.classList.remove('not-active');
        inputSnap.classList.add('active');
    }
    else if (select == 'upload') {
        inputFitted.disabled = true;
        inputFitted.classList.add('not-active');
        inputFitted.classList.remove('active');
        inputScale.disabled = true;
        inputScale.classList.add('not-active');
        inputScale.classList.remove('active');
        inputSaveUpl.disabled = false;
        inputSaveUpl.classList.add('active');
        inputSaveUpl.classList.remove('not-active');
    }
    clone.element.removeAttribute('class');
    var savedFrames = document.getElementById('saved-frames');
    clone.element.style.zIndex = '0';
    clone.element.className = 'droppable';
    savedFrames.appendChild(clone.element);
    frameButtons.setAttribute('class', 'invisible');
    inputClear.removeAttribute('class');

    if (select == 'camera') {
        document.getElementById('select-camera').style.position = 'relative';
        document.getElementById('select-camera').insertBefore(inputClear, document.querySelector('.snap-save'));
        inputClear.style.position = 'absolute';
        inputClear.style.marginLeft = '-350px';
        inputClear.style.left =  getCoords(document.getElementById('canvas-camera')).left + width + 10 + 'px';
        inputClear.style.top =  getCoords(document.getElementById('canvas-camera')).top + 30 + 'px';
    } else if (select == 'upload') {
        document.getElementById('canvas-upload-block').style.position = 'relative';
        document.getElementById('canvas-upload-block').appendChild(frameButtons);
        inputClear.style.position = 'absolute';
        inputClear.style.marginLeft = '10px';
        inputClear.style.left =  getCoords(document.getElementById('canvas-upload')).left + width + 10 + 'px';
        inputClear.style.top =  getCoords(document.getElementById('canvas-upload')).top + 215 + 'px';
    }

    imagesNotDraggable = document.getElementById('frames').getElementsByClassName('not-draggable');
    for (i = 0; i < imagesNotDraggable.length;) {
        imagesNotDraggable.item(i).setAttribute("class", "draggable");
    }
    var img = document.createElement('img');
    img = document.getElementById("frames").appendChild(img);
    img.src = (clone.element).getAttribute("src");
    img.setAttribute("class", 'draggable');
};