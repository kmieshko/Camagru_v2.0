var comment = document.getElementById('comment');
if (comment)  {
    var delComments = document.querySelectorAll(".delete-comment");
    for (var i = 0; i < delComments.length; i++) {
        delComments.item(i).onclick = function () {
            var date = this.parentElement.parentElement.querySelector('.date').innerText;
            var text = this.parentElement.innerText;
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
        };
    }
}