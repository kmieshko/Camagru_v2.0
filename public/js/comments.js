document.addEventListener('DOMContentLoaded', function() {
    var working = false;

    var addCommentForm = document.getElementById('addCommentForm');
    addCommentForm.onsubmit = function (e) {
        e.preventDefault();
        if (working) return false;
        working = true;
        var send = document.getElementById('btnSubmit');
        send.value = "...";
        // document.querySelector('.error').remove();

        var xhr = new XMLHttpRequest();
        // var body = serialize(this);
        var body = serialize(this) + '&img=' + document.querySelector('.front').src.match(/\/public\/images\/[a-zA-Z0-9]+\.png/)[0];
        xhr.open('POST', '/main/comment-image', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(body);
        console.log(body);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                working = false;
                send.value = "Send";
                var comment = extractJSON(xhr.responseText);
                console.log(comment);
                document.body.innerHTML = document.body.innerHTML + comment.html;

                // console.log(JSON.parse(this.responseText));

                // document.body.insertBefore(asd, document.getElementById('addCommentContainer'));
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


var serialize = function (form) {

    // Setup our serialized data
    var serialized = [];

    // Loop through each field in the form
    for (var i = 0; i < form.elements.length; i++) {

        var field = form.elements[i];

        // Don't serialize fields without a name, submits, buttons, file and reset inputs, and disabled fields
        if (!field.name || field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') continue;

        // If a multi-select, get all selections
        if (field.type === 'select-multiple') {
            for (var n = 0; n < field.options.length; n++) {
                if (!field.options[n].selected) continue;
                serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[n].value));
            }
        }

        // Convert field data to a query string
        else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
            serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
        }
    }

    return serialized.join('&');

};


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