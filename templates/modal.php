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
                <li>Average Time: <b><?php echo $this->scientific($benchmark->getAverage()); ?> s</b></li>
            </ul>
        </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->