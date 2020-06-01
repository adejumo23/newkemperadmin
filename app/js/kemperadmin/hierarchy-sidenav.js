$(document).ready(function () {
    $(".hierarchy-collapse").sideNav({
        // breakpoint: 1200,
        edge: 'right'
    });
    // SideNav Scrollbar Initialization
    var sideNavScrollbar1 = document.querySelector('.custom-scrollbar-right');
    var ps1 = new PerfectScrollbar(sideNavScrollbar1, {
        wheelSpeed: 2,
        wheelPropagation: true,
        minScrollbarLength: 20
    });
});