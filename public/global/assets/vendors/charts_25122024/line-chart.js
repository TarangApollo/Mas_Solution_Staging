//line chart
var ctxL = document.getElementById("lineChart").getContext('2d');
var myLineChart = new Chart(ctxL, {
  type: 'line',
  data: {
    labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
    datasets: [{
      label: "Resolution at L1",
      data: [5, 12, 18, 22, 15, 28, 25, 25, 20, 19, 26, 10, 19, 21, 35, 28, 11, 26, 25, 40, 38, 25, 24, 22, 10, 26, 17, 14, 23, 20, 14],
      backgroundColor: [
        'rgba(105, 0, 132, .2)',
      ],
      borderColor: [
        'rgba(200, 99, 132, .7)',
      ],
      borderWidth: 2
    },
    {
      label: "Resolution at L2",
      data: [2, 12, 10, 18, 20, 15, 28, 20, 15, 12, 20, 0, 10, 1, 5, 18, 9, 6, 15, 10, 18, 20, 24, 12, 0, 6, 14, 18, 13, 10, 17],
      backgroundColor: [
        'rgba(97, 199, 102, .2)',
      ],
      borderColor: [
        'rgba(65, 186, 71, .7)',
      ],
      borderWidth: 2
    },
    {
      label: "Resolution in < 24 hrs",
      data: [12, 2, 5, 8, 6, 8, 10, 10, 1, 5, 3, 8, 0, 8, 2, 8, 1, 6, 9, 8, 5, 0, 7, 5, 1, 0, 0, 4, 2, 8, 1],
      backgroundColor: [
        'rgba(0, 137, 132, .2)',
      ],
      borderColor: [
        'rgba(0, 10, 130, .7)',
      ],
      borderWidth: 2
    }
    ]
  },
  
  options: {
    responsive: true
  }
});


//RMA line chart
var ctxL = document.getElementById("RMAlineChart").getContext('2d');
var myLineChart = new Chart(ctxL, {
  type: 'line',
  data: {
    labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
    datasets: [{
      label: "Registered RMA",
      data: [8, 2, 10, 9, 5, 8, 5, 2, 0, 9, 6, 10, 9, 6, 5, 8, 11, 6, 2, 1, 8, 5, 2, 2, 10, 6, 7, 4, 3, 0, 2],
      backgroundColor: [
        'rgba(105, 0, 132, .2)',
      ],
      borderColor: [
        'rgba(200, 99, 132, .7)',
      ],
      borderWidth: 2
    },
    {
      label: "Closed RMA",
      data: [6, 2, 5, 8, 6, 8, 5, 3, 1, 5, 3, 5, 0, 3, 2, 6, 1, 6, 3, 8, 5, 0, 4, 5, 1, 0, 0, 4, 2, 8, 2],
      backgroundColor: [
        'rgba(0, 137, 132, .2)',
      ],
      borderColor: [
        'rgba(0, 10, 130, .7)',
      ],
      borderWidth: 2
    }
    ]
  },
  
  options: {
    responsive: true
  }
});