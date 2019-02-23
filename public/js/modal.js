var container = document.querySelector('.container');
var children = container.getElementsByTagName('div');
var src;
var title;
var modal = document.getElementById('modal');

for (var i = 0; i < children.length; i++) {
    var child = children.item(i);
    child.onclick = function(e) {
        for (var j = 0; j < e.path.length; j++) {
            var item = e.path[j];
            if (item.className === 'item') {
                title = item.getElementsByTagName('label').item(0).innerHTML;
                src = item.getElementsByTagName('a').item(0).getElementsByTagName('img').item(0).src;
                src = src.match(/\/public\/images\/[a-zA-Z0-9]+\.png/)[0];
                modal.querySelector('img').src = src;
                modal.getElementsByTagName('header').item(0).getElementsByTagName('label').item(0).innerText = title;
                document.body.appendChild(modal);
            }
        }
    };
}

document.location.hash = '#';