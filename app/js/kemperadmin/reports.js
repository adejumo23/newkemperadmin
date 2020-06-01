var Reports = (function ($) {
    "use strict";

    var reportStatusContainerId = "reportStatus";
    var queuedReports = [];

    function getReportStatusContainer() {
        return $('#' + reportStatusContainerId);
    }

    function loadRecentReports() {
        $('.loading-reports').show();
        $('.no-recent-reports').hide();
        var $reportStatusContainer = getReportStatusContainer();
        var url = $reportStatusContainer.data('url');
        $.ajax({
            url: url,
            type : 'post',
            success : function (response) {
                response = JSON.parse(response);
                if (response.status) {
                    $('.loading-reports').hide();
                    getReportStatusContainer().append(response.body);
                } else {
                    $('.loading-reports').hide();
                    $('.no-recent-reports').show();
                }
            }
        });
    }

    function handleReportGenerate(e) {
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action');
        var data = $form.serializeArray();
        $.post(url, data, function (response) {
            response = JSON.parse(response);
            if (response.status) {
                queuedReports.push(response.reportData)
            } else {
                alert("Failed to queue report job! Try again later.");
            }
        });
    }

    function init() {
        loadRecentReports();
        $(document).on('submit', '[name="reportgenerateform"]', handleReportGenerate)

    }

    return {
        init: init,
    }
})(jQuery);
$(Reports.init());
