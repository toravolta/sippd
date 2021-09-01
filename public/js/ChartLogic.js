$(function () {

  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $visitorsChart = $('#visitors-chart')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels: ['Apr1', 'Apr2', 'Apr3', 'Apr4', 'Apr5', 'Apr6'],
      datasets: [{
        type: 'line',
        data: [100, 120, 600, 420, 324, 499, 1000],
        backgroundColor: '#fbd9a4',
        borderColor: '#f39c12',
        pointBorderColor: '#f39c12',
        pointBackgroundColor: '#f39c12',
        fill: true
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
// ++++++++++++++++++++++++++++++++++++++++++++++++++//
  var $tempChart = $('#temp-chart')
  // eslint-disable-next-line no-unused-vars
  var tempChart = new Chart($tempChart, {
    data: {
      labels: ['Apr1', 'Apr2', 'Apr3', 'Apr4', 'Apr5', 'Apr6'],
      datasets: [{
        type: 'line',
        data: ['34.2', '35.5', '36.2', '35.6', '36.0', '36.4'],
        backgroundColor: '#92ffcc',
        borderColor: '#00a65a',
        pointBorderColor: '#00a65a',
        pointBackgroundColor: '#00a65a',
        fill: true
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          display: true,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'black',
            zeroLineColor: '#313131'
          },
          ticks: $.extend({
            beginAtZero: false,
            suggestedMax: 38
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

// =======================================================//

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */

      /*
     * DONUT CHART VIsitor
     * -----------
     */

    var donutDataVisitor = [
      {
        label: '> 36<sup>o</sup> C',
        data : 25,
        color: '#f56954'
      },
      {
        label: '<= 36<sup>o</sup> C',
        data : 20,
        color: '#00a65a'
      }
    ]
    $.plot('#donutChartVisitor', donutDataVisitor, {
      series: {
        pie: {
          show       : true,
          radius     : 1,
          innerRadius: 3,
          label      : {
            show     : true,
            radius   : 0.56,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    })
    /*
     * END DONUT CHART
     */
//++++++++++++++++++++++++++++++++++++++//
  /*
 * DONUT CHART VIsitor
 * -----------
 */

  var donutDataTemp = [
    {
      label: 'Visitor',
      data: 25,
      color: '#f39c12'
    },
    {
      label: 'Return Visitor',
      data: 20,
      color: '#00c0ef'
    }
  ]
  $.plot('#donutChartTemp', donutDataTemp, {
    series: {
      pie: {
          show       : true,
          radius     : 1,
          innerRadius: 3,
          label      : {
            show     : true,
            radius   : 0.56,
            formatter: labelFormatter,
            threshold: 0.1
        }

      }
    },
    legend: {
      show: false
    }
  })
    /*
   * END DONUT CHART
   */

})

/*
 * Custom Label formatter
 * ----------------------
 */
function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
        + label
        + '<br>'
        + Math.round(series.percent) + '%</div>'
}