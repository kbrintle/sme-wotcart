<?php if( $attribute_options ): ?>
    <?php $i=0;
    foreach( $attribute_options as $attribute_option): ?>
        <div class="input-group" id="option-group-<?= $i; ?>">
            <span class="input-group-btn sortable_handler">
                <i class="material-icons">drag_handle</i>
            </span>
            <input type="text" class="form-control" name="AttributeForm[attribute_options][]" placeholder="Option text..." value="<?= $attribute_option->value; ?>">
            <span class="input-group-btn">
                <button type="button" id="remove-option" data-index="<?= $i; ?>" class="btn btn-default">
                    <i class="material-icons">delete</i>
                </button>
            </span>
        </div>
    <?php $i++;
    endforeach; ?>
<?php endif; ?>