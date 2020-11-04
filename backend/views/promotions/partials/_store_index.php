<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#enabled" aria-controls="enabled" role="tab" data-toggle="tab">Enabled <span class="badge"><?= $badges[0]; ?></span></a>
    </li>
    <li role="presentation">
        <a href="#available" aria-controls="available" role="tab" data-toggle="tab">Available <span class="badge"><?= $badges[1]; ?></span></a>
    </li>
</ul>

<br />

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="enabled">
        <?= $this->render('_store_enabled', [
            'enabled'  => $enabled,
        ]) ?>
    </div>

    <div role="tabpanel" class="tab-pane" id="available">
        <?= $this->render('_store_available', [
            'available'  => $available,
        ]) ?>
    </div>
</div>
