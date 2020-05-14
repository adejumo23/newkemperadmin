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
function displayChart() {
    $('.min-chart#chart-sales').easyPieChart({
        barColor: "#FF5252",
        onStep: function (from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
}
function mainChart(){
    // Main chart
    var ctxL = document.getElementById("lineChart").getContext('2d');
    var myLineChart = new Chart(ctxL, {
        type: 'bar',
        data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                label: "My First dataset",
                fillColor: "#fff",
                backgroundColor: 'rgba(255, 255, 255, .3)',
                borderColor: 'rgba(255, 255, 255)',
                data: [0, 10, 5, 2, 20, 30, 45],
            }]
        },
        options: {
            legend: {
                labels: {
                    fontColor: "#fff",
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: true,
                        color: "rgba(255,255,255,.25)"
                    },
                    ticks: {
                        fontColor: "#fff",
                    },
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        display: true,
                        color: "rgba(255,255,255,.25)"
                    },
                    ticks: {
                        fontColor: "#fff",
                    },
                }],
            }
        }
    });
}
// Material Select Initialization
function materialSelect() {
    $('.mdb-select').material_select();
}
// Tooltips Initialization
function toolTips() {
    $('[data-toggle="tooltip"]').tooltip()
}

$(document).ready(function () {
// Data Picker Initialization
    $('.datepicker').pickadate();
    // materialSelect ();
    toolTips();
    filterDisposerData();
    dispositionChart();
    filterYearlyDisposerData();
    displayChart();
    mainChart()
});
