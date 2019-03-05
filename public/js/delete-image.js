var deleteImg = document.getElementById('delete-image');
if (deleteImg) {
    deleteImg.onclick = function () {
        var xhr = new XMLHttpRequest();
        var body = "img=" + src;
        xhr.open('POST', '/gallery/delete-image', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(body);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                location.reload();
            }
        }
    }
}