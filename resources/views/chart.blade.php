<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Procedure Distribution</title>
    
    <!-- Include the compiled app.js file with Vite -->
    @vite('resources/js/app.js')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #chart {
            width: 100%;
            max-width: 600px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #333;
        }
    </style>
</head>
<body>
    <div>
        <h1>Appointments Procedure Distribution</h1>
        <div id="chart"></div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var options = {
                    chart: {
                        type: 'donut', // Donut chart
                        height: 350,
                        background: 'transparent',
                        toolbar: { show: false },
                    },
                    series: @json($data), // Dynamic data from controller
                    labels: @json($labels), // Dynamic labels from controller
                    title: {
                        text: 'Procedures',
                        align: 'center',
                        style: {
                            fontSize: '18px',
                            fontWeight: '600',
                            color: '#333'
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '50%',
                                background: 'transparent',
                            },
                        },
                    },
                    colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33D6FF', '#FF8333', '#D4FF33'], // Color palette for each procedure
                    tooltip: {
                        theme: 'light',
                        x: { show: false }
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center',
                        fontSize: '14px',
                        fontWeight: 400,
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render(); // Render the chart
            });
        </script>
    </div>
</body>
</html>
