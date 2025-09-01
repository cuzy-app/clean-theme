humhub.module('cleanTheme.leftNavigation', function (module, require, $) {

    module.initOnPjaxLoad = true;

    const leftNavDistFromTopBar = 15;

    let $topBarHeight;
    let resizeTimeout;
    let $menu;
    let $collapseBtn;
    let $expandBtn;
    let rowChildren;
    let navContainer;
    let contentContainer;
    let navContainerColNb;
    let contentContainerColNb;
    let isReady = false;
    let $leftNav;
    let $leftNavTop;
    let $leftNavIsFixed = false; // Only to improve performance while scrolling

    const init = function () {
        $(function () {
            $menu = $('#' + module.config.menuId);
            if (!$menu.length || $menu.hasClass('d-none')) {
                return;
            }
            $collapseBtn = $('#' + module.config.collapseBtn);
            $expandBtn = $('#' + module.config.expandBtn);
            rowChildren = $menu.parents('.row').children();
            navContainer = rowChildren.eq(0);
            contentContainer = rowChildren.eq(1);
            navContainerColNb = nbColFromClass(navContainer);
            contentContainerColNb = nbColFromClass(contentContainer);

            $collapseBtn.on('click', function () {
                collapseMenu();
            });
            $expandBtn.on('click', function () {
                expandMenu();
            });

            isReady = true;

            // Make the left menu fixed when scrolling down
            $leftNav = $('.left-navigation');
            handelWindowSize();
            $(window).off('resize', handelWindowSize);
            $(window).on('resize', handelWindowSize);
        });
    };

    const nbColFromClass = function (element) {
        const colNbs = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        for (const colNb of colNbs) {
            if (element.hasClass('col-xl-' + colNb)) {
                return colNb;
            }
        }
        return null;
    };

    const collapseMenu = function () {
        function waitForInit() {
            if (isReady && $menu.length) {
                if (!$menu.hasClass('d-none')) {
                    $menu.addClass('d-none');
                    navContainer.removeClass('col-xl-' + navContainerColNb);
                    navContainer.addClass('col-xl-12');
                    contentContainer.removeClass('col-xl-' + contentContainerColNb);
                    contentContainer.addClass('col-xl-' + (contentContainerColNb + navContainerColNb));
                    $expandBtn.removeClass('d-none');
                }
            } else {
                window.setTimeout(waitForInit, 100);
            }
        }

        waitForInit();
    };

    const expandMenu = function () {
        function waitForInit() {
            if (isReady && $menu.length) {
                if ($menu.hasClass('d-none')) {
                    $menu.removeClass('d-none');
                    navContainer.removeClass('col-xl-12');
                    navContainer.addClass('col-xl-' + navContainerColNb);
                    contentContainer.removeClass('col-xl-' + (contentContainerColNb + navContainerColNb));
                    contentContainer.addClass('col-xl-' + contentContainerColNb);
                    $expandBtn.addClass('d-none');
                }
            } else {
                window.setTimeout(waitForInit, 100);
            }
        }

        waitForInit();
    };

    function handelWindowSize() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            if (!$leftNav.length) {
                return;
            }

            // Reset
            $(window).off('scroll', switchFixedPanels);
            removeLeftNavFixed();
            $leftNav.css("width", "");

            // Get new values
            const $topBar = $('#topbar');
            $topBarHeight = parseInt($topBar.css('top')) + $topBar.height();
            $leftNavTop = $leftNav.offset().top;

            // Force width to keep the same when position when fixed
            $leftNav.width($leftNav.width());

            const availableHeightForSidebar = $(window).height() - $topBarHeight - leftNavDistFromTopBar;
            if ($leftNav.height() < availableHeightForSidebar) {
                switchFixedPanels();
                $(window).on('scroll', switchFixedPanels);
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

    module.export({
        init: init,
        collapseMenu: collapseMenu,
        expandMenu: expandMenu
    });
});
