//pie
var ctxP = document.getElementById("RMApieChart").getContext('2d');
var myPieChart = new Chart(ctxP, {
  type: 'pie',
  data: {
    labels: ["Advanced", "Apollo", "Argus", "FFE", "Esprit"],
    datasets: [{
      data: [60, 80, 120, 80, 150],
      backgroundColor: ["#00b8bf", "#59cefe", "#436cb1", "#f75789", "#ff925e"],
      hoverBackgroundColor: ["#0fd5dd", "#92dbf9", "#5988d7", "#f8779f", "#fdb28f"]
    }]
  },
  options: {
    responsive: true
  }
});