/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//btv Google Play
$(function () {
    var chart;
    chart = new Highcharts.chart({
        chart: {
            renderTo: 'container-btv-googleplay',
            type: 'spline'
        },
        title: {
            text: 'Notes'
        },
//        subtitle: {
//            text: '15 derniers jours'
//        },
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
                text: 'Notes'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        legend: {
            useHTML: true
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
                name: '<div style="font-size:10px">B.TV <img src="images/B.tv mobile.png" width="17"></div>',
                color: '#00A6BD',
                data: JSON.parse("[" + notes_btv_android + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">TV Orange <img src="images/otv.gif" width="17"></div>',
                color: '#FF7900',
                data: JSON.parse("[" + notes_orange_android + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">SFR TV <img src="images/stv.png" width="17" ></div>',
                color: '#E2031A',
                data: JSON.parse("[" + notes_sfr_android + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">myTF1 <img src="images/mytf1.png" width="17" ></div>',
                color: '#182081',
                data: JSON.parse("[" + notes_mytf1_android + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">myCANAL <img src="images/mycanal.png" width="17" ></div>',
                color: '#252525',
                data: JSON.parse("[" + notes_mycanal_android + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">6play <img src="images/6play.png" width="17" ></div>',
                color: '#274066',
                data: JSON.parse("[" + notes_6play_android + "]"),
                connectNulls: true
            }]
    });
    chart.setTitle({
        useHTML: true,
        text: '<div>Palmarès Notes</div> <div style="margin-left: 50px;"><img src="images/androidtab.png" style="width: 23px;"></div>'
    });
});

//btv AppStore
$(function () {
    var chart;
    chart = new Highcharts.chart({
        chart: {
            renderTo: 'container-btv-appstore',
            type: 'spline'
        },
        title: {
            text: 'Palmarès Notes'
        },
//        subtitle: {
//            text: '15 derniers jours'
//        },
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
                text: 'Notes'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        legend: {
            useHTML: true
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
                name: '<div style="font-size:10px">B.TV <img src="images/B.tv mobile.png" width="17"></div>',
                color: '#00A6BD',
                data: JSON.parse("[" + notes_btv_ios + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">TV Orange <img src="images/otv.gif" width="17"></div>',
                color: '#FF7900',
                data: JSON.parse("[" + notes_orange_ios + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">SFR TV <img src="images/stv.png" width="17" ></div>',
                color: '#E2031A',
                data: JSON.parse("[" + notes_sfr_ios + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">myTF1 <img src="images/mytf1.png" width="17" ></div>',
                color: '#182081',
                data: JSON.parse("[" + notes_mytf1_ios + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">myCANAL <img src="images/mycanal.png" width="17" ></div>',
                color: '#252525',
                data: JSON.parse("[" + notes_mycanal_ios + "]"),
                connectNulls: true
            }, {
                name: '<div style="font-size:10px">6play <img src="images/6play.png" width="17" ></div>',
                color: '#274066',
                data: JSON.parse("[" + notes_6play_ios + "]"),
                connectNulls: true
            }]
    });
    chart.setTitle({
        useHTML: true,
        text: '<div>Palmarès Notes</div> <div style="margin-left: 50px;"><img src="images/appstoretab.png" style="width: 23px;"></div>'
    });
});

//Graphe VOD
$(function () {
    // Create the chart
    Highcharts.chart('container-VOD', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'VOD'
        },
        subtitle: {
            text: date_vod
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}'
                }
            }
        },
        series: [{
                name:'Visiteurs',
                colorByPoint: true,
                data: [{
                        name: 'iOS',
                        y: JSON.parse(total_vod_ios),
                        drilldown: 'iOS'
                    }, {
                        name: 'Android',
                        y: JSON.parse(total_vod_android),
                        color: '#093',
                        drilldown: 'Android'
                    }]
            }],
        drilldown: {
            series: [{
                    name: 'iOS',
                    id: 'iOS',
                    data: data_vod_ios
                }, {
                    name: 'Android',
                    id: 'Android',
                    data: data_vod_android
                }]
        }
    });
});

// Graphe RPVR
$(function () {
    // Create the chart
    Highcharts.chart('container-RPVR', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'RPVR'
        },
        subtitle: {
            text: date_rpvr
        },
        xAxis: {
            type: 'category'
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}'
                }
            }
        },
        series: [{
                name:'Visiteurs',
                colorByPoint: true,
                data: [{
                        name: 'iOS',
                        y: JSON.parse(total_rpvr_ios),
                        drilldown: 'iOS'
                    }, {
                        name: 'Android',
                        y: JSON.parse(total_rpvr_android),
                        color: '#093',
                        drilldown: 'Android'
                    }]
            }],
        drilldown: {
            series: [{
                    name: 'iOS',
                    id: 'iOS',
                    data: data_rpvr_ios
                }, {
                    name: 'Android',
                    id: 'Android',
                    data: data_rpvr_android
                }]
        }
    });
});

// Graphe Note vs comment
$(function () {
    // Create the chart
    Highcharts.chart('container-Note-Comment', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Note vs Commentaires'
        },
        subtitle: {
            text: '30 derniers jours'
        },
        xAxis: {
            type: 'category'
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}'
                }
            }
        },
        series: [{
                name:'Votes',
                colorByPoint: true,
                data: [{
                        name: 'Sans commentaires',
                        y: JSON.parse(total_NSC),
                        drilldown: 'NSC',
                        color:'#737373'
                    }, {
                        name: 'Avec commentaires',
                        y: JSON.parse(total_NAC),
                        drilldown: 'NAC',
                        color:'#7CB5EC'
                    }]
            }],
        drilldown: {
            series: [{
                    name: 'Sans commentaires',
                    id: 'NSC',
                    data: repartition_NSC
                }, {
                    name: 'Avec commentaires',
                    id: 'NAC',
                    data: repartition_NAC
                }]
        }
    });
});

