<script src="/public/js/pagination.js"></script>
<link rel="stylesheet" type="text/css" href="/public/css/comments.css">


<?php debug($_POST); ?>

<?php use app\models\Main; ?>

<?php $model = new Main(); ?>

<div class="gallery">
<?php if (!empty($images)): ?>
    <div class="container">
        <?php $i = 0; ?>
        <?php foreach ($images as $image): ?>
        <?php $i++;?>
        <?php if (file_exists(ROOT . '/public/images/'. $image['image'])): ?>
            <div class="item" id="item-<?=$i;?>">
                <label class="invisible" for="title">User picture <?=$image['login']?></label>
                    <a href="#modal" id="modal-<?=$i;?>">
                        <img class="front" width="300" src="<?= '/public/images/'. $image['image']; ?>" alt="">
                        <span class="back"></span>
                    </a>
            </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No images yet</p>
<?php endif; ?>

<div class="clear"></div>
<?php if ($pagination->countPages > 1): ?>
    <?= $pagination ?>
<?php endif; ?>
</div>

<script src="/public/js/modal.js"></script>

<div class="modal" id="modal"></div>


