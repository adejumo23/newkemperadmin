var ProductionDashoard = (function ($) {
    function handleDocumentReady() {
        $(".hierarchyList").hierarchyList({
            'closeCallback' : closeSideBar
        });


    }
    var closeSideBar = function () {
        $('#sidenav-overlay').trigger('click');
    };

    function init() {
        $(document).ready(handleDocumentReady);
    }

    return {
        init: init,
        handleDocumentReady:handleDocumentReady,
    }
})(jQuery);
$(ProductionDashoard.init());