<div class="row">
    <div class="col-xs-12">
        <?= $form->field($model, 'title')->textInput([
            'class' => 'form-control'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?= $form->field($model, 'key')->textInput([
            'class' => 'form-control'
        ]); ?>
    </div>
</div>
<div class="row"
    ng-controller="WizardOptionsController"
    ng-init='options=<?= $model->jsonOptions; ?>'>
    <div class="col-xs-12">
        <i class="material-icons"
           ng-click="addOption()">add_circle</i>
    </div>
    <div class="col-xs-12">
        <div class="row"
             ng-repeat="option in options">

            <div class="col-xs-12 col-md-3">
                <input type="text" class="form-control" placeholder="icon"
                       ng-model="options[$index].icon" />
            </div>
            <div class="col-xs-12 col-md-3">
                <input type="text" class="form-control" placeholder="label"
                       ng-model="options[$index].label" />
            </div>
            <div class="col-xs-12 col-md-3">
                <input type="text" class="form-control" placeholder="value"
                       ng-model="options[$index].value" />
            </div>
            <div class="col-xs-12 col-md-3">
                <i class="material-icons"
                   ng-click="removeOption($index)">remove_circle</i>
            </div>
        </div>
    </div>
</div>