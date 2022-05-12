humhub.module('clean.theme.topNavigation', function (module, require, $) {

    var $topBar = $('#topbar');
    var $topBarContainer = $('#topbar > .container');
    var $topMenuNav = $('#topbar > .container #top-menu-nav');

    var init = function () {

        // Wait for the end of the resizing
        var doit;
        $(window).on('resize', function () {
            clearTimeout(doit);
            doit = setTimeout(fixNavigationOverflow, 100);
        });

        if (!isOverflow()) {
            $topBar.css('overflow', '');
            return;
        }

        setTimeout(fixNavigationOverflow, 100);
    };

    var fixNavigationOverflow = function () {
        if (!isOverflow()) {
            return;
        }

        var $topMenuDropdown = $topMenuNav.find('#top-menu-sub').show().find('#top-menu-sub-dropdown');

        while (isOverflow() && moveNextItemToDropDown($topMenuDropdown)) {
        }

        // We remove the next dropdown for edgecases, e.g. the scrollbar appears after init
        moveNextItemToDropDown($topMenuDropdown);

        $topBar.css('overflow', '');
        $topMenuNav.find('#top-menu-sub').find('.dropdown-toggle').dropdown();

    };

    var moveNextItemToDropDown = function ($topMenuDropdown) {
        var $item = $topMenuNav.children('.top-menu-item:last');
        if (!$item.length) {
            return false;
        }

        $item.find('br').remove();
        $topMenuDropdown.prepend($item);
        return true;
    };

    var isOverflow = function () {
        return $topBarContainer[0].offsetHeight > $topBar[0].offsetHeight;
    };

    module.export({
        init: init,
        sortOrder: 100,
    });
});