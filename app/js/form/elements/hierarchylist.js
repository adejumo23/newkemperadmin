var HierarchyList = function (options) {
    "use strict";
    var element = $(options.element);
    var elementClass = $(element).attr('class');
    var closecallback = options.closeCallback;

    var getElement = function () {
        element = $("." + elementClass);
        return element;
    };

    var handleItemClick = function (e) {
        var data = getElement().find('ul').data();
        if (data['url']) {
            storePrevState();
            data = {...data, ...$(this).find('a').data()};
            doPostData(data['url'], data, getElement());
        }
    };


    function getStorageKey() {
        return 'hierarchyListPrevState' + getElement().find('ul').attr('id');
    }

    var storePrevState = function () {
        var data = loadData(getStorageKey());
        data.push(getElement().parent().html());
        saveData(data);
    };

    var loadPrevState = function () {
        var data = loadData(getStorageKey());
        var prevHtml = data.pop();
        saveData(data);
        getElement().replaceWith(prevHtml);
    };

    var loadData = function (key) {
        var dataString = localStorage.getItem(key);
        if (dataString === null) {
            return [];
        }
        return JSON.parse(dataString);
    };

    var saveData = function (data) {
        if (data.length === 0) {
            localStorage.removeItem(getStorageKey());
            return;
        }
        localStorage.setItem(getStorageKey(), JSON.stringify(data));
    };

    function doPostData(url, data, el) {
        $.post(url, data, function (response) {
            response = JSON.parse(response);
            if (response.success) {
                el.replaceWith(response.data);
            }
        });
    }

    var doLoadInitialState = function () {
        if (getElement().data('state') !== "loading") {
            getElement().data('state', 'loading');
            //loadData here
            var data = getElement().find('ul').data();
            var url = data['url'];
            var el = getElement();
            doPostData(url, data, el);
            localStorage.removeItem(getStorageKey());
            getElement().data('state', 'done');
        }
    };

    var closeSideBar = function () {
        if (closecallback !== undefined) {
            closecallback();
        }
    };

    var handleGoBack = function () {
        if (localStorage.getItem(getStorageKey()) !== null) {
            loadPrevState();
        } else {
            closeSideBar();
        }
    };

    function setCloseCallback($callback) {
        closecallback = $callback;
    }

    var init = function () {
        doLoadInitialState();
        $(document).on('click', '.heirarchy', handleItemClick);
        $(document).on('click', '.close-hierarchyList', handleGoBack);
    };

    init();
    return {
        handleItemClick: handleItemClick,
        setCloseCallback: setCloseCallback
    }
};

(function ($) {

    $.fn.hierarchyList = function (options) {
        $.fn.hierarchyList.defaults = {
            element:this
        };
        var opts = $.extend({}, $.fn.hierarchyList.defaults, options);
        return this.each(function () {
            var hl = new HierarchyList(opts);
        });

    };
})(jQuery);

// Usage example:
// $(".hierarchyList").hierarchyList();