humhub.module('cleanTheme.leftNavigation', function (module, require, $) {

    module.initOnPjaxLoad = true;

    let menu;
    let expandBtn;
    let collapseBtn;
    let rowChildren;
    let navContainer;
    let contentContainer;
    let navContainerColNb;
    let contentContainerColNb;
    let isReady = false;

    const init = function () {
        $(function () {
            menu = $('#' + module.config.menuId);
            if (!menu.length || menu.is(':hidden')) {
                return;
            }
            collapseBtn = $('#' + module.config.collapseBtn);
            expandBtn = $('#' + module.config.expandBtn);
            rowChildren = menu.parents('.row').children();
            navContainer = rowChildren.eq(0);
            contentContainer = rowChildren.eq(1);
            navContainerColNb = nbColFromClass(navContainer);
            contentContainerColNb = nbColFromClass(contentContainer);

            collapseBtn.on('click', function () {
                collapseMenu();
            });
            expandBtn.on('click', function () {
                expandMenu();
            });

            isReady = true;
        });
    };

    const nbColFromClass = function (element) {
        const colNbs = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        for (const colNb of colNbs) {
            if (element.hasClass('col-md-' + colNb)) {
                return colNb;
            }
        }
        return null;
    };

    const collapseMenu = function () {
        function waitForInit() {
            if (isReady && menu.length) {
                if (menu.is(':visible')) {
                    menu.hide();
                    navContainer.removeClass('col-md-' + navContainerColNb);
                    navContainer.addClass('col-md-12');
                    contentContainer.removeClass('col-md-' + contentContainerColNb);
                    contentContainer.addClass('col-md-' + (contentContainerColNb + navContainerColNb));
                    expandBtn.removeClass('hidden');
                }
            } else {
                window.setTimeout(waitForInit, 100);
            }
        }

        waitForInit();
    };

    const expandMenu = function () {
        function waitForInit() {
            if (isReady && menu.length) {
                if (menu.is(':hidden')) {
                    menu.show();
                    navContainer.removeClass('col-md-12');
                    navContainer.addClass('col-md-' + navContainerColNb);
                    contentContainer.removeClass('col-md-' + (contentContainerColNb + navContainerColNb));
                    contentContainer.addClass('col-md-' + contentContainerColNb);
                    expandBtn.addClass('hidden');
                }
            } else {
                window.setTimeout(waitForInit, 100);
            }
        }

        waitForInit();
    };

    module.export({
        init: init,
        collapseMenu: collapseMenu,
        expandMenu: expandMenu
    });
});