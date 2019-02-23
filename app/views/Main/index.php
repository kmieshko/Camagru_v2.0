<script src="/public/js/pagination.js"></script>

<div class="gallery">
<?php if (!empty($images)): ?>
    <div class="container">
        <?php foreach ($images as $image): ?>
        <?php if (file_exists(ROOT . '/public/images/'. $image['image'])): ?>
            <div class="item">
                <label class="invisible" for="title">User picture <?=$image['login']?></label>
                    <a href="#modal">
                        <img class="front" width="300" src="<?= '/public/images/'. $image['image']; ?>" alt="">
                        <span class="back">
                            <img src="/public/icons/eye.png">
                        </span>
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

<div class="modal" id="modal">
    <div class="modal-container">
            <header>
                <h2><label></label></h2>
            </header>
            <section>
                <img src="">
            </section>
            <footer class="footer">
                <a href="#" class="btn"><input type="button" value="Close"></a>
            </footer>
    </div>
</div>

<script src="/public/js/modal.js"></script>
