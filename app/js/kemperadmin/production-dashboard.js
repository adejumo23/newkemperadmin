var ProductionDashoard = (function ($) {
    function handleDocumentReady() {
        // return;
        // var $rightSideNavContainer = $('#right-side-nav');
        // debugger;
        // var $hierarchyListParent = $("div.hierarchyList").parent();
        // $("div.hierarchyList").remove();
        // $hierarchyListParent.append($hierarchyList);

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
        handleDocumentReady:handleDocumentReady
    }
})(jQuery);
$(ProductionDashoard.init());
