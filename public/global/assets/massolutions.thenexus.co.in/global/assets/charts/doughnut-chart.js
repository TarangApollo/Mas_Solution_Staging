//doughnut
var ctxD = document.getElementById("doughnutChart").getContext('2d');
var myLineChartD = new Chart(ctxD, {
  type: 'doughnut',
  data: {
    labels: ["Advanced", "Apollo", "Argus", "FFE", "Esprit"],
    datasets: [{
      data: [300, 50, 100, 40, 120],
      backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
      hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]
    }]
  },
  options: {
    responsive: true
  }
});
