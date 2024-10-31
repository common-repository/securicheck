jQuery(document).ready(function ($) {
    showGraph(data);
});


function showGraph(data) {
    {

        var jour = [];
        var nbConnexions = [];

        for (var i in data) {
            jour.push(data[i].jour);
            nbConnexions.push(data[i].nombre_connexions);
        }

        var chartdata = {
            labels: jour,
            datasets: [{
                label: 'Connexions échouées',
                //  backgroundColor: '#49e2ff',
                backgroundColor: '#ffffff',
                //   borderColor: '#46d5f1',
                borderColor: '#04AA6D',
                hoverBackgroundColor: '#ffffff',
                hoverBorderColor: '#04AA6D',
                pointStyle: 'circle',
                pointRadius: 3.5,
                pointHoverRadius: 5,
                data: nbConnexions
            }]
        };

        var graphTarget = jQuery("#graphCanvas");

        var barGraph = new Chart(graphTarget, {
            type: 'line',
            data: chartdata,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    /*title: {
                        display: true,
                        text: (ctx) => 'Nombre de connexions échouées',
                    },*/
                    legend: {
                        display: false // This hides all text in the legend and also the labels.
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            precision: 0
                        },
                    }
                }
            }
        });
    }
}