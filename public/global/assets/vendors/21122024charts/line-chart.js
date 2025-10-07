//line chart
var ctxL = document.getElementById("lineChart").getContext('2d');
var myLineChart = new Chart(ctxL, {
  type: 'line',
  data: {
    labels: [ "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec","Jan", "Feb", "Mar"],
    datasets: [{
      label: "Resolution at L1",
      data: [],
      backgroundColor: [
        'rgba(105, 0, 132, .2)',
      ],
      borderColor: [
        'rgba(200, 99, 132, .7)',
      ],
      borderWidth: 2
    },
    {
      label: "Resolution in < 24 hrs",
      data: [],
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

$.ajax({
    url: 'https://jsonplaceholder.typicode.com/posts/1/comments',
    success: function(data) {
        myLineChart.data.datasets[0].data.push(28, 48, 40, 19, 86, 27, 90, 54, 87, 45, 66, 35);
        myLineChart.data.datasets[1].data.push(18, 28, 30, 29, 46, 17, 40, 24, 57, 75, 26, 85);
        myLineChart.update();
    }
  });
