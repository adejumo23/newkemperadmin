function renderDisposerChart(data, disposeData, labels) {
    // Main chart
    var ctxL = document.getElementById("earningVsDisposition").getContext('2d');
    var myLineChart = new Chart(ctxL, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Premiums",
                fillColor: "#fff",
                backgroundColor: 'rgba(255, 255, 255, .3)',
                borderColor: 'rgba(255, 255, 255)',
                data: data,
            },
                {
                    label: "Dispositions",
                    fillColor: "#fff",
                    backgroundColor: 'rgba(255, 255, 255, .6)',
                    borderColor: 'rgba(255, 255, 255)',
                    data: disposeData,
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
function showChartData(response) {
    if(response.status) {
        var ctx = document.getElementById("earningVsDisposition");
        renderDisposerChart(response.chartData,response.chartDisposed, response.chartLabels);
    }
    else{
        alert('Your Customer Service Rep Conserved no premiums so far');
    }
}
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
function renderDispositionChart(data,labels) {
    // Main chart
    var ctxL = document.getElementById("popularDisposition").getContext('2d');
    var myLineChart = new Chart(ctxL, {
        type: 'horizontalBar',
        data: {
            labels: labels,
            datasets: [{
                label: "Dispositions",
                fillColor: "#fff",
                backgroundColor: 'rgba(255, 255, 255, .3)',
                borderColor: 'rgba(255, 255, 255)',
                data: data,
            }]

        },
        options: {
            legend: {
                labels: {
                    fontColor: "#fff",
                }
            },
            title: {
                display: true,
                text: 'Popular disposition Listings',
                fontColor: "#fff"
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
    // filterYearlyDisposerData();
    // displayChart();
    // mainChart()
});
