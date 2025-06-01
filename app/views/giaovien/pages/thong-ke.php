<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Khoảng điểm', 'Số học sinh'],
            ['0 - 4 điểm', 3],
            ['5 - 6 điểm', 5],
            ['7 - 8 điểm', 9],
            ['9 - 10 điểm', 4]
        ]);

        var options = {
            title: '📈 Biểu đồ phân bố điểm học sinh',
            pieHole: 0.4,
            pieStartAngle: 45,
            colors: ['#e74c3c', '#f39c12', '#2ecc71', '#3498db'],
            legend: { position: 'bottom', textStyle: { fontSize: 14, color: '#2c3e50' }},
            titleTextStyle: { fontSize: 20, bold: true, color: '#2c3e50' },
            slices: {
            2: { offset: 0.1 },
            3: { offset: 0.05 }
            },
            tooltip: { text: 'both' },
            chartArea: { width: '85%', height: '60%' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>

<style>

    .container {
    max-width: 900px;
    margin: auto;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    padding: 30px;
    }

    h1 {
    text-align: center;
    margin-bottom: 10px;
    }

    .description {
    text-align: center;
    font-size: 16px;
    margin-bottom: 30px;
    color: #555;
    }

    #piechart {
    height: 450px;
    }

    .table-box {
    margin-top: 40px;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    }

    th, td {
    padding: 12px 15px;
    border: 1px solid #ccc;
    text-align: center;
    }

    th {
    background-color: #3498db;
    color: #fff;
    }

    tfoot td {
    background-color: #ecf0f1;
    font-weight: bold;
    }

    .footer-note {
    margin-top: 30px;
    font-size: 14px;
    color: #777;
    font-style: italic;
    text-align: right;
    }

    .fa {
    margin-right: 6px;
    }
</style>

<div class="container">
    <div id="piechart"></div>
</div>