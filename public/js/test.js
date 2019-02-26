var items = document.querySelectorAll("[id^='modal-']");
for (var i = 0; i < items.length; i++) {
    items.item(i).onclick = function (e) {
        var img = this.getElementsByTagName('img').item(0);
        var src = img.src.match(/\/public\/images\/[a-zA-Z0-9]+\.png/)[0];

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
            }
        };
    };
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

document.location.hash = '';