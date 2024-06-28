<?php
require_once __DIR__ . '/src/helpers.php';
$objJWT = verifyToken();
if (!$objJWT) {
    redirect('/login.php');
}
$user = getUser(verifyToken()->user_id);
if (!$user) {
    redirect('/login.php');
}
$account = getUserAccount($user['id']);
$term = "month";
if (isset($_SESSION['selected_term'])) {
    $term = getTerm();
}
$expenses = getUserExpenses($account['account_id'], $term);

$amounts = array();
$categoryNames = array();
$categoryColorsFill = array();
$categoryColorsBorder = array();

if (empty($expenses)) {
    $expenses = [
        ['amount' => 0, 'category_name' => 'Expenses', 'category_color' => '#afafaf'],
    ];
}
foreach ($expenses as $expense) {
    $amounts[] = intval($expense['amount']);
    $categoryNames[] = $expense['category_name'];
    $categoryColors[] = hexToRgba($expense['category_color'], 0.3);
    $categoryColorsBorder[] = hexToRgba($expense['category_color'], 1);
}
$accountJson = json_encode($account);
$amountJson = json_encode($amounts);
$categoryNamesJson = json_encode($categoryNames);
$categoryColorsJson = json_encode($categoryColors);
$categoryColorsBorderJson = json_encode($categoryColorsBorder);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="/css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

</head>

<body>


    <div class="user-info">
        <div class="info-header">
            <h3><?= $account['account_name'] ?></h3>
            <button class="edit-icon" onclick="toggleEditMode()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path
                        d="M3 17.25V21h3.75l11-11-3.75-3.75L3 17.25zM21 6.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-2.83 2.83 3.75 3.75L21 6.04z" />
                </svg>
            </button>
            <button id="openFormButton" class="edit-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <circle cx="12" cy="12" r="11" stroke="white" stroke-width="2" fill="transparent" />
                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z" fill="white" />
                </svg>


            </button>
        </div>
        <span id="balance" class="balance-value" style="color: <?= $account['balance'] < 0 ? 'red' : 'white' ?>"
            onblur="saveEditedBalance(this.textContent, <?= $account['account_id'] ?>)"><?= $account['balance'] ?></span><span
            style="color: <?= $account['balance'] < 0 ? 'red' : 'white' ?>"><?= $account['currency'] ?></span>
        <canvas id="expensesChart" width="500" height="500"></canvas>
    </div>
    <div class="container" style="width:500px; margin: 20px;">
        <div class="term-container" style="margin-bottom: 10px">
            <ul class="term-list">
                <li class="term-list-item" data-term="day">Day</li>
                <li class="term-list-item" data-term="week">Week</li>
                <li class="term-list-item" data-term="month">Month</li>
                <li class="term-list-item" data-term="year">Year</li>
            </ul>
        </div>
        <div class='expenses-list'>
            <?php
            foreach ($expenses as $expense) {
                $category = htmlspecialchars($expense['category_name']);
                $amount = number_format($expense['amount'], 2);
                $percentage = (array_sum($amounts) > 0) ? round(($expense['amount'] / array_sum($amounts)) * 100, 2) : 0;

                echo "<div class='expense-item' style='border-left: 5px solid " . $expense['category_color'] . "'>";
                echo "<div class='expense-category'>$category</div>";
                echo "<div class='expense-amount'>$amount" . $account['currency'] . "</div>";
                echo "<div class='expense-percentage'>$percentage%</div>";
                echo "</div>";
            }
            ?>
        </div>
        <div class="top-right">
            <form action="src/actions/logoutAction.php" method="post">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay">
        <div class="form-container" id="formContainer">
            <span class="close-button" id="closeFormButton">&times;</span>
            <form id="expenseForm" action="/src/actions/createExpence.php" method="POST">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <?php foreach (getCategories() as $category): ?>
                        <option value="<?= htmlspecialchars($category['categories']) ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"></textarea>

                <input type="hidden" id="user_id" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">

                <input type="hidden" id="account_id" name="account_id"
                    value="<?= htmlspecialchars($account['account_id']) ?>">

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <script src="/js/main.js" defer></script>
    <script src="/js/overlay.js" defer></script>
    <script src="/js/termSelector.js" defer></script>
</body>
<script>

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

            ctx.fillText(`${sum(values)}<?= $account['currency'] ?>`, centerX, centerY);

            ctx.restore();
        }
    };

    const ctx = document.getElementById('expensesChart').getContext('2d');
    console.log(ctx);
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

</script>


</html>