<link rel="stylesheet" type="text/css" href="/public/css/comments.css">
<?php if (isset($_SESSION['user']) && $_SESSION['user']['activate'] === '0') $_SESSION['error'] = 'For comment and like images, you need to activate your account'; ?>
<center>
    <div class="gallery">
        <?php if (!empty($images)): ?>
            <div class="container">
                <?php foreach ($images as $image): ?>
                    <?php if (file_exists(ROOT . '/public/images/'. $image['image'])): ?>
                        <div class="item">
                            <label class="invisible" for="title">User picture <?=$image['login']?></label>
                            <a href="#modal">
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
</center>
<script src="/public/js/modal.js"></script>
<div class="modal" id="modal"></div>

