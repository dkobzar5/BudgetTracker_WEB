let values = <?php echo $amountJson; ?>;
let lables = <?php echo $categoryNamesJson; ?>;
let colorsFill = <?php echo $categoryColorsJson; ?>;
let colorsBorders = <?php echo $categoryColorsBorderJson; ?>;

values = values.map(num => parseInt(num, 10));

function sum(array) {
    let total = 0;
    for (let i = 0; i < array.length; i++) {
        let num = parseInt(array[i]);
        total += num;
    }
    return total;
}
const doughnutLabel = {
    id: 'doughnutLabel',
    beforeDatasetsDraw: function (chart, args, options) {
        const { ctx, data } = chart;
        ctx.save();

        const centerX = chart.width / 2;
        const centerY = chart.height / 2;

        ctx.font = "30px 'Montserrat', sans-serif";
        ctx.fillStyle = '#eee';
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';

        ctx.fillText(`${sum(values)}₴`, centerX, centerY);

        ctx.restore();
    }
};

const ctx = document.getElementById('expensesChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: lables,
        datasets: [{
            label: '',
            data: values,
            backgroundColor: colorsFill,
            borderColor: colorsBorders,
            borderWidth: 1
        }]
    },
    options: {
        cutout: '50%',
        plugins: {
            legend: {
                display: false
            },
            datalabels: {
                display: true,
                color: 'white',
                font: {
                    family: 'Montserrat',
                },
                borderRadius: 1,
                formatter: function (value, context) {
                    let data = context.dataset.data;
                    let dataIndex = context.dataIndex;
                    let labels = context.chart.data.labels;

                    if ((data[dataIndex] / sum(data) * 100 < 5) && labels[dataIndex].length >= 10) {
                        return '';
                    } else {
                        return labels[dataIndex];
                    }
                },
                padding: 6
            }
        }
    },
    plugins: [ChartDataLabels, doughnutLabel]
});