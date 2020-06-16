var HierarchyList = function (options) {
    "use strict";
    var element = $(options.element);
    var elementClass = $(element).attr('class');
    var closecallback = options.closeCallback;
    var getDataElement = function () {
        element = $(".allCards");
        return element;
    };
    var getElement = function () {
        element = $("." + elementClass);
        return element;
    };
    var handleItemClick = function (e) {
        var data = getElement().find('ul').data();
        if (data['url']) {
            data = {...data, ...$(this).find('a').data()};
            doPostData(data['url'], data, getElement());
        }
    };
    function getStorageKey() {
         var storageKey = { 'html':  'hierarchyListPrevState' + getElement().find('ul').attr('id'),
        };
        return storageKey;
    }
    var storePrevState = function (postData) {
        var stream = loadData(getStorageKey());
        var element = '';
        var data = '';
        if((getElement().parent().html()) !== null){
            element = (getElement().parent().html());
        }
        if((getDataElement().parent().html()) !== null){
            data = (getDataElement().parent().html());
        }
        stream.push({'html':element,'data':data,'chart':postData});
        saveData(stream);
    };
    var loadPrevState = function () {
        var stream = loadData(getStorageKey());
        var prevHtmlString = stream.pop();
        var prevPost = stream;
        saveData(prevPost);
        var prevPostData = stream.pop();
        getElement().replaceWith(prevHtmlString.html);
        // getDataElement().replaceWith(prevHtmlString.data);
        handleSales(prevPostData.chart);
        handleRefunds(prevPostData.chart);
        handleNet(prevPostData.chart);
    };
    var loadData = function (key) {
        var stream = [];
        var newHtml = '';
        var newData = '';
        var dataString = localStorage.getItem(key.html);
        if (dataString === null) {
            stream.push({'html':'','data':''});
            return stream;
        }
        newData = JSON.parse(dataString);
        return  newData;
    };
    var saveData = function (data) {
        var keys = getStorageKey();
        var htmlString = keys.html;
        if (data.length === 1) {
            localStorage.removeItem(htmlString);
            return;
        }
        localStorage.setItem(htmlString, JSON.stringify(data));
    };
    function doPostData(url, data, el) {
        $.post(url, data, function (response) {
            response = JSON.parse(response);
            if (response.success) {
                storePrevState(response.allData);
                el.replaceWith(response.data);
                handleSales(response.allData);
                handleRefunds(response.allData);
                handleNet(response.allData);
            }
        });
    }
/*    function doPostClickData(url, data, el) {
        $.post(url, data, function (response) {
            response = JSON.parse(response);
            if (response.success) {
                storePrevState(response.allData);
                el.replaceWith(response.data);
                handleSales(response.allData);
                handleRefunds(response.allData);
            }
        });
    }*/
    function handleSales(data){
        var newSales = data.newSales;
        var sales = data.chart.chartSales;
        $('#kemperDashboardSales').remove(); // this is my <canvas> element
        $('.chart-area-sales').append('<canvas id="kemperDashboardSales"><canvas>');
        $('.salesNumber').remove();
        $('.salesData').append('<h4 class="font-weight-bold salesNumber">$'+ number_format(newSales)+'</h4>');
        showChartData(sales.sales,sales.labels,'kemperDashboardSales', 'Sales');
    }
    function handleRefunds(data){
        var refunds = data.refunds;
        var refundChart = data.chart.chartRefunds;
        $('#kemperDashboardRefunds').remove(); // this is my <canvas> element
        $('.chart-area-refunds').append('<canvas id="kemperDashboardRefunds"><canvas>');
        $('.refundNumber').remove();
        $('.refundData').append('<h4 class="font-weight-bold refundNumber">$'+ number_format(refunds)+'</h4>');
        showChartData(refundChart.refund,refundChart.labels,'kemperDashboardRefunds', 'Refunds');
    }
    function handleNet(data){
        var net = data.net;
        var netChart = data.chart.chartPremium;
        $('#kemperDashboardNet').remove(); // this is my <canvas> element
        $('.chart-area-net').append('<canvas id="kemperDashboardNet"><canvas>');
        $('.netNumber').remove();
        $('.netData').append('<h4 class="font-weight-bold refundNumber">$'+ number_format(net)+'</h4>');
        showChartData(netChart.premium,netChart.labels,'kemperDashboardNet','Net');
    }
    function showChartData(data,labels,ctx,Name) {
            var ctxL = document.getElementById(ctx);
            renderChart(ctxL, data,labels,Name);

    }
    function renderChart(ctx, data, labels,Name) {
        // Main chart
        var ctxL = ctx.getContext('2d');
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
                    text: Name+' YTD',
                    fontColor: "#fff"
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            color: "rgba(255,255,255,.25)"
                        },
                        ticks: {
                            fontColor: "#fff",
                        },
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: false,
                            color: "rgba(255,255,255,.25)"
                        },
                        ticks: {
                            fontColor: "#fff",
                            callback: function(value, index, values) {
                                return '$' + number_format(value);
                            }
                        },
                    }],
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
                            return datasetLabel + ': $' + number_format(tooltipItem.yLabel);

                        }
                    }
                }
            }

        });
    }
    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    var doLoadInitialState = function () {
        if (getElement().data('state') !== "loading") {
            getElement().data('state', 'loading');
            //loadData here
            var data = getElement().find('ul').data();
            var url = data['url'];
            var el = getElement();
            doPostData(url, data, el);
            var keys = getStorageKey();
            var htmlString = keys.html;
            var htmlData = keys.data;
            localStorage.removeItem(htmlString);
            localStorage.removeItem(htmlData);
            getElement().data('state', 'done');
        }
    };
    var closeSideBar = function () {
        if (closecallback !== undefined) {
            closecallback();
        }
    };
    var handleGoBack = function () {
        var key = getStorageKey();
        var dataString = getElement().find('ul').data();
        if (dataString['personneltype'] !== 'rvp') {
            loadPrevState();
        } else {
            closeSideBar();
        }

    };
    function setCloseCallback($callback) {
        closecallback = $callback;
    }
    var init = function () {
        doLoadInitialState();
        $(document).on('click', '.heirarchy', handleItemClick);
        $(document).on('click', '.close-hierarchyList', handleGoBack);
    };
    init();
    return {
        handleItemClick: handleItemClick,
        setCloseCallback: setCloseCallback
    }
};
(function ($) {

    $.fn.hierarchyList = function (options) {
        $.fn.hierarchyList.defaults = {
            element:this
        };
        var opts = $.extend({}, $.fn.hierarchyList.defaults, options);
        return this.each(function () {
            var hl = new HierarchyList(opts);
        });

    };
})(jQuery);

// Usage example:
// $(".hierarchyList").hierarchyList();