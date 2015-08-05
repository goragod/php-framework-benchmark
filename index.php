<?php
require __DIR__.'/libs/php-recipe-2nd/make_chart_parts.php';
require __DIR__.'/libs/parse_results.php';
$module = $_GET['module'];
$m = array_keys($modules);
if (!isset($modules[$module])) {
    $module = $m[0];
}

$results = parse_results(__DIR__.'/output/'.$module.'/results.hello_world.log');

$barColors = array(
    'DarkBlue', 'DarkCyan', 'DarkGoldenRod', 'DarkOrange', 'DarkGreen',
    'DarkKhaki', 'DarkMagenta', 'DarkOliveGreen', 'DarkSeaGreen', 'DarkOrchid',
    'DarkRed', 'DarkSalmon', 'DarkGray', 'DarkSlateBlue', 'DarkSlateGray',
);

$graphWidth  = 800;
$graphHeight = 400;

// RPS Benchmark
$data[] = array('', 'rps', array('role' => 'style'));  // header

$colors = $barColors;
foreach ($results as $fw => $result) {
    $data[] = array($fw, $result['rps'], array_shift($colors));
}
//var_dump($data); exit;

$options = array(
    'title' => 'Throughput',
    'titleTextStyle' => array('fontSize' => 16),
    'hAxis' => array('title' => 'requests per second',
        'titleTextStyle' => array('bold' => true)),
    'vAxis' => array('minValue' => 0, 'maxValue' => 0.01),
    'width' => $graphWidth,
    'height' => $graphHeight,
    'bar' => array('groupWidth' => '90%'),
    'legend' => array('position' => 'none')
);
$type = 'ColumnChart';
list($chart_rpm, $div_rpm) = makeChartParts($data, $options, $type);

// Memory Benchmark
$data = array();
$data[] = array('', 'memory', array('role' => 'style'));  // header

$colors = $barColors;
foreach ($results as $fw => $result) {
    $data[] = array($fw, $result['memory'], array_shift($colors));
}

$options = array(
    'title' => 'Memory',
    'titleTextStyle' => array('fontSize' => 16),
    'hAxis' => array('title' => 'peak memory (MB)',
        'titleTextStyle' => array('bold' => true)),
    'vAxis' => array('minValue' => 0, 'maxValue' => 1),
    'width' => $graphWidth,
    'height' => $graphHeight,
    'bar' => array('groupWidth' => '90%'),
    'legend' => array('position' => 'none')
);
$type = 'ColumnChart';
list($chart_mem, $div_mem) = makeChartParts($data, $options, $type);

// Exec Time Benchmark
$data = array();
$data[] = array('', 'time', array('role' => 'style'));  // header

$colors = $barColors;
foreach ($results as $fw => $result) {
    $data[] = array($fw, $result['time'], array_shift($colors));
}

$options = array(
    'title' => 'Exec Time',
    'titleTextStyle' => array('fontSize' => 16),
    'hAxis' => array('title' => 'ms',
        'titleTextStyle' => array('bold' => true)),
    'vAxis' => array('minValue' => 0, 'maxValue' => 1),
    'width' => $graphWidth,
    'height' => $graphHeight,
    'bar' => array('groupWidth' => '90%'),
    'legend' => array('position' => 'none')
);
$type = 'ColumnChart';
list($chart_time, $div_time) = makeChartParts($data, $options, $type);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PHP Framework Benchmark</title>
        <script src="https://www.google.com/jsapi"></script>
        <script>
<?php
echo $chart_rpm, $chart_mem, $chart_time;
?>
        </script>
    </head>
    <body>
        <h1>PHP Framework Benchmark</h1>

        <h2><?php echo $modules[$module]?></h2>
        <p>
            <?php
            $as = array();
            foreach ($modules as $key => $value) {
                $as[] = '<a href="index.php?module='.$key.'">'.$value.'</a>';
            }
            echo implode(' | ', $as);
            ?>
        </p>
        <div>
            <?php
            echo $div_rpm, $div_mem, $div_time;
            ?>
        </div>

        <ul>
            <?php
            $url_file = __DIR__.'/output/'.$module.'/urls.log';
            if (file_exists($url_file)) {
                $urls = file($url_file);
                foreach ($urls as $url) {
                    $url = str_replace('127.0.0.1', $_SERVER['HTTP_HOST'], $url);
                    echo '<li><a target=_blank href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').
                    '">'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').
                    '</a></li>'."\n";
                }
            }
            ?>
        </ul>

        <hr>

        <footer>
            <p style="text-align: right">This page is a part of <a href="https://github.com/kenjis/php-framework-benchmark">php-framework-benchmark</a>.</p>
        </footer>
    </body>
</html>
