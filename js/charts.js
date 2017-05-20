/* 
 * Created By LEMANGAR
 */

// New code for export all
$(function () {

    /**
     * Create a global getSVG method that takes an array of charts as an
     * argument
     */
    Highcharts.getSVG = function (charts) {
        var svgArr = [],
                top = 0,
                width = 0;

        Highcharts.each(charts, function (chart) {
            var svg = chart.getSVG();
            svg = svg.replace(
                    '<svg',
                    '<g transform="translate(0,' + top + ')" '
                    );
            svg = svg.replace('</svg>', '</g>');

            top += chart.chartHeight;
            width = Math.max(width, chart.chartWidth);

            svgArr.push(svg);
        });

        return '<svg height="' + top + '" width="' + width +
                '" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
                svgArr.join('') + '</svg>';
    };

    /**
     * Create a global exportCharts method that takes an array of charts as an
     * argument, and exporting options as the second argument
     */
    Highcharts.exportCharts = function (charts, options) {

        // Merge the options
        options = Highcharts.merge(Highcharts.getOptions().exporting, options);

        // Post to export server
        Highcharts.post(options.url, {
            filename: options.filename || 'chart',
            type: options.type,
            width: options.width,
            svg: Highcharts.getSVG(charts)
        });
    };

// existing code : Create charts


//Notes
    var chart1;
    chart1 = new Highcharts.chart({
        chart: {
            renderTo: 'container-notes',
            type: 'spline'
        },
        title: {
            text: 'Notes'
        },
        subtitle: {
            text: '15 derniers jours'
        },
        xAxis: {
            categories: notes_date,
            labels: {
                style: {
                    fontSize: '10px'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Notes'
            }
        },
        tooltip: {
            useHTML: true,
            shared: true,
            formatter: function () {
                var points = this.points;
                var pointsLength = points.length;
                var tooltipMarkup = pointsLength ? '<span style="font-size: 10px">' + points[0].key + '</span><br/>' : '';
                var index;

                for (index = 0; index < pointsLength; index += 1) {
                    tooltipMarkup += '<span style="color:' + points[index].series.color + '">\u25CF</span> ' + points[index].series.name + ': <b>' + points[index].y + '</b><br/>';
                }
                tooltipMarkup += '<span><img src="../images/groupe.png"></span> ' + ' Votes Google: <b>' + points[0].point.mydata;
                return tooltipMarkup;
            }
        },
        plotOptions: {
            spline: {
                marker: {
                    radius: 4,
                    lineColor: '#666666',
                    lineWidth: 1
                }
            },
            series: {
                marker: {
                    fillColor: '#FFFFFF',
                    lineWidth: 2,
                    radius: 3,
                    symbol: 'circle',
                    lineColor: null // inherit from series
                }
            }
        },
        series: [{
                name: 'Google Play Quotidien',
                color: '#004d00',
                data: notes_android,
                connectNulls: true

            }, {
                name: 'Google Play',
                color: '#009933',
                data: JSON.parse("[" + notes_android_total + "]"),
                connectNulls: true

            }, {
                name: 'App Store',
                color: '#7CB5EC',
                data: JSON.parse("[" + notes_ios + "]"),
                connectNulls: true

            }],
        exporting: {
            sourceWidth: 1250,
            scale: 1 //(default)
        }
    });
    chart1.setTitle({
        text: 'Notes'
    }, {
        useHTML: true,
        text: '<div id="rangenotes" style="font-size:11px; position: relative; float: left; text-align: center;"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i><span style="padding-left: 2px;"></span> <b class="caret"></b></div>'
    });
    chart1.xAxis[0].setTitle({
        text: 'Date'
    });

// Visitor
//Visitors

    var chart2 = Highcharts.chart('container-visitors', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Visiteurs Uniques'
        },
        subtitle: {
            text: ' 7 derniers mois'
        },
        xAxis: {
            categories: visiteurs_date,
            crosshair: true,
            labels: {
                style: {
                    fontSize: '10px'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Visiteurs'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
                name: 'Google Play',
                color: '#004d00',
                data: JSON.parse("[" + visiteurs_android + "]"),
                connectNulls: true

            }, {
                name: 'App Store',
                color: '#7CB5EC',
                data: JSON.parse("[" + visiteurs_ios + "]"),
                connectNulls: true

            }, {
                name: 'Total Stores',
                color: '#ff3333',
                data: JSON.parse("[" + sum_stores + "]"),
                connectNulls: true
            }],
        exporting: {
            sourceWidth: 1250,
            scale: 1, //(default)
//            chartOptions: {
//                subtitle: {
//                    text: ''
//                },
//                chart: {
//                    height: this.chartHeight + 200
//                }
//            }
        }
    });
    chart2.xAxis[0].setTitle({
        text: 'Date'
    });

//Sales

    var chart3;
    chart3 = new Highcharts.chart({
        chart: {
            renderTo: 'container-sales',
            type: 'spline'
        },
        title: {
            text: 'Téléchargements-Désinstallations-Mises à jour',
            x: -20 //center
        },
        subtitle: {
            text: 'dernier mois'
        },
        xAxis: {
            categories: date_de_mesure,
            labels: {
                style: {
                    fontSize: '10px'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Install & Uninstall & Upgrade'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        plotOptions: {
            spline: {
                marker: {
                    radius: 4,
                    lineColor: '#666666',
                    lineWidth: 1
                }
            },
            series: {
                marker: {
                    //enabled: false // hide point series
                    fillColor: '#FFFFFF',
                    lineWidth: 2,
                    radius: 3,
                    symbol: 'circle',
                    lineColor: null // inherit from series
                }
            }
        },
        series: [{
                name: 'Uninstall Google Play',
                color: '#ff3333',
                data: JSON.parse("[" + desinstallation_android + "]"),
                connectNulls: true

            }, {
                name: 'Install Google Play',
                color: '#004d00',
                data: JSON.parse("[" + telechargement_android + "]"),
                connectNulls: true

            }, {
                name: 'Upgrade Google Play',
                color: '#009933',
                data: JSON.parse("[" + mise_a_jour_android + "]"),
                connectNulls: true

            }, {
                name: 'Install App Store',
                color: '#7CB5EC',
                data: JSON.parse("[" + telechargement_ios + "]"),
                connectNulls: true
            }, {
                name: 'Reinstall App Store',
                color: '#FF9A00',
                data: JSON.parse("[" + desinstallation_ios + "]"),
                connectNulls: true
            }, {
                name: 'Upgrade App Store',
                color: '#00FF78',
                data: JSON.parse("[" + mise_a_jour_ios + "]"),
                connectNulls: true

            }],
        exporting: {
            sourceWidth: 1250,
            scale: 1
        }
    });
    chart3.setTitle({
        text: 'Téléchargements-Désinstallations-Mises à jour'
    }, {
        useHTML: true,
        text: '<div id="rangesales" style="font-size:11px; position: relative; float: left; text-align: center;"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i><span style="padding-left: 2px;"></span> <b class="caret"></b></div>'
    });
    chart3.xAxis[0].setTitle({
        text: 'Date'
    });

    //export all
    $('#export-png').click(function () {
        Highcharts.exportCharts([chart1, chart2, chart3]);
    });

    $('#export-pdf').click(function () {
//            chart1.exportChart({
//            type: 'application/pdf',
//            filename: 'my-pdf'
//        });
        Highcharts.exportCharts([chart1, chart2, chart3], {
            type: 'application/pdf'
        });
    });
});




