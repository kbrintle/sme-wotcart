<div class="row action-row">
    <div class="col-md-12 clearfix">
        <h3 class="pull-left">Active Attributes</h3>
    </div>
</div>

<div class="row">
    <div id="sortable" class="masonry_container attribute_sortable" data-callback="attribute_sets">

        <?php foreach($all_attributes as $attribute):
            if($attribute->is_default):
                echo $this->render('attributes/_default', [
                    'attribute' => $attribute
                ]);
            else:
                echo $this->render('attributes/_editable', [
                    'attributeSetId'    => $attributeSetId,
                    'attribute'         => $attribute
                ]);
            endif;
        endforeach; ?>
    </div>
</div>