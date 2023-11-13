humhub.module('cleanTheme.topNavigation', function (module, require, $) {

    const $topBar = $('#topbar');
    const $topBarContainer = $('#topbar > .container');
    const $topMenuNav = $('#top-menu-nav');
    const $topMenuSub = $('#top-menu-sub');
    const $topMenuDropdown = $('#top-menu-sub-dropdown');
    const $searchMenuNav = $('#search-menu-nav');

    const init = function () {
        $(function () {
            // Add "Search" label to top menu Search entry
            $('#search-menu').append('<br>' + module.config.searchItemLabel);

            // Wait for the end of the resizing
            let doit;
            $(window).on('resize', function () {
                clearTimeout(doit);
                doit = setTimeout(fixNavigationOverflow, 100);
            });
            setTimeout(fixNavigationOverflow, 100);
        });
    };

    const fixNavigationOverflow = function () {
        $topMenuSub.show(); // For isOverflow() test

        while (!isOverflow()) {
            moveFromDropDown('.search-menu', $searchMenuNav);
            if (!moveFromDropDown('.top-menu-item:first', $topMenuNav)) {
                break;
            }
        }

        while (isOverflow()) {
            if (!moveToDropDown('.top-menu-item:last', $topMenuNav)) {
                moveToDropDown('.search-menu', $searchMenuNav);
                break;
            }
        }

        // If drop down sub-menu has items
        if ($topMenuDropdown.children('.top-menu-item').length > 0) {
            $topMenuSub.find('.dropdown-toggle').dropdown();
            $topMenuNav.append($topMenuSub); // Move the dropdown sub menu to the end
        } else {
            $topMenuSub.hide();
        }
    };

    const moveToDropDown = function (itemClass, from) {
        const item = from.children(itemClass);
        if (!item.length) {
            return false;
        }
        item.find('br').remove();
        $topMenuDropdown.prepend(item);
        return true;
    };

    const moveFromDropDown = function (itemClass, to) {
        const item = $topMenuDropdown.children(itemClass);
        if (!item.length) {
            return false;
        }

        const iItem = item.find('a:first > i:first');
        if (iItem) {
            iItem.after('<br/>');
        }
        to.append(item);
        return true;
    };

    const isOverflow = function () {
        return $topBarContainer[0].offsetHeight > $topBar[0].offsetHeight;
    };

    module.export({
        init: init,
        sortOrder: 100
    });
});
