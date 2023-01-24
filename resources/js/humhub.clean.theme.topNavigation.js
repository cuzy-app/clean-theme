humhub.module('cleanTheme.topNavigation', function (module, require, $) {

    var topBar = $('#topbar');
    var topBarContainer = $('#topbar > .container');
    var topMenuNav = $('#topbar > .container #top-menu-nav');
    var topMenuSub = topMenuNav.find('#top-menu-sub');
    var topMenuDropdown = topMenuSub.find('#top-menu-sub-dropdown');

    var init = function () {

        // Wait for the end of the resizing
        var doit;
        $(window).on('resize', function () {
            clearTimeout(doit);
            doit = setTimeout(fixNavigationOverflow, 100);
        });

        if (!isOverflow()) {
            topBar.css('overflow', '');
            return;
        }

        setTimeout(fixNavigationOverflow, 100);
    };

    var fixNavigationOverflow = function () {
        if (!isOverflow()) {
            if (topMenuSub.is(":visible")) {
                topMenuSub.hide();
                while (!isOverflow() && topMenuDropdown.children('.top-menu-item').length > 0) {
                    moveFirstItemToMenuBar(topMenuDropdown);
                }
                if (topMenuDropdown.children('.top-menu-item').length > 0) {
                    topMenuSub.show();
                }
                if (!isOverflow()) {
                    return;
                }
            } else {
                return;
            }
        }

        topMenuSub.show();

        while (isOverflow() && moveNextItemToDropDown(topMenuDropdown)) {
        }

        // We remove the next dropdown for edgecases, e.g. the scrollbar appears after init
        moveNextItemToDropDown(topMenuDropdown);

        topBar.css('overflow', '');
        topMenuSub.find('.dropdown-toggle').dropdown();

    };

    var moveNextItemToDropDown = function (topMenuDropdown) {
        var $item = topMenuNav.children('.top-menu-item:last');
        if (!$item.length) {
            return false;
        }

        $item.find('br').remove();
        topMenuDropdown.prepend($item);
        return true;
    };

    var moveFirstItemToMenuBar = function (topMenuDropdown) {
        var $item = topMenuDropdown.children('.top-menu-item:first');
        var $iItem = $item.find('a:first > i:first');
        if ($iItem) {
            $iItem.after('<br/>');
        }
        topMenuSub.before($item);
    };

    var isOverflow = function () {
        return topBarContainer[0].offsetHeight > topBar[0].offsetHeight;
    };

    module.export({
        init: init,
        sortOrder: 100
    });
});
