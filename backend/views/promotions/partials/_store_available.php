<?php if (empty($available)): ?>

    <table class="table">
        <tbody>
        <tr>
            <td><i class="material-icons">info</i></td>
            <td>
                <b>No promotions are available.</b>
                <br />Either you're out of promotions or haven't created any yet.
            </td>
        </tr>
        </tbody>
    </table>

<?php else: ?>
    <?php foreach($available as $promotion): ?>

        <?= $this->render('_promo_accordion', [
            'promotion' => $promotion,
            'available' => true
        ]) ?>

    <?php endforeach; ?>
<?php endif; ?>