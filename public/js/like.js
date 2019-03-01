var divLike = document.getElementById('like');
divLike.onclick = function () {
    var likeFlag;
    if (divLike.classList.contains('unliked')) {
        divLike.classList.remove('unliked');
        divLike.classList.add('liked');
        likeFlag = 1;
    } else if (divLike.classList.contains('liked')) {
        divLike.classList.remove('liked');
        divLike.classList.add('unliked');
        likeFlag = 0;
    }
    var xhr = new XMLHttpRequest();
    var body = "img=" + src + "&like=" + likeFlag;
    xhr.open('POST', '/main/like-image', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(body);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {

        }
    }
};

var divComment = document.getElementById('comment');
divComment.onclick = function () {
    var addCommentContainer = document.getElementById('addCommentContainer');
    addCommentContainer.classList.remove('invisible');
    scrollToElement(addCommentContainer);
};

