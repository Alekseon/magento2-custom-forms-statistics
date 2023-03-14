/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
 define([
    'jquery',
    'chartJs'
], function ($, Chart) {
    'use strict';

    $.widget('mage.testChart', {

        _create: function () {
            this.createChart();
        },

        createChart: function () {
            this.chart = new Chart(this.element, this.getChartSettings());
        },

        updateChart: function (response) {
            this.chart.data.datasets[0].data = response.data;
            this.chart.data.datasets[0].backgroundColor = response.barColors;
            this.chart.data.labels = response.labels;
            this.chart.update();
        },

        getChartSettings: function () {
            console.log( this.options);
            var xValues = this.options.chartData.labels;
            var yValues = this.options.chartData.values;
            var barColors = this.options.chartData.colors;

            if(this.options.type === 'pie'){
                return {
                    type: this.options.type,
                    data: {
                        labels: xValues,
                        datasets: [{
                            backgroundColor: barColors,
                            data: yValues,
                        }]
                    },
                    options: { plugins: { legend: { display: false }, }, maintainAspectRatio: false }
                };
            } else {
                return {
                    type: this.options.type,
                    data: {
                        labels: xValues,
                        datasets: [{
                            backgroundColor: barColors,
                            data: yValues,
                        }]
                    },
                    options: { plugins: { legend: { display: false }, } }
                };
            }
        }
    });

    return $.mage.testChart;
});
