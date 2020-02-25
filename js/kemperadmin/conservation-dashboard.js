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
    var url = "grabDispositions.php";
    var dummyData = "activeDispositions";
    var data = {'activeDispositions': dummyData};
    $.get(url, data, function (response) {
        response = JSON.parse(response);
        if (response.status) {
            renderDispositionChart(response.dataset, response.labels)
        }
    });

}
$(document).ready(function () {
    filterDisposerData();
    dispositionChart();
});
