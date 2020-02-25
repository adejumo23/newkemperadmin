function handleDropdownImportClick() {
    var dropdownImportItem = $(this);
    var checkItems = [
        'Kams Agent Listing',
        'RCLA 150'
    ];
    if (dropdownImportItem.data('report-name') === 'Prod44anydetails' && $(this).prop('checked')) {
        $('.import-dropdown-item').each(function(){
            if (checkItems.indexOf($(this).data('report-name')) > -1) {
                $(this).prop('checked', true);
            }
        });
    }
}
function checkImportStatus(id) {
    var url = 'check-import-status.php';
    var data = {'id': id};
    $.get(url, data, function (response) {
        response = JSON.parse(response);
        var percentageDone = response.percentComplete;
        //Handle Percentage Progress bar

        if (response.status !== 'complete' || response.status !== 'error') {
            window.setTimeout(function (id) {
                checkImportStatus(id);
            }, 3000);//time in milli seconds
        } else {
            if (response.status === 'error') {
                alert('Import Failed. Please try again.');
            } else {
                alert('Import Successful!');
            }
        }
    });
}
function handleImportSubmit(){
    alert('Hello');
    $('.loading-gif').show();
    var importsArray = [];
    $('.import-dropdown-item').each(function(){
        if ($(this).prop('checked')) {
            importsArray.push($(this).data('report-name'));
        }
    });
    var url = 'run-imports.php';
    var data = {'importArray' : importsArray};
    $.post(url, data, function (response) {
        response = JSON.parse(response);
        console.log(response.status);
        checkImportStatus(response.id);
        $('.loading-gif').hide();
    });
}
//New JS file
function renderChart($element, data, disposeData, labels) {
    var myLineChart = new Chart($element, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Premiums",
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                data: data,
            },
                {
                    label: "Disposition",
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    data: disposeData,
                }],
        },
        options: {
            title: {
                display: true,
                text: 'Earned premium vs Total Disposition'
            },
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7,
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return '$' + number_format(value);
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: true
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        if(datasetLabel === 'Premiums') {
                            return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                        }
                        if (datasetLabel === 'Disposition') {
                            return datasetLabel + ': ' + (tooltipItem.yLabel);
                        }
                    }
                }
            }

        },
    });
}
function showChartData(response) {
    if(response.status) {
        var ctx = document.getElementById("earningVsDisposition");
        renderChart(ctx, response.chartData,response.chartDisposed, response.chartLabels);
    }
    else{
        alert('Your Customer Service Rep Conserved no premiums so far');
    }

}
function checkFilter(){
    var filterRange = [];
    var startingDate = document.getElementById("startingDate").value;
    var endingDate = document.getElementById("endingDate").value;
    if(startingDate == '' || endingDate == '') {
        alert("Please make sure you have a start date and end date selected");
    }
    else {
        filterRange.push({
            'startingDate': startingDate,
            'endingDate': endingDate
        });
        var url = 'confirmDates.php';
        var data = {'filterRange' : filterRange};
        $.post(url, data,function (response) {
            response = JSON.parse(response);
            if (response.status === 'error') {
                alert('Bad filter dates');
            }
            else{
                window.location.href = 'ConservationDashboard.php?startingDate='+startingDate+'&endingDate='+endingDate;
            }
        });
    }
}
// function filterDisposerData(){
//     $('#earningVsDisposition').remove(); // this is my <canvas> element
//     $('.chart-area').append('<canvas id="earningVsDisposition"><canvas>');
//     var disposerId = $(this).data('chart-disposer');
//     var url = "grabDisposerFilterData.php";
//     var data ={'disposerId' :disposerId};
//     $.post(url, data,function(response) {
//         response = JSON.parse(response);
//         if (response.status === '') {
//             alert('Your Customer Service Rep Conserved no premiums so far');
//         }
//         else{
//             showChartData(response);
//         }
//     });
// }
function renderDispositionChart(data,labels) {
    new Chart(document.getElementById("popularDisposition"), {
        type: 'horizontalBar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Dispositions",
                    backgroundColor:   ['rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)'
                    ],
                    data: data
                }
            ]
        },
        options: {
            legend: {display: false},
            title: {
                display: true,
                text: 'Popular disposition Listings'
            }
        }
    });
}
$(document).on('click', '.chartDisposerItem', filterDisposerData);
$(document).on('click', '.filterSubmit', checkFilter);
$(document).on('click', '#importList', handleImportSubmit);
$(document).on('click', '.import-dropdown-item', handleDropdownImportClick);