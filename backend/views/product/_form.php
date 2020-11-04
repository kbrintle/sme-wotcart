<?php

use common\models\core\Store;
use common\models\catalog\CatalogAttributeType;
use common\models\catalog\CatalogAttributeOption;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogBrand;
use common\components\helpers\FormHelper;
use nkovacs\datetimepicker\DateTimePicker;
use common\models\catalog\CatalogStoreProduct;
use common\models\catalog\CatalogProduct;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$catalogBrands = ArrayHelper::map(CatalogBrand::find()->where(['is_active' => true, 'is_deleted' => false])->all(), 'id', 'name');

// Group field types that are similar enough
// to use the same HTML markup
$stores = Store::getStoreList(true);
$repeatable = [
    CatalogAttributeType::TEXT,
    CatalogAttributeType::DATE,
    CatalogAttributeType::TEL,
    CatalogAttributeType::URL,
    CatalogAttributeType::FILE
];

?>
<div id="save" class="alert alert-fixed">Saved</div>
<div class="product-form">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <?php if ((isset($isCreate) || isset($isOwner))): ?>
                <?php $activeTab = true; ?>
                <li role="presentation" class="active"><a href="#base-settings" role="tab" data-toggle="tab">Base
                        Settings</a></li>
            <?php endif; ?>
            <!--            --><?php //print_r($attributes); die; ?>
            <?php foreach ($attributes as $key => $attribute): ?>
                <?php if (!empty($attribute)): ?>
                    <?php
                    $index = array_search($key, array_keys($attributes));
                    $slug = strtolower(str_replace(' ', '-', $key));
                    if ($slug == "images") {
                        continue;
                    }
                    ?>
                    <li role="presentation" <?= !$index && !isset($activeTab) ? 'class="active"' : '' ?>>
                        <a href="#<?= $slug ?>" role="tab" data-toggle="tab"><?= $key ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            <li role="presentation"><a href="#Stores" role="tab" data-toggle="tab">Stores</a></li>
            <?php if (isset($isUpdate)): ?>
                <li role="presentation"><a href="#images" role="tab" data-toggle="tab">Images</a></li>
            <?php endif; ?>
            <?php if ((isset($isUpdate) && isset($isOwner)) && ($product_type == CatalogProduct::GROUPED || $product_type == CatalogProduct::CONFIGURABLE)): ?>
                <?php $activeTab = true; ?>
                <li role="presentation"><a href="#associated-products" role="tab"
                                           data-toggle="tab">Associated Products</a></li>
            <?php endif; ?>
            <?php if ($product_type == CatalogProduct::SIMPLE): ?>
                <?php $activeTab = true; ?>

                <li role="presentation"><a href="#options" role="tab" data-toggle="tab">Custom Options</a></li>
            <?php endif; ?>
            <?php if (isset($isUpdate)): ?>
                <li role="presentation"><a href="#related-products" role="tab" data-toggle="tab">Related
                        Products</a></li>
                <li role="presentation"><a href="#product-attachments" role="tab" data-toggle="tab">Attachments</a></li>
            <?php endif; ?>
        </ul>

        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => "productForm",
                'class' => 'form',
                'enctype' => 'multipart/form-data'
            ]
        ]); ?>
        <input type="hidden" id="current-tab" name="current-tab" value="">
        <div class="tab-content">
            <br>
            <div role="tabpanel" class="tab-pane active" id="base-settings">
                <div class="form-group">
                    <label class="control-label">Categories</label>
                    <br>
                    <?= Html::dropDownList('CatalogCategoryProduct[category_id]', $category_ids, $categoriesArray, [
                        'multiple' => 'multiple',
                    ]); ?>
                </div>
                <?= $form->field($model, 'slug')->textInput()->label('URL Key'); ?>

                <?= $form->field($model, 'brand_id')->dropdownList($brandsArray, ['prompt' => 'Select one'])->label('Brand'); ?>

                <?php if (!empty($featuresArray)): ?>
                    <?= $form->field($model, 'selected_features')->dropdownList(
                        $featuresArray,
                        [
                            'multiple' => 'multiple',
                            'options' => $featuresOptions
                        ]
                    )->label('Features'); ?>
                <?php endif; ?>

            </div>

            <div role="tabpanel" class="tab-pane" id="Stores">
                <ul class="list-unstyled">
                    <?php foreach ($stores as $id => $name): ?>
                        <li>
                            <span>
                                <input type="checkbox"
                                       name="stores[<?php echo $id ?>]" <?php echo (CatalogStoreProduct::isEnabled($model->id, $id)) ? 'checked="checked"' : '' ?>/>
                            </span>
                            <span><?= $name ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php foreach ($attributes as $key => $attribute): ?>
                <?php
                $index = array_search($key, array_keys($attributes));
                $slug = strtolower(str_replace(' ', '-', $key));
                if ($slug == "images") {
                    continue;
                }
                ?>
                <div role="tabpanel" class="tab-pane <?= !$index && !isset($activeTab) ? 'active' : '' ?>"
                     id="<?= $slug ?>">
                    <?php if (!empty($attribute)): ?>
                        <?php foreach ($attribute as $field): ?>
                            <?php
                            $format = CatalogAttributeType::findOne($field->type_id)->format;
                            $disabled = !isset($isCreate) && !isset($isOwner) && !$field->is_editable ? 'disabled' : '';
                            $value = isset($isUpdate) ? CatalogAttributeValue::storeValue($field->slug, $model->id) : '';
                            ?>
                            <?php if (in_array($field->type_id, $repeatable)): ?>
                                <div class="form-group">
                                    <label class="control-label" for="<?= $field->slug; ?>-value">
                                        <?= $field->label; ?>
                                    </label>
                                    <input type="<?= ($format == 'number') ? 'text' : $format ; ?>" id="<?= $field->slug; ?>-value" class="form-control"
                                           name="Attribute[<?= $field->id; ?>]"
                                           value="<?= htmlentities($value); ?>" <?= $disabled; ?>/>
                                </div>
                            <?php else: ?>
                                <?php switch ($field->type_id):
                                    case CatalogAttributeType::SELECT: ?>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="<?= $field->slug; ?>-value"><?= $field->label; ?></label>
                                            <select id="<?= $field->slug; ?>-value" class="form-control"
                                                    name="Attribute[<?= $field->id; ?>]" <?= $disabled; ?>>
                                                <option value="">None</option>
                                                <?php foreach (CatalogAttributeOption::findAll(['attribute_id' => $field->id, 'is_active' => true, 'is_deleted' => false]) as $option): ?>
                                                    <?php $selected = (isset($isUpdate) ? CatalogAttributeValue::storeValue($field->slug, $model->id) == $option->id ? 'selected' : '' : ''); ?>
                                                    <option value="<?= $option->id; ?>" <?= $selected; ?>><?= $option->value; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <?php break; ?>
                                    <?php case CatalogAttributeType::IMAGE: ?>
                                        <?php break; ?>
                                    <?php case CatalogAttributeType::MULTISELECT: ?>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="<?= $field->slug; ?>-value"><?= $field->label; ?></label>
                                            <select id="<?= $field->slug; ?>-value" class="form-control"
                                                    name="Attribute[<?= $field->id; ?>][]"
                                                    multiple="multiple" <?= $disabled; ?>>
                                                <option value="">None</option>
                                                <?php if ($field->slug == 'compatible-brands'): ?>
                                                    <?php $options = CatalogBrand::getAvailableBrands(); ?>
                                                <?php else: ?>
                                                    <?php $options = CatalogAttributeOption::findAll(['attribute_id' => $field->id, 'is_active' => true, 'is_deleted' => false]); ?>
                                                <?php endif; ?>
                                                <?php foreach ($options as $option): ?>
                                                    <?php $selected = (isset($isUpdate) ? in_array($option->id, CatalogAttributeValue::storeValue($field->slug, $model->id, true)) ? 'selected' : '' : ''); ?>
                                                    <option value="<?= $option->id; ?>" <?= $selected; ?>><?= $field->slug == 'compatible-brands' ? $option->name : $option->value; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <?php break; ?>
                                    <?php case CatalogAttributeType::TEXTAREA: ?>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="<?= $field->slug; ?>-value"><?= $field->label; ?></label>
                                            <textarea id="<?= $field->slug; ?>-value" class="form-control"
                                                      name="Attribute[<?= $field->id; ?>]" <?= $disabled; ?>><?= $value; ?></textarea>
                                        </div>
                                        <?php break; ?>
                                    <?php case CatalogAttributeType::NUMBER: ?>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="<?= $field->slug; ?>-value"><?= $field->label; ?></label>
                                            <input type="text" step="0.01" id="<?= $field->slug; ?>-value"
                                                   class="form-control" name="Attribute[<?= $field->id; ?>]"
                                                   value="<?= $value; ?>" <?= $disabled; ?>>
                                        </div>
                                        <?php break; ?>
                                    <?php case CatalogAttributeType::BOOLEAN: ?>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="<?= $field->slug; ?>-value"><?= $field->label; ?></label>
                                            <select id="<?= $field->slug; ?>-value" class="form-control"
                                                    name="Attribute[<?= $field->id; ?>]" <?= $disabled; ?>>
                                                <option value="">None</option>
                                                <?php foreach (FormHelper::getBooleanValues() as $key => $value): ?>
                                                    <?php $selected = (isset($isUpdate) ? CatalogAttributeValue::storeValue($field->slug, $model->id) == $key ? 'selected' : '' : ''); ?>
                                                    <option value="<?= $key; ?>" <?= $selected; ?>><?= $value; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <?php break; ?>
                                    <?php case CatalogAttributeType::DATETIME: ?>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="<?= $field->slug; ?>-value"><?= $field->label; ?></label>
                                            <?php echo DateTimePicker::widget([
                                                'id' => "$field->slug-value",
                                                'name' => "Attribute[$field->id]",
                                                'value' => $value
                                            ]);
                                            ?>
                                        </div>
                                        <?php break; ?>
                                    <?php endswitch; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted">No editable properties.</span>
                    <?php endif; ?>
                    <?php if ($slug == "prices"): ?>
                        <?php echo Yii::$app->controller->renderPartial('partials/_tierprices', ['model' => $model]); ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <div role="tabpanel" class="tab-pane" id="options">
                <br>
                <input type="text" class="form-control hidden" name="AttributeForm[options]" value=""
                       placeholder="Option Title" readonly>
                <?php echo Yii::$app->controller->renderPartial('partials/_options', ['model' => $model]); ?>
                <?php ActiveForm::end(); ?>
            </div>

            <br>
            <div role="tabpanel" class="tab-pane" id="images">
                <?php echo Yii::$app->controller->renderPartial('partials/_media',
                    ['model' => $model]); ?>
            </div>
            <?php if (isset($isUpdate)): ?>
                <?php if ($product_type == CatalogProduct::GROUPED || $product_type == CatalogProduct::CONFIGURABLE): ?>
                    <div role="tabpanel" class="tab-pane" id="associated-products">
                        <?php echo Yii::$app->controller->renderPartial('partials/_associatedproducts', ['model' => $model, 'catalogBrands' => $catalogBrands]); ?>
                    </div>
                <?php endif; ?>
                <div role="tabpanel" class="tab-pane" id="related-products">
                    <?php echo Yii::$app->controller->renderPartial('partials/_relatedproducts', ['model' => $model, 'catalogBrands' => $catalogBrands]); ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="product-attachments">
                    <?php echo Yii::$app->controller->renderPartial('partials/_attachments', ['model' => $model]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<script src='/admin/_assets/src/js/tinymce/tinymce.min.js'></script>
<script> tinymce.init({
        selector: '#description-value',
        height: 200,
        plugins: ["advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste image imagetools"],
        toolbar: "code | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: false,
        branding: false,
        file_picker_types: 'image',
        extended_valid_elements: "script[src|type|language]",
        images_upload_url: "<?= Url::to(["product/tinymceupload/$model->id"])?>",
        images_upload_base_path: '/uploads/products',
        relative_urls: true,
        remove_script_host: false,
        convert_urls: false,
        image_title: true,
        automatic_uploads: true,
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                var file = this.files[0];
                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var blobInfo = blobCache.create(id, file);
                blobCache.add(blobInfo);
                cb(blobInfo.blobUri(), {title: file.name});
            };
            input.click();
        }
    });

    tinymce.init({
        selector: '#short-description-value',
        height: 200,
        plugins: ["advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste image imagetools"],
        toolbar: "code | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: false,
        branding: false,
        file_picker_types: 'image',
        extended_valid_elements: "script[src|type|language]",
        images_upload_url: "<?= Url::to(["product/tinymceupload/$model->id"])?>",
        images_upload_base_path: '/uploads/products',
        relative_urls: true,
        remove_script_host: false,
        convert_urls: false,
        image_title: true,
        automatic_uploads: true,
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                var file = this.files[0];
                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var blobInfo = blobCache.create(id, file);
                blobCache.add(blobInfo);
                cb(blobInfo.blobUri(), {title: file.name});
            };
            input.click();
        }
    });

</script>