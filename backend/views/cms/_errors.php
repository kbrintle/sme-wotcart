<?php if($errors): ?>
    <div class="row col-xs-12">
        <div class="alert alert-danger">
            <ul class="llist-unstyled">
                <?php foreach($errors as $k=>$v): ?>
                    <li>
                        <strong><?= $k; ?></strong> :
                        <ul>
                            <?php foreach($v as $e): ?>
                                <li><?= $e ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>