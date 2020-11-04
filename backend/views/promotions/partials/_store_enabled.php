<?php if (empty($enabled)): ?>

    <table class="table">
        <tbody>
        <tr>
            <td><i class="material-icons">info</i></td>
            <td>
                <b>No promotions are enabled.</b>
                <br />Either you're out of promotions or haven't enabled any yet.
            </td>
        </tr>
        </tbody>
    </table>

<?php else: ?>
    <?php foreach($enabled as $promotion): ?>

        <?= $this->render('_promo_accordion', [
            'promotion' => $promotion,
            'enabled'   => true
        ]) ?>

    <?php endforeach; ?>
<?php endif; ?>