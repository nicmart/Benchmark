<!DOCTYPE html>
<html>
<head>
    <title><?php echo 'Benchmark Suite'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">


    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

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
            Benchmark <?php echo $setIndex + 1; ?>: <b><?php echo number_format($set->iterations); ?></b> iterations,
                <?php if ($set->inputSize): ?>size: <b><?php echo number_format($set->inputSize); ?></b><?php endif; ?>
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
                            <?php modal($benchmark); ?>
                        </td>
                        <td>
                            <?php echo $benchmark->iterations; ?>
                            <?php if($set->iterations != $benchmark->iterations):?>
                                <i class="icon-question-sign" data-toggle="tooltip" data-placement="right"
                                    title="Actual iterations = iterations / (input size)^(correctionExp - 1)"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo scientific($benchmark->time); ?> s</td>
                        <td><?php echo scientific($benchmark->getAverage()); ?> s</td>
                        <?php foreach($benchmark->getComparisons() as $comparison): ?>
                            <td class="
                                <?php
                                    if ($comparison->ratio() > 1) echo "text-danger";
                                    elseif ($comparison->ratio() < 1) echo "text-success";
                                ?>">
                                <b><?php echo number_format($comparison->ratio(), 3); ?>&times;</b>
                                (<?php echo $comparison->percentualIncrease() >= 0 ? '+' : '', number_format($comparison->percentualIncrease(), 3); ?>%)
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
              ~10<sup><?php echo number_format($order, 2); ?></sup>
        </li><?php endforeach; ?>
    </ul>
    <?php endif; ?>
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
<?php
function scientific($number, $precision = 2)
{
    if ($number >= 0.0001)
        return number_format($number, 4 + $precision);

    $s = sprintf("%.{$precision}e", $number);
    list($main, $exp) = explode('e', $s);

    return sprintf("%s &times; 10<sup>%s</sup>", $main, $exp);
}
function modal(\Nicmart\Benchmark\BenchmarkResult $benchmark)
{
?>
    <div class="modal fade" id="modal-<?php echo spl_object_hash($benchmark); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo $benchmark->getTitle(); ?></h4>
          </div>
          <div class="modal-body">
<pre class="brush: php">
<?php echo $benchmark->getCode(); ?>
</pre>
          </div>
            <div class="modal-footer small">
                <ul class="list-inline" style="margin: 0; padding: 0">
                    <?php if ($benchmark->getInputSize()): ?><li>Input Size: <b><?php echo $benchmark->getInputSize(); ?></b></li><?php endif; ?>
                    <li>Iterations: <b><?php echo number_format($benchmark->iterations); ?></b></li>
                    <li>Average Time: <b><?php echo scientific($benchmark->getAverage()); ?> s</b></li>
                </ul>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php
}