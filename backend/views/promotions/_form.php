<div class="form-group">
    <div class="col-md-4 col-md-offset-4">
        <?php echo $form->field($model, 'label')->textInput(['maxlength' => true]); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-md-2 col-md-offset-4">
        <label class="control-label" for="starts_at-value">Promotion Starts</label>
        <input type="date" id="starts_at-value" class="form-control" name="starts_at"
               value="<? if (isset($model->starts_at)) {
                   echo date("Y-m-d", $model->starts_at);
               } ?>">
    </div>
</div>

<div class="form-group">
    <div class="col-md-2">
        <label class="control-label" for="ends_at-value">Promotion Ends</label>
        <input type="date" id="ends_at-value" class="form-control" name="ends_at"
               value="<? if (isset($model->ends_at)) {
                   echo date("Y-m-d", $model->ends_at);
               } ?>">
    </div>
</div>
