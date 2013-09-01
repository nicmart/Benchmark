<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container theme-showcase">
    <h1><?php echo $title; ?></h1>
    <hr>
    <?php foreach ($benchmarks as $benchmark): ?>
        <h5><?php echo $benchmark['iterations']; ?> iterations</h5>
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
                        <td><b><?php echo $row['title']; ?></b></td>
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
    <?php endforeach;?>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery.js"></script>
</body>
</html>