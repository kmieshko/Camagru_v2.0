var items = document.getElementsByClassName('item');
for (var i = 0; i < items.length; i++) {
    items.item(i)
}

document.addEventListener('DOMContentLoaded', function() {
    var btnSubmit = document.getElementById('btnSubmit');
    btnSubmit.onclick = function (e) {

        var xhr = new XMLHttpRequest();
        var body =  'body=' + document.getElementById('body').value + '&img=' + document.querySelector('.front').src.match(/\/public\/images\/[a-zA-Z0-9]+\.png/)[0];
        xhr.open('POST', '/main/comment-image', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(body);
        console.log(body);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var comment = extractJSON(xhr.responseText);
                var divComment = document.createElement('div');
                divComment.className = 'comment';
                divComment.innerHTML = comment.html;
                document.getElementById('main').insertBefore(divComment, document.getElementById('addCommentContainer'));
                document.getElementById('body').value = '';
            }
        }
    };
});


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

// $(document).ready(function(){
//     /* Следующий код выполняется только после загрузки DOM */
//
//     /* Данный флаг предотвращает отправку нескольких комментариев: */
//     var working = false;
//
//     /* Ловим событие отправки формы: */
//     $('#addCommentForm').submit(function(e){
//
//         e.preventDefault();
//         if(working) return false;
//
//         working = true;
//         $('#btnSubmit').val('Занято...');
//         $('span.error').remove();
//
//         /* Отправляем поля формы в submit.php: */
//         console.log($(this).serialize());
//         $.post('/main/comment-image',$(this).serialize(),function(msg){
//
//             working = false;
//             $('#submit').val('Отправить');
//             //
//             // if(msg.status){
//             //
//             //     /*
//             //     /	Если вставка была успешной, добавляем комментарий
//             //     /	ниже последнего на странице с эффектом slideDown
//             //     /*/
//             //
//             //     $(msg.html).hide().insertBefore('#addCommentContainer').slideDown();
//             //     $('#body').val('');
//             // }
//             // else {
//             //
//             //     /*
//             //     /	Если есть ошибки, проходим циклом по объекту
//             //     /	msg.errors и выводим их на страницу
//             //     /*/
//             //
//             //     $.each(msg.errors,function(k,v){
//             //         $('label[for='+k+']').append('<span class="error">'+v+'</span>');
//             //     });
//             // }
//     //     },'json');
//     // });
//
// });