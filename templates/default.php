<!DOCTYPE html>
<html>
<head>
    <title><?php echo 'Benchmark Suite'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">


    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <link href="http://alexgorbatchev.com/pub/sh/current/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
    <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shCore.js" type="text/javascript"></script>
    <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shAutoloader.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPhp.js"></script>
    <style>
        .syntaxhighlighter code {
            padding: 0;
            background-color: transparent;
        }
        .syntaxhighlighter td {
            border: none !important;
        }
        .syntaxhighlighter .gutter .line {
            border: none !important;
        }
        .syntaxhighlighter .container {
            width: auto;
        }
        .icon-question-sign {
            color: #ddd;
        }
        div[class="tooltip-inner"] {
            max-width: 600px !important;
        }
    </style>
</head>
<body>
<div class="container theme-showcase">
<?php foreach ($groups as $groupIndex => $group): ?>

    <h1><?php echo $group->title; ?></h1>
    <hr>
    <?php foreach ($group->sets as $setIndex => $set): ?>
        <span class="btn btn-xs btn-info">
            Benchmark <?php echo $setIndex + 1; ?>: <b><?php echo $this->n($set->iterations)->format(); ?></b> iterations,
                <?php if ($set->inputSize): ?>size: <b><?php echo $this->n($set->inputSize)->format(); ?></b><?php endif; ?>
        </span>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Function</th>
                    <th>Iterations</th>
                    <th>Time</th>
                    <th>Average</th>
                    <?php foreach ($group->compareWith as $name): ?>
                        <th>Compare with <?php echo $group->funcTitles[$name]; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($set->benchmarks as $name => $benchmark): ?>
                    <tr>
                        <td><b><?php echo $group->funcTitles[$benchmark->name]; ?></b>
                            <?php if($set->iterations != $benchmark->iterations):?>
                                <sup><a href="#" data-toggle="tooltip" title="Iteration correction exponent">
                                    <?php echo is_callable($group->iterationsCorrections[$name]) ? 'custom' : $group->iterationsCorrections[$name]; ?></a>
                                </sup>
                            <?php endif; ?>
                            <small> - <a href="#" data-toggle="modal" data-target="#modal-<?php echo spl_object_hash($benchmark); ?>"> <i class="icon-code"></i> Code</a></small>
                            <?php echo $this->modal($benchmark); ?>
                        </td>
                        <td>
                            <?php echo $this->n($benchmark->iterations)->getSuffixNotation(); ?>
                            <?php if($set->iterations != $benchmark->iterations):?>
                                <i class="icon-question-sign" data-toggle="tooltip" data-placement="right"
                                    title="Actual iterations = iterations / (input size)^(correctionExp - 1)"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $this->n($benchmark->time)->round(3)->getSciNotation(); ?> s</td>
                        <td><?php echo $this->n($benchmark->getAverage())->round(3)->getSciNotation(); ?> s</td>
                        <?php foreach($benchmark->getComparisons() as $comparison): ?>
                            <td class="
                                <?php
                                    if ($comparison->ratio() > 1) echo "text-danger";
                                    elseif ($comparison->ratio() < 1) echo "text-success";
                                ?>">
                                <b><?php echo $this->n($comparison->ratio())->round(3)->format(); ?>&times;</b>
                                (<?php echo $comparison->percentualIncrease() > 0 ? '+' : '',
                                    $this->n($comparison->percentualIncrease())->round(3)->format()
                                ?>%)
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    <?php endforeach; ?>

    <?php if ($orders = $group->ordersOfGrowth()): ?>

        <i class="pull-left">Empyrical orders of growth:</i><ul class="list-inline">
        <?php foreach ($orders as $name => $order): ?><li>
              <b><?php echo $group->funcTitles[$name]; ?></b>:
              ~10<sup><?php echo $this->n($order)->round(2)->format(); ?></sup>
        </li><?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php $machineData = $group->getMachineData(); ?>
    <ul class="list-inline">
        <b>PHP version</b>: <?php echo $machineData->phpVersion(); ?>
        <?php if($cachingData = $machineData->opcodeCacheData()): ?>
            <b>Opcode Cache</b>: <?php echo $cachingData['title']; ?> v<?php echo $cachingData['version']; ?>
        <?php endif; ?>
        <b>Max Memory Usage</b> <?php echo $this->n(memory_get_peak_usage())->round(2)->getSuffixNotation(); ?>B
    </ul>

<?php endforeach; ?>
</div>
<!-- Modal -->
<script type="text/javascript">
    SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.all();

    $('[data-toggle="tooltip"]').tooltip();
</script>
</body>
</html>