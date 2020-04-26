function filterDisposerData() {
    $('#earningVsDisposition').remove(); // this is my <canvas> element
    $('.chart-area').append('<canvas id="earningVsDisposition"><canvas>');
    var url = $(this).data('url');
    if (url == undefined) {
        url = $('[name="disposerDataUrl"]').val();
    }
    var data = {};
    $.post(url, data, function (response) {
        response = JSON.parse(response);
        if (!response.status) {
            alert('Your Customer Service Rep Conserved no premiums so far');
        } else {
            showChartData(response);
        }
    });
}
function dispositionChart() {
    var url = $('[name="dispositionDataUrl"]').val();
    var data = {};
    $.get(url, data, function (response) {
        response = JSON.parse(response);
        if (response.status) {
            renderDispositionChart(response.dataset, response.labels)
        }
    });

}
function filterYearlyDisposerData(){
    var url = $('[name="yearlyDataUrl"]').val();
    var data = {};
    $.get(url, data, function (response) {
        response = JSON.parse(response);
        if (response.status) {
            showYearlyChartData(response);
        }
    });
}

$(document).ready(function () {
    $('.datepicker').pickadate();
    filterDisposerData();
    dispositionChart();
    filterYearlyDisposerData();
});
