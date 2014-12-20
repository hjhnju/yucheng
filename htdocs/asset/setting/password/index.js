define('setting/password/index', function (require) {
    var $ = require('jquery');
    var a = $('.login-username').children('.user-lable');
    function init() {
        $('.login-input').on({
            focus: function () {
                $(this).parent().children('.user-lable').addClass('hidden');
            },
            blur: function () {
                var val = $(this).val();
                !val && a.hasClass('hidden') && a.removeClass('hidden');
            }
        });
    }
    return { init: init };
});