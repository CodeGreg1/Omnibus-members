"use strict";

/**----------------------------------------------------------------------
 * [Handle app.chartConfig initialization]
 *--------------------------------------------------------------*/
app.chartConfig = {};

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object default color]
 *--------------------------------------------------------------*/
app.chartConfig.defaultColor = '#6777ef';

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object default line and bar chart types]
 *--------------------------------------------------------------*/
app.chartConfig.lineBarCharts = [
    'line',
    'bar'
];

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object line chart configuration]
 *--------------------------------------------------------------*/
app.chartConfig.line = {
    borderWidth: 3,
    backgroundColor: 'transparent',
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                display: false
            }
        },
        scales: {
            y: {
                grid: {
                    display: false,
                    border: false,
                },
                ticks: {
                    stepSize: 150
                }
            },
            x: {
                grid: {
                    color: '#f5f5f5',
                    lineWidth: 1
                }
            }
        },
    }
};

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object bar chart configuration]
 *--------------------------------------------------------------*/
app.chartConfig.bar = {
    borderWidth: 2,
    backgroundColor: app.chartConfig['defaultColor'],
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                display: false
            }
        },
        scales: {
            y: {
                grid: {
                    display: false,
                    border: false,
                },
                ticks: {
                    stepSize: 150
                }
            },
            x: {
                grid: {
                    color: '#f5f5f5',
                    lineWidth: 1
                }
            }
        },
    }
};

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object pie chart configuration]
 *--------------------------------------------------------------*/
app.chartConfig.pie = {
    backgroundColor: app.chartConfig['defaultColor'],
    options: {
        responsive: true,
        legend: {
            position: 'bottom'
        }
    }
};

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object chart datasets configuration]
 *--------------------------------------------------------------*/
app.chartConfig.datasets = function(attributes) {

    if(app.chartConfig.lineBarCharts.includes(attributes['type'])) {
        return [{
            label: attributes['label'],
            data: attributes['result']['data'],
            borderWidth: app.chartConfig[attributes['type']]['borderWidth'],
            borderColor: app.chartConfig['defaultColor'],
            backgroundColor: app.chartConfig[attributes['type']]['backgroundColor'],
            pointBackgroundColor: '#fff',
            pointBorderColor: app.chartConfig['defaultColor'],
            pointRadius: 4,
            tension: 0.3
        }];
    }

    if(attributes['type'] == 'pie') {
        return [{
            label: attributes['label'],
            data: attributes['result']['data'],
            backgroundColor: app.chartConfig.generatePieBgColors(attributes['result']['data'])
        }];
    }
    
};

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object pie chart random colors]
 *--------------------------------------------------------------*/
app.chartConfig.generatePieBgColors = function(data) {
    var colors = [app.chartConfig['defaultColor']];

    for (let i = 1; i < data.length; i++) {
        colors.push(app.chartConfig.randomColor());
    }

    return colors;
};

/**----------------------------------------------------------------------
 * [Handle app.chartConfig object that generate random colors]
 *--------------------------------------------------------------*/
app.chartConfig.randomColor = function() {
    let maxVal = 0xFFFFFF; // 16777215

    let randomNumber = Math.random() * maxVal; 

    randomNumber = Math.floor(randomNumber);

    randomNumber = randomNumber.toString(16);

    let randColor = randomNumber.padStart(6, 0); 

    return `#${randColor.toUpperCase()}`
};

/**----------------------------------------------------------------------
 * [handle initialization of the chart with chart.js plugin]
 * 
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.chart = function(attributes) {
    new Chart(document.getElementById(attributes['selector']).getContext('2d'), {
        type: attributes['type'],
        data: {
            labels: attributes['result']['labels'],
            datasets: app.chartConfig.datasets(attributes)
        },
        options: app.chartConfig[attributes['type']]['options']
    });
}