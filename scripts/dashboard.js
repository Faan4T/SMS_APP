"use strict";
google.charts.load('current', {'packages':['line']});
google.charts.setOnLoadCallback(drawChart);
function drawChart(){
    var data = new google.visualization.DataTable();
    data.addColumn('string', '');
    data.addColumn('number', 'SMS In');
    data.addColumn('number', 'SMS Out');
    data.addRows(data2);
    var options = {
        chart:{
            title: 'SMS In and Out',
            subtitle: 'Last 10 days activity'
        }
    };
    var chart = new google.charts.Line(document.getElementById('chartActivity'));
    chart.draw(data, google.charts.Line.convertOptions(options));
}
$(window).resize(function(){
    drawChart();
});