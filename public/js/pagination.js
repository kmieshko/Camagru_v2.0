// var pagination = document.querySelectorAll('.pagination a.nav-link');
// for (var i = 0; i < pagination.length; i++) {
//     pagination.item(i).onclick = function(e) {
//         e.preventDefault();
//         var page = this.getAttribute('data-page');
//         var xhr = new XMLHttpRequest();
//         xhr.open("POST", '/main/qwe/', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         var body = 'page=' + page;
//         xhr.send(body);
//
//         xhr.onreadystatechange = function (res) {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 var gallery = document.querySelector('.gallery');
//                 gallery.innerHTML = null;
//                 gallery.style.visibility = 'hidden';
//             }
//         }
//     };
// }




