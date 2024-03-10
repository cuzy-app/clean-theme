humhub.module('cleanTheme.topNavigation', function (module, require, $) {

    module.initOnPjaxLoad = true;

    let $leftNav;
    let $topBarHeight;
    let $leftNavTop;
    let $leftNavIsFixed = false; // Only to improve performance while scrolling
    let resizeTimeout;

    const leftNavDistFromTopBar = 15;

    const $body = $('body');
    const $topMenu = $('#topbar');
    const $topMenuContainer = $('#topbar > .container');
    const $topMenuNavOrBottomMenu = $('#top-menu-nav');
    const $topMenuSub = $('#top-menu-sub');
    const $topMenuDropdown = $('#top-menu-sub-dropdown');


    const init = function () {
        $(function () {

            // Hide menus on scroll top
            hideMenusOnScrollTop(module.config.hideTopMenuOnScrollDown, module.config.hideBottomMenuOnScrollDown);

            // Waiting for the end of the resizing and setting up a window resize event listener
            let resizeTimeout;
            $(window).on('resize', function () {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(fixNavigationOverflow, 100);
            });
            setTimeout(fixNavigationOverflow, 100);

            // Top menu -> icon buttons: update active status
            updateBtnStatus('search-menu', 'search', 'search');
            updateBtnStatus('icon-notifications', 'notification', 'overview');
            updateBtnStatus('icon-messages', 'mail', 'mail');
            updateBtnStatus('icon-activity-web-summary', 'activity-web-summary', 'latest');

            // Make the left menu fixed when scrolling down
            $leftNav = $('.left-navigation');
            handelWindowSize();
            $(window).off('resize', handelWindowSize);
            $(window).on('resize', handelWindowSize);
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
            if (!moveFromDropDown('.top-menu-item:first', $topMenuNavOrBottomMenu)) {
                break;
            }
        }

        while (isOverflow()) {
            if (!moveToDropDown('.top-menu-item:last', $topMenuNavOrBottomMenu)) {
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
            let bodyHeightDiffWithWindow = $('body').height() - $(window).height();

            // Prevent freezing when scrolling down if the end of page is in the bottom menu (document height is slightly smaller than the screen height)

            if (bodyHeightDiffWithWindow <= 0) {
                return;
            }

            let newScrollTop = $(this).scrollTop();

            // Prevent false scroll down due to "elastic scrolling"
            // or "scroll bounce" on iOs when scrolling to the top of the page
            if (newScrollTop < 0) {
                newScrollTop = 0;
            }
            if (newScrollTop > bodyHeightDiffWithWindow) {
                newScrollTop = bodyHeightDiffWithWindow;
            }

            if (newScrollTop && newScrollTop > 10 && newScrollTop >= lastScrollTop) { // Scrolling down
                if (hideTopMenuOnScrollDown) {
                    $body.addClass('hide-top-menu');
                }
                if (hideBottomMenuOnScrollDown) {
                    $body.addClass('hide-bottom-menu');
                }
            } else { // Scrolling up
                if (hideTopMenuOnScrollDown) {
                    $body.removeClass('hide-top-menu');
                }
                if (hideBottomMenuOnScrollDown) {
                    $body.removeClass('hide-bottom-menu');
                }
            }

            lastScrollTop = newScrollTop;
        });
    };

    function handelWindowSize() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            if (!$leftNav.length) {
                return;
            }

            $(window).off('scroll', switchFixedPanels);
            removeLeftNavFixed();

            $topBarHeight = $('#topbar').height();
            $leftNavTop = $leftNav.offset().top;

            // Force width to keep the same when position is fixed
            $leftNav.css("width", "");
            $leftNav.width($leftNav.width());

            if ($leftNav.parent().css('float') === 'left') {
                const availableHeightForSidebar = $(window).height() - $topBarHeight - leftNavDistFromTopBar;
                if ($leftNav.height() < availableHeightForSidebar) {
                    switchFixedPanels();
                    $(window).on('scroll', switchFixedPanels);
                }
            }
        }, 100);
    }

    const switchFixedPanels = function () {
        const $scrollTop = $(window).scrollTop();
        const distanceFromTopBar = $leftNavTop - $scrollTop - $topBarHeight;
        if (distanceFromTopBar < leftNavDistFromTopBar) {
            addLeftNavFixed();
        } else {
            removeLeftNavFixed();
        }
    };

    const addLeftNavFixed = function () {
        if (!$leftNavIsFixed) {
            $leftNav.css({
                'position': 'fixed',
                'top': ($topBarHeight + leftNavDistFromTopBar) + 'px'
            });
            $leftNavIsFixed = true;
        }
    };

    const removeLeftNavFixed = function () {
        if ($leftNavIsFixed) {
            $leftNav.css({
                'position': 'static',
                'top': 'auto'
            });
            $leftNavIsFixed = false;
        }
    };

    /**
     * Updates the active status of a button based on the current state of the UI module.
     * @param {string} btnId - The ID of the button.
     * @param {string} moduleId - The ID of the module.
     * @param {string} controllerId - The ID of the controller.
     */
    const updateBtnStatus = function (btnId, moduleId, controllerId) {
        const state = humhub.modules.ui.view.getState();
        const searchBtn = $('#' + btnId).parent();
        if (searchBtn.length) {
            if (state.moduleId === moduleId && state.controllerId === controllerId) {
                searchBtn.addClass('active');
            } else {
                searchBtn.removeClass('active');
            }
        }
    };

    module.export({
        init: init,
        sortOrder: 100
    });
});
