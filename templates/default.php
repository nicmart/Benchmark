<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">


    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.js"></script>

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
    </style>
</head>
<body>
<div class="container theme-showcase">
    <h1><?php echo $title; ?></h1>
    <hr>
    <?php foreach ($benchmarks as $i => $benchmark): ?>
        <span class="btn btn-xs btn-info">Benchmark <?php echo $i + 1; ?>: <b class="warning"><?php echo $benchmark['iterations']; ?></b> iterations</span>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Function</th>
                    <th>Time</th>
                    <?php foreach ($benchmark['compareWith'] as $data): ?>
                        <th>Compare with <?php echo $data['title'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($benchmark['rows'] as $row): ?>
                    <tr>
                        <td><b><?php echo $row['title']; ?></b>
                            <small> - <a href="#" data-toggle="modal" data-target="#modal-<?php echo $row['name']; ?>"> <i class="icon-code"></i> Code</a></small>
                            <?php modal($row); ?>
                        </td>
                        <td><?php echo $row['time']; ?> s</td>
                        <?php foreach($row['comparisons'] as $comparisonData): ?>
                            <td class="
                                <?php
                                    if ($comparisonData['ratio'] > 1) echo "text-danger";
                                    elseif ($comparisonData['ratio'] < 1) echo "text-success";
                                ?>">
                                <b><?php echo number_format($comparisonData['ratio'], 3); ?>&times;</b>
                                (<?php echo $comparisonData['percentIncrease'] >= 0 ? '+' : '', number_format($comparisonData['percentIncrease'], 3); ?>%)
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    <?php endforeach; var_dump($benchmark); ?>
</div>
<!-- Modal -->
<script type="text/javascript">
    SyntaxHighlighter.defaults['toolbar'] = false;
     SyntaxHighlighter.all()
</script>
</body>
</html>
<?php
function modal($row)
{
?>
    <div class="modal fade" id="modal-<?php echo $row['name']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo $row['title']; ?></h4>
          </div>
          <div class="modal-body">
<pre class="brush: php">
<?php echo $row['code']; ?>
</pre>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php
}