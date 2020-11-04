<?php

use yii\helpers\Html;

?>

<?= Html::button('Reset Filters', ["id" => 'resetRelatedFilters', 'class' => 'btn btn-default pull-left']); ?>
<?= Html::button('Search', ["id" => 'relatedSearch', 'class' => 'btn btn-primary pull-left left-btn-space']); ?>

<br><br>

<table id="related-table" data-pid="<?= $model->id ?>" class="table table-hover data-table grouped-table dataTable" style="width: 100%;">
    <thead>
    <tr>
        <td><select class="form-control">
                <option value="1">yes</option>
                <option value="2">no</option>
                <option value="0">any</option>
            </select></td>
        <td><input class="form-control column-search-box" type="text" placeholder="Name Search"/></td>
        <td><input class="form-control column-search-box" type="text" placeholder="Sku Search"/></td>
        <td>
            <div class="field">
                <select class="form-control">
                    <option value="0">all</option>
                    <?php foreach ($catalogBrands as $id => $name): ?>
                        <option value=<?= $id ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td><input class="form-control column-search-box" type="text" placeholder="Sort Order"/></td>
    </tr>
    <tr>
        <td><label>Associated</label></td>
        <td><label>Name</label></td>
        <td><label>Sku</label></td>
        <td><label>Brand</label></td>
        <td><label>Sort</label></td>
    </tr>
    </thead>
</table>