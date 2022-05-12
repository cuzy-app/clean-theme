humhub.module('ui.theme', function (module, require, $) {
    module.export({
        getContentTop: function () {
            var $topBar = $('#topbar');
            return $topBar.offset().top + $topBar.height() - $(window).scrollTop();
        }
    });
});