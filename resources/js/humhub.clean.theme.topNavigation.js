humhub.module('cleanTheme.topNavigation', function (module, require, $) {

    const $body = $('body');
    const $topMenu = $('#topbar');
    const $topMenuContainer = $('#topbar > .container');
    const $topMenuNavOrBottomMenu = $('#top-menu-nav');
    const $topMenuSub = $('#top-menu-sub');
    const $topMenuDropdown = $('#top-menu-sub-dropdown');
    const $searchMenuNav = $('#search-menu-nav');

    const init = function () {
        $(function () {
            // Add "Search" label to top menu Search entry
            $('#search-menu').append('<br>' + module.config.searchItemLabel);

            // Hide menus on scroll top
            hideMenusOnScrollTop(module.config.hideTopMenuOnScrollDown, module.config.hideBottomMenuOnScrollDown);

            // Waiting for the end of the resizing and setting up a window resize event listener
            let resizeTimeout;
            $(window).on('resize', function () {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(fixNavigationOverflow, 100);
            });
            setTimeout(fixNavigationOverflow, 100);
        });
    };

    /**
     * Fix navigation overflow by moving menu items between navigation and dropdown menus.
     * This function checks if the navigation menu is overflowing, and if so, moves items to the dropdown menu.
     * It also checks if the dropdown menu is overflowing, and if so, moves items back to the navigation menu.
     * Finally, it moves the dropdown submenu to the end of the navigation menu if it has items, or hides it otherwise.
     *
     * @function fixNavigationOverflow
     */
    const fixNavigationOverflow = function () {
        $topMenuSub.show(); // For isOverflow() calculations (will be hidden at the end of this function)

        while (!isOverflow()) {
            // moveFromDropDown('.search-menu', $searchMenuNav);
            if (!moveFromDropDown('.top-menu-item:first', $topMenuNavOrBottomMenu)) {
                break;
            }
        }

        while (isOverflow()) {
            if (!moveToDropDown('.top-menu-item:last', $topMenuNavOrBottomMenu)) {
                // moveToDropDown('.search-menu', $searchMenuNav);
                break;
            }
        }

        // If drop-down submenu has items
        if ($topMenuDropdown.children('.top-menu-item').length > 0) {
            $topMenuSub.find('.dropdown-toggle').dropdown();
            $topMenuNavOrBottomMenu.append($topMenuSub); // Move the dropdown submenu to the end
        } else {
            $topMenuSub.hide();
        }

        // Change sub menu drop-down to drop-up if in the bottom menu (mobile view)
        if (isMobileView()) {
            $topMenuSub.removeClass('dropdown').addClass('dropup');
        } else {
            $topMenuSub.removeClass('dropup').addClass('dropdown');
        }
    };

    /**
     * Moves an item from the main menu bar to the drop-down menu
     *
     * @param {string} itemClass - The class of the item to be moved.
     * @param {jQuery} from - The element from which the item should be moved.
     * @returns {boolean} - True if the item was successfully moved, false otherwise.
     */
    const moveToDropDown = function (itemClass, from) {
        const item = from.children(itemClass);
        if (!item.length) {
            return false;
        }
        item.find('br').remove();
        $topMenuDropdown.prepend(item);
        return true;
    };

    /**
     * Moves an item from a dropdown menu to the main menu bar
     *
     * @param {string} itemClass - The class of the item in the dropdown menu to be moved.
     * @param {jQuery} to - The jQuery object representing the target location where the item will be moved to.
     * @returns {boolean} - True if the item was successfully moved, false otherwise.
     */
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

    /**
     * Checks if the top bar container overflows the top bar
     *
     * @returns {boolean} - True if the top bar container overflows the top bar, false otherwise
     */
    const isOverflow = function () {
        if (isMobileView()) {
            return $topMenuNavOrBottomMenu.outerWidth() > $topMenu.outerWidth();
        }
        return $topMenuContainer[0].offsetHeight > $topMenu[0].offsetHeight;
    };

    /**
     * Determines if the current view is a mobile view (small screen)
     * @return {Boolean} True if the view is a mobile view, false otherwise.
     */
    const isMobileView = function () {
        return $topMenuNavOrBottomMenu.css('position') === 'fixed';
    };

    const hideMenusOnScrollTop = function (hideTopMenuOnScrollDown, hideBottomMenuOnScrollDown) {
        let lastScrollTop = 0;

        $(window).on("scroll", function () {
            let newScrollTop = $(this).scrollTop();
            if ($body.height() > $(window).height()) { // Prevent freezing when scrolling down if the end of page is in the bottom menu
                if (newScrollTop > lastScrollTop) { // Scrolling down
                    if (hideTopMenuOnScrollDown) {
                        $body.addClass('hide-top-menu');
                    }
                    if (hideBottomMenuOnScrollDown) {
                        $body.addClass('hide-bottom-menu');
                    }
                } else {
                    if (hideTopMenuOnScrollDown) { // Scrolling up
                        $body.removeClass('hide-top-menu');
                    }
                    if (hideBottomMenuOnScrollDown) {
                        $body.removeClass('hide-bottom-menu');
                    }
                }
            }
            lastScrollTop = newScrollTop;
        });
    };

    module.export({
        init: init,
        sortOrder: 100
    });
});
