function include(url) {
    var script = document.createElement('script');
    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}

function convertCanvasToImage(canvas) {
    var new_image = new Image();
    new_image.src = canvas.toDataURL("image/png");
    return new_image;
}

function extractJSON(str) {
    var firstOpen, firstClose, candidate;
    firstOpen = str.indexOf('{', firstOpen + 1);
    while(firstOpen != -1) {
        firstClose = str.lastIndexOf('}');
        if(firstClose <= firstOpen) {
            return null;
        }
        while(firstClose > firstOpen) {
            candidate = str.substring(firstOpen, firstClose + 1);
            try {
                var res = JSON.parse(candidate);
                return res;
            }
            catch(e) {
            }
            firstClose = str.substr(0, firstClose).lastIndexOf('}');
        }
        firstOpen = str.indexOf('{', firstOpen + 1);
    }
}

function ScaleImage(srcwidth, srcheight, targetwidth, targetheight, fLetterBox) {

    var result = {width: 0, height: 0, fScaleToTargetWidth: true};
    if ((srcwidth <= 0) || (srcheight <= 0) || (targetwidth <= 0) || (targetheight <= 0)) {
        return result;
    }
    var scaleX1 = targetwidth;
    var scaleY1 = (srcheight * targetwidth) / srcwidth;
    var scaleX2 = (srcwidth * targetheight) / srcheight;
    var scaleY2 = targetheight;
    var fScaleOnWidth = (scaleX2 > targetwidth);
    if (fScaleOnWidth) {
        fScaleOnWidth = fLetterBox;
    }
    else {
        fScaleOnWidth = !fLetterBox;
    }

    if (fScaleOnWidth) {
        result.width = Math.floor(scaleX1);
        result.height = Math.floor(scaleY1);
        result.fScaleToTargetWidth = true;
    }
    else {
        result.width = Math.floor(scaleX2);
        result.height = Math.floor(scaleY2);
        result.fScaleToTargetWidth = false;
    }
    result.targetleft = Math.floor((targetwidth - result.width) / 2);
    result.targettop = Math.floor((targetheight - result.height) / 2);
    return result;
}

function scrollToElement(theElement) {
    var selectedPosX = 0;
    var selectedPosY = 0;
    while (theElement != null) {
        selectedPosX += theElement.offsetLeft;
        selectedPosY += theElement.offsetTop;
        theElement = theElement.offsetParent;
    }
    window.scrollTo(selectedPosX, selectedPosY);
}