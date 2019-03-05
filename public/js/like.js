var divLike = document.getElementById('like');
if (divLike) {
    divLike.onclick = function () {
        var likeFlag;
        if (divLike.classList.contains('unliked')) {
            divLike.classList.remove('unliked');
            divLike.classList.add('liked');
            likeFlag = 1;
        } else if (divLike.classList.contains('liked')) {
            divLike.classList.remove('liked');
            divLike.classList.add('unliked');
            likeFlag = -1;
        }
        var xhr = new XMLHttpRequest();
        var body = "img=" + src + "&like=" + likeFlag;
        xhr.open('POST', '/main/like-image', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(body);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var countLikes = document.getElementById('count-likes');
                var count = parseInt(countLikes.innerText.replace('Likes: ', ''));
                count += likeFlag;
                countLikes.innerText = 'Likes: ' + count;
            }
        }
    };

    var divComment = document.getElementById('comment');
    divComment.onclick = function () {
        var addCommentContainer = document.getElementById('addCommentContainer');
        addCommentContainer.classList.remove('invisible');
        scrollToElement(addCommentContainer);
    };
}