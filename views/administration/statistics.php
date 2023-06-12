<?php
include("admins-layout.php");

/** @var array $recipe_count_per_category */
/** @var array $average_tsc_per_category */
/** @var array $average_ingredient_per_category */
/** @var array $recipes_per_month */
/** @var array $categories_with_recipes_per_month */
/** @var int $recipes_count */
/** @var int $categories_count */
/** @var array $most_liked_recipes */
/** @var array $most_liked_categories */

/** @var int $user_count */
/** @var array $privileged_users */
/** @var array $signups_per_month */
/** @var array $user_count_by_access */
/** @var array $last_logined_users */


core\Core::getInstance()->pageParams['title'] = 'Statistics';
?>
<div class="h2">Statistics</div>
<div class="display-6 mx-5 mt-2 text-center">Recipes - <?= $recipes_count ?>, Categories - <?= $categories_count ?></div>
<div class="my-5">
    <h3>Recipes count per Category</h3>
    <canvas class="my-4" id="recipesCategoriesChart" width="900" height="380"></canvas>
</div>

<h3>Average data per Category</h3>
<div class="table-responsive mb-5">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>Ingredients</th>
            <th width="160px">Time, min</th>
            <th width="160px">Servings, pers</th>
            <th width="160px">Calories, cal</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($average_tsc_per_category as $key => $data)
        {
            $row = "<tr><td><a class='text-decoration-none text-dark' href='/category/view/{$data['id']}'>
                    {$data['name']}</a></td><td>{$average_ingredient_per_category[$key]['average']}</td>
                    <td>{$data['time']} </td><td>{$data['servings']}</td><td>{$data['calories']}</td></tr>";
            echo $row;
        }
        ?>
        </tbody>
    </table>
</div>

<div class="my-5">
    <h3>Data count per Month</h3>
    <canvas class="my-4" id="newRecipesChart" width="900" height="380"></canvas>
</div>

<h3>Most Favourited</h3>
<div class="table-responsive mb-5">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Recipes</th>
            <th>Favourites</th>
            <th>Categories</th>
            <th>Favourites</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($most_liked_recipes as $key => $recipe)
        {
            $row = "<tr><td><a class='text-decoration-none text-dark' href='/recipe/view/{$recipe['id']}'>
                    {$recipe['name']}</a></td><td>{$recipe['count']}</td>
                    <td><a class='text-decoration-none text-dark' href='/category/view/{$most_liked_categories[$key]['id']}'>
                    {$most_liked_categories[$key]['name']}</a></td><td>{$most_liked_categories[$key]['count']}</td></tr>";
            echo $row;
        }
        ?>
        </tbody>
    </table>
</div>

<hr>
<div class="display-6 mx-5 pt-5 text-center">Users - <?= $user_count ?></div>

<div class="mt-4 mb-5">
    <h3>Users data per Month</h3>
    <canvas class="my-4" id="usersChart" width="900" height="380"></canvas>
</div>

<h3>Privileged Users</h3>
<div class="table-responsive mb-5">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Login</th>
            <th>Name</th>
            <th>Access Level</th>
            <th>Last Login Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($privileged_users as $user)
        {
            $row = "<tr><td><a class='text-decoration-none text-dark' href='/user/edit/{$user['id']}'>
                    {$user['login']}</a></td><td>{$user['firstname']} {$user['lastname']}</td>
                    <td>{$user['access_level']}</td><td>{$user['last_login_date']}</td></tr>";
            echo $row;
        }
        ?>
        </tbody>
    </table>
</div>
<div class="my-5">
    <h3>Users Access Level</h3>
    <canvas class="my-4" id="accessChart" width="900" height="380"></canvas>
</div>

<h3>Last Logins</h3>
<div class="table-responsive mb-5">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Login</th>
            <th>Name</th>
            <th>Access Level</th>
            <th>Last Login Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($last_logined_users as $user)
        {
            $row = "<tr><td><a class='text-decoration-none text-dark' href='/user/edit/{$user['id']}'>
                    {$user['login']}</a></td><td>{$user['firstname']} {$user['lastname']}</td>
                    <td>{$user['access_level']}</td><td>{$user['last_login_date']}</td></tr>";
            echo $row;
        }
        ?>
        </tbody>
    </table>