// Graphe craches
$(function () {
    // Create the chart
    Highcharts.chart('container-craches', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Craches et ANRs'
        },
        subtitle: {
            text: 'dernier mois'
        },
        xAxis: {
            type: 'category'
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}'
                }
            }
        },
        series: [{
                name:'Nombre',
                colorByPoint: true,
                data: [{
                        name: 'Craches',
                        y: JSON.parse(total_craches),
                        color:'#ff6666',
                        drilldown: 'craches'
                    }, {
                        name: 'ANRs',
                        y: JSON.parse(total_ANRs),
                        color:'#ffa64d',
                        drilldown: 'anrs'
                    }]
            }],
        drilldown: {
            series: [{
                    name: 'Craches',
                    id: 'craches',
                    data: repartition_craches
                }, {
                    name: 'ANRs',
                    id: 'anrs',
                    data: repartition_ANRs
                }]
        }
    });
});

// Graphe Unites OS
$(function () {

    var colors = Highcharts.getOptions().colors,
            categories = ['iOS', 'Android'],
            data = [{
                    y: JSON.parse(total_os_ios),
                    color: colors[0],
                    drilldown: {
                        name: 'iOS',
                        categories: name_os_ios,
                        data: value_os_ios,
                        color: colors[0]
                    }
                }, {
                    y: JSON.parse(total_os_android),
                    color: '#093',
                    drilldown: {
                        name: 'Android',
                        categories: name_os_android,
                        data: value_os_android,
                        color: '#093'
                    }
                }],
            browserData = [],
            versionsData = [],
            i,
            j,
            dataLen = data.length,
            drillDataLen,
            brightness;


    // Build the data arrays
    for (i = 0; i < dataLen; i += 1) {

        // add browser data
        browserData.push({
            name: categories[i],
            y: data[i].y,
            color: data[i].color
        });

        // add version data
        drillDataLen = data[i].drilldown.data.length;
        for (j = 0; j < drillDataLen; j += 1) {
            brightness = 0.2 - (j / drillDataLen) / 5;
            versionsData.push({
                name: data[i].drilldown.categories[j],
                y: data[i].drilldown.data[j],
                color: Highcharts.Color(data[i].color).brighten(brightness).get()
            });
        }
    }

    // Create the chart
    Highcharts.chart('container-uOS', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Visiteurs uniques / OS'
        },
        subtitle: {
            text: date_os
        },
        yAxis: {
            title: {
                text: 'Total percent market share'
            }
        },
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%'],
                dataLabels: {
//                    style: {
//                        fontSize: '8px'
//                    }
                }
            }
        },
        series: [{
                name:'Visiteurs',
                data: browserData,
                size: '43%',
                dataLabels: {
                    formatter: function () {
                        return this.y > 5 ? this.point.name : null;
                    },
                    color: '#ffffff',
                    distance: -27
                }
            }, {
                name: 'Visiteurs',
                data: versionsData,
                size: '80%',
                innerSize: '60%',
                dataLabels: {
                    formatter: function () {
                        // display only if larger than 1
                        return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y : null;
                    }
                }
            }]
    });
});

// Graphe Unites Version
$(function () {

    var colors = Highcharts.getOptions().colors,
            categories = ['iOS', 'Android'],
            data = [{
                    y: JSON.parse(total_version_ios),
                    color: colors[0],
                    drilldown: {
                        name: 'iOS',
                        categories: name_version_ios,
                        data: value_version_ios,
                        color: colors[0]
                    }
                }, {
                    y: JSON.parse(total_version_android),
                    color: '#093',
                    drilldown: {
                        name: 'Android',
                        categories: name_version_android,
                        data: value_version_android,
                        color: '#093'
                    }
                }],
            browserDatav = [],
            versionsDatav = [],
            i,
            j,
            dataLenv = data.length,
            drillDataLenv,
            brightnessv;


    // Build the data arrays
    for (i = 0; i < dataLenv; i += 1) {

        // add browser data
        browserDatav.push({
            name: categories[i],
            y: data[i].y,
            color: data[i].color
        });

        // add version data
        drillDataLenv = data[i].drilldown.data.length;
        for (j = 0; j < drillDataLenv; j += 1) {
            brightnessv = 0.2 - (j / drillDataLenv) / 5;
            versionsDatav.push({
                name: data[i].drilldown.categories[j],
                y: data[i].drilldown.data[j],
                color: Highcharts.Color(data[i].color).brighten(brightnessv).get()
            });
        }
    }

    // Create the chart
    Highcharts.chart('container-uVersion', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Visiteurs uniques / version'
        },
        subtitle: {
            text: date_version
        },
        yAxis: {
            title: {
                text: 'Total percent market share'
            }
        },
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%'],
                dataLabels: {
//                    style: {
//                        fontSize: '8px'
//                    }
                }
            }
        },
        series: [{
                name:'Visiteurs',
                data: browserDatav,
                size: '43%',
                dataLabels: {
                    formatter: function () {
                        return this.y > 5 ? this.point.name : null;
                    },
                    color: '#ffffff',
                    distance: -27
                }
            }, {
                name: 'Visiteurs',
                data: versionsDatav,
                size: '80%',
                innerSize: '60%',
                dataLabels: {
                    formatter: function () {
                        // display only if larger than 1
                        return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y : null;
                    }
                }
            }]
    });
});