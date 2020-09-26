(function ($) {
    $(document).ready(function () {
        var $chatbox = $('.chatbox'),
            $chatboxTitle = $('.chatbox__title'),
            $chatboxTitleClose = $('.chatbox__title__close'),
            $chatboxCredentials = $('.chatbox__credentials');
        $chatboxopen = $('.chatbox_open');
        $chatboxTitle.on('click', function () {
            $chatbox.toggleClass('chatbox--tray');
            $chatbox.addClass('chatbox--closed');
            $chatboxopen.removeClass('hide_chatbox');
        });
        $chatboxTitleClose.on('click', function (e) {
            e.stopPropagation();
            $chatbox.toggleClass('chatbox--tray');
            $chatbox.addClass('chatbox--closed');
            $chatboxopen.removeClass('hide_chatbox');
        });
        // $chatbox.on('transitionend', function () {
        //     if ($chatbox.hasClass('chatbox--closed')) $chatbox.remove();
        // });
        $chatboxopen.on('click', function (e) {
            e.stopPropagation();
            $chatbox.toggleClass('chatbox--tray');
            $chatbox.removeClass('chatbox--closed');
            $chatboxopen.addClass('hide_chatbox');
        });
    });
})(jQuery);


// (function ($) {
//     $(document).ready(function () {
//         var $chatbox = $('.chatbox'),
//             $chatboxTitle = $('.chatbox__title'),
//             $chatboxTitleClose = $('.chatbox__title__close'),
//             $chatboxCredentials = $('.chatbox__credentials');
//         $chatboxopen = $('.chatbox_open');
//         $chatboxTitle.on('click', function () {
//             console.log('test');
//             $chatbox.toggleClass('chatbox--tray');
//         });
//         $chatboxTitleClose.on('click', function (e) {
//             console.log('close chatbox__title');
//             e.stopPropagation();
//             $chatbox.addClass('chatbox--closed');
//             $chatboxopen.removeClass('hide_chatbox');
//         });
//         // $chatbox.on('transitionend', function () {
//         //     if ($chatbox.hasClass('chatbox--closed')) $chatbox.remove();
//         // });
//         $chatboxopen.on('click', function (e) {
//             console.log('open chatbox__title');
//             e.stopPropagation();
//             $chatbox.removeClass('chatbox--closed');
//             $chatboxopen.addClass('hide_chatbox');
//         });
//     });
// })(jQuery);
