
<?php
use common\models\core\Store;
$stores = Store::find()->where(['is_active'=>true])->all();
//print_r($store); die;
?>

    <div class="snize" id="snize_container"></div>

    <script type="text/javascript">
        SearchaniseAdmin = {};
        SearchaniseAdmin.host = 'https://www.searchanise.com';
        SearchaniseAdmin.PrivateKey = '3R9m5X8B0K2v9S4r6o1e';
        SearchaniseAdmin.ReSyncLink = '<?php echo Yii::$app->params['base_url']?>/admin/searchanise/start_resync';
        SearchaniseAdmin.LastRequest = '12.06.2018';
        SearchaniseAdmin.LastResync = '12.06.2018';
        SearchaniseAdmin.ConnectLink = '<?php echo Yii::$app->params['base_url'] ?>/admin/searchanise/connect';
        SearchaniseAdmin.AddonStatus = 'enabled';
        SearchaniseAdmin.ShowResultsControlPanel = true;
        SearchaniseAdmin.Engines = [];
        <?php foreach($stores as $store):?>
            SearchaniseAdmin.Engines.push(
                {
                PrivateKey: '<?php echo $store->searchanise_private_key ?>',
                LangCode: 'EN',
                Name : '<?php echo $store->name ?>',
                ExportStatus: 'done',
                PriceFormat: {
                    rate : 1.0,
                    symbol: '$',
                    decimals: 2,
                    decimals_separator: '.',
                    thousands_separator: ',',
                    after: false
                }

            });
        <?php endforeach; ?>
    </script>
    <script type="text/javascript" src="https://www.searchanise.com/js/init.js"></script>
