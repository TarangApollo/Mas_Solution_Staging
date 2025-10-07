

var data = {
    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"],
    datasets: [{
        label: '#Calls',
        data: [5, 8, 9, 2, 4, 7, 3, 6, 36, 45, 25, 1],
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
            'rgba(157, 22, 22, 0.2)'
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
            'rgba(157, 22, 22, 1)'
        ],
        borderWidth: 1,
        fill: false
    }]
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

var barChartCanvas = $("#barChart").get(0).getContext("2d");
// This will get the first returned node in the jQuery collection.
var barChart = new Chart(barChartCanvas, {
    type: 'bar',
    data: data,
    options: options
});




// var yearID = $("#fYear").val();

// $.ajax({
//     type: 'POST',
//     url: "{{ route('profile.getCallChart') }}",
//     data: { yearId: yearID },
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     },
//     success: function (data) {
//         barChart.data.datasets[0].data.push(5, 8, 9, 2, 4, 7, 3, 6, 36, 45, 25, 1);
//         barChart.update();
//     }
// });
