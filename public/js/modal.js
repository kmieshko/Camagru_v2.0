var items = document.querySelectorAll(".item");
var src;
for (var i = 0; i < items.length; i++) {
    items.item(i).onclick = function () {
        var img = this.getElementsByTagName('img').item(0);
        src = img.src.match(/\/public\/images\/[a-zA-Z0-9]+\.png/)[0];
        var xhr = new XMLHttpRequest();
        var body = 'img=' + src;
        xhr.open('POST', '/main/modal', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(body);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var modal = extractJSON(xhr.responseText);
                var divModal = document.getElementById('modal');
                divModal.innerHTML = modal.html;
                include("/public/js/like.js");
                include("/public/js/delete-comment.js");
                include("/public/js/delete-image.js");
                var btnSubmit = document.getElementById('btnSubmit');
                btnSubmit.onclick = function () {
                    addComment();
                };
            }
        };
    };
}

function addComment() {

    var xhr = new XMLHttpRequest();
    var text = document.getElementById('body').value.trim();
    if (text) {
        var body = 'body=' + text + '&img=' + src;
        xhr.open('POST', '/main/comment-image', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(body);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var comment = extractJSON(xhr.responseText);
                var divComment = document.createElement('div');
                divComment.className = 'comment';
                console.log(xhr.responseText);
                divComment.innerHTML = comment.html;
                document.getElementById('container-comment').appendChild(divComment);
                document.getElementById('body').value = '';
                divComment.querySelector('.delete-comment').addEventListener('click', function () {
                    var date = this.parentElement.parentElement.querySelector('.date').innerText;
                    var comment = this.parentElement.parentElement;
                    var xhr = new XMLHttpRequest();
                    var body = 'date=' + date;
                    xhr.open('POST', '/main/delete-comment', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send(body);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            comment.remove();
                        }
                    };
                });
            }
        }
    }
}

document.location.hash = '';