<?php

use yii\helpers\Html;
use app\components\StoreUrl;
use common\components\CurrentStore;
use common\models\store\StoreFavoriteList;
use frontend\components\Assets;

$store = CurrentStore::getStore();
$this->title = 'My Favorites';
$this->params['breadcrumbs'][] = ['label' => 'My Account', 'url' => ["/$store->url/account/overview"]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page--content-favorites account page--content-pad">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 col-md-3">
                <div class="sidebar">
                    <?php echo $this->render('../account/_nav.php') ?>
                </div>
            </div>
            <div class="col-sm-9 col-md-9 col-lg-9">
                <div class="tile-container">
                    <?php if ($lists): ?>
                        <?php foreach ($lists as $list): ?>
                            <div class="tile">
                                <a href="<?= StoreUrl::to('favorites/update/' . $list->list_id) ?>">
                                    <div class="tile-img">
                                        <div class="favorite-folder" style="background-image:url('<?= Assets::themeResource("favorites/favorites_folder_image.png"); ?>')"></div>
                                    </div>
                                    <div class="tile-body">
                                        <h4 class="favorites--tile-heading">
                                            <?= $list->title ?>
                                        </h4>
                                        <h5 class="tile-products"><?= StoreFavoriteList::getStoreFavoriteListItemsCount($list->list_id) ? StoreFavoriteList::getStoreFavoriteListItemsCount($list->list_id) : 0; ?>
                                            Products</h5>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>Import or Create a new list</h3>
                    <?php endif; ?>
                </div>
                <hr/>
                <div class="clearfix">
                    <a href="#" class="btn btn-primary" data-target="#createModal" data-toggle="modal">Create a list</a>
                    <span class="pull-right">
                         <?= Html::beginForm([StoreUrl::to('favorites/csv-upload')], 'post', ['enctype'=>"multipart/form-data", 'id' => "upload-csv"]); ?>
                        <label for="file-upload" class="btn btn-primary import-csv">Import CSV
                    </label>
                    <input id="file-upload" name="file" type="file"/>
                        <?= Html::endForm(); ?>
                        </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal modal__ui fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create New List</h5>
            </div>
            <div class="modal-body clearfix">
                <?= Html::beginForm([StoreUrl::to('favorites/create-list')], 'post'); ?>
                <div class="form-group">
                    <?= Html::input('text', 'title', '', ['class' => 'form-control']) ?>
                </div>
                <?= Html::submitButton('Create list', ['class' => 'btn btn-primary pull-right']) ?>
                <?= Html::endForm(); ?>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById("file-upload").onchange = function() {
        document.getElementById("upload-csv").submit();
    };
</script>