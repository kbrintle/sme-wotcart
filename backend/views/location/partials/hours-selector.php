<div class="row"
    ng-controller="HoursSelectorController"
    ng-init='setStoreHours(<?php echo $model->hours; ?>)'>
    <div class="col-xs-12">
        <div class="row col-xs-12 col-md-4"
             ng-repeat="(day, hour) in storeHours">
            <div class="form-inline">
                <h5 style="text-transform: capitalize;">{{day}}</h5>
                <div class="form-group">
                    <label>Open</label>
                    <select class="form-control"
                            ng-model="hour.open">
                        <option ng-repeat="time in $parent.timeOptions"
                                ng-value="time">{{time}}</option>
                    </select>

                    <label>Close</label>
                    <select class="form-control"
                            ng-model="hour.close">
                        <option ng-repeat="time in $parent.timeOptions"
                                ng-value="time">{{time}}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'hours')->hiddenInput([
        'ng-value'  => 'getStoreHours()'
    ])->label(false); ?>
</div>