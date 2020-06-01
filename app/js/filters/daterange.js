var DateRange = (function ($) {

    function init() {
        $('.datepicker').pickadate();
    }

    return {
        init: init,
    }
})(jQuery);
$(DateRange.init());