</div>


<!-- Charts -->


<!-- Recipes | Categories -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.1/dist/Chart.min.js"></script>
<?php
    $cats = [];
    $recs = [];
    foreach ($recipe_count_per_category as $category)
    {
        $cats[] = "'{$category['name']}'";
        $recs[] = "{$category['count']}";
    }
    $categoriesData = toChartData($cats);
    $recipesData= toChartData($recs);
?>
<script>
    ctx = document.getElementById("recipesCategoriesChart");
    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $categoriesData ?>,
            datasets: [{
                data: <?= $recipesData ?>,
                lineTension: 0,
                backgroundColor: '#5e9677',
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            },
            legend: {
                display: false,
            },
        }
    });
</script>

<?php
$months = [];
$recs = [];
$cats = [];

foreach ($recipes_per_month as $login)
{
    $months[] = $login['month'];
    $recs[] = "{$login['count']}";
}
foreach ($categories_with_recipes_per_month as $signup)
{
    if (!in_array($signup['month'], $months))
        $cats[] = 0;
    else
        $cats[] = "{$signup['count']}";
}
$monthsNames = [];
foreach ($months as $month)
{
    $monthName = date('F', mktime(0, 0, 0, $month, 10));
    $monthsNames[] = "'{$monthName}'";
}

$monthData = toChartData($monthsNames);
$recipesData = toChartData($recs);
$categoriesData = toChartData($cats);
?>
<script>
    ctx = document.getElementById("newRecipesChart");
    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $monthData ?>,
            datasets: [
                {
                    label: 'Recipes',
                    data: <?= $recipesData ?>,
                    borderColor: '#86bde8',
                    backgroundColor: 'transparent'
                }
                ,
                {
                    label: 'Categories',
                    data: <?= $categoriesData ?>,
                    borderColor: '#e8619c',
                    backgroundColor: 'transparent'

                }
                ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            },
            responsive: true,
            legend: {
                display: true
            },
        }
    });
</script>



<!-- Users -->

<?php
$months = [];
$signups = [];
$monthsNames = [];
foreach ($signups_per_month as $signup)
{
    $monthName = date('F', mktime(0, 0, 0, $signup['month'], 10));
    $monthsNames[] = "'{$monthName}'";
    $signups[] = "{$signup['count']}";
}
$monthData = toChartData($monthsNames);
$signupData = toChartData($signups);
?>
<script>
    ctx = document.getElementById("usersChart");
    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $monthData ?>,
            datasets: [
                {
                    label: 'Signups',
                    data: <?= $signupData ?>,
                    borderColor: '#99E0E0C9',
                    backgroundColor: '#99E0E0C9'

                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            },
            responsive: true,
            legend: {
                display: true
            },
        }
    });
</script>

<?php
$access = [];
$count = [];
foreach ($user_count_by_access as $row)
{
    $access[] = "'Level {$row['access_level']}'";
    $count[] = "{$row['count']}";
}

$accessData = toChartData($count);
$accessLabels= toChartData($access);
?>
<script>
    let ctx = document.getElementById("accessChart");
    let myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?= $accessLabels ?>,
            datasets: [
                {
                    label: 'Signups',
                    data: <?= $accessData ?>,
                    backgroundColor: []
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Pie Chart'
                }
            }
        },
    });
    const colors = [
        '#4dc9f6',
        '#f67019',
        '#f53794',
        '#537bc4',
        '#acc236',
        '#166a8f',
        '#00a950',
        '#58595b',
        '#8549ba'
    ];
    for (let i = 0; i < <?= count($access) ?>; i++) {
        myChart.data.datasets[0].backgroundColor.push(colors[i]);
    }
    myChart.update();
</script>

<?php
function toChartData($array)
{
    $data= "[ ";
    $data.= implode(', ', $array);
    $data .= " ]";
    return $data;
}
?>
