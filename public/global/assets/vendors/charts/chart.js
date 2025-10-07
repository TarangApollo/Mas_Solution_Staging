$(function () {
  // bar chart
  'use strict';
  var data = {
    labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
    datasets: [{
      label: '# Calls',
      data: [130, 190, 113, 50, 230, 300, 105, 119, 332, 25, 202, 103, 130, 190, 113, 50, 230, 300, 105, 119, 332, 25, 202, 103, 130, 190, 113, 50, 230, 300, 105, 119, 332, 25, 202, 103],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        
        'rgba(21, 42, 237, 0.2)',
        'rgba(224, 1, 200, 0.2)',
        'rgba(13, 191, 189, 0.2)',
        'rgba(191, 71, 13, 0.2)',
        'rgba(63, 191, 13, 0.2)',
        'rgba(157, 22, 22, 0.2)',

        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        
        'rgba(21, 42, 237, 0.2)',
        'rgba(224, 1, 200, 0.2)',
        'rgba(13, 191, 189, 0.2)',
        'rgba(191, 71, 13, 0.2)',
        'rgba(63, 191, 13, 0.2)',
        'rgba(157, 22, 22, 0.2)',

        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        
        'rgba(21, 42, 237, 0.2)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        
        'rgba(21, 42, 237, 1)',
        'rgba(224, 1, 200, 1)',
        'rgba(13, 191, 189, 1)',
        'rgba(191, 71, 13, 1)',
        'rgba(63, 191, 13, 1)',
        'rgba(157, 22, 22, 1)',

        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        
        'rgba(21, 42, 237, 1)',
        'rgba(224, 1, 200, 1)',
        'rgba(13, 191, 189, 1)',
        'rgba(191, 71, 13, 1)',
        'rgba(63, 191, 13, 1)',
        'rgba(157, 22, 22, 1)',

        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        
        'rgba(21, 42, 237, 1)',
      ],
      borderWidth: 1,
      fill: false
    }]
  };
 
  var multiLineData = {
    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange", "Red", "Blue", "Yellow", "Green", "Purple", "Orange", "Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
    datasets: [{
      label: 'Dataset 1',
      data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
      borderColor: [
        '#587ce4'
      ],
      borderWidth: 2,
      fill: false
    },    
    ]
  };
  var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }
  };
      
  // Get context with jQuery - using jQuery's .get() method.
  if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      options: options
    });
  }
});