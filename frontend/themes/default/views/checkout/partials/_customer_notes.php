<div class="panel panel__ui">
    <div class="panel-heading panel__ui-heading">
        <h3 class="panel__ui-heading-ttl">Customer Notes</h3>
    </div>
    <div class="panel-body panel__ui-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'customer_note')->textarea([
                        'class'         => 'form-control note',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>