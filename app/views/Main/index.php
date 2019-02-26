<script src="/public/js/pagination.js"></script>
<script src="/public/js/comments.js"></script>
<link rel="stylesheet" type="text/css" href="/public/css/comments.css">

<?php debug($_POST); ?>

<?php use app\models\Main; ?>

<div class="gallery">
<?php if (!empty($images)): ?>
    <div class="container">
        <?php foreach ($images as $image): ?>
        <?php if (file_exists(ROOT . '/public/images/'. $image['image'])): ?>
            <div class="item">
                <label class="invisible" for="title">User picture <?=$image['login']?></label>
<!--                    <a href="#modal">-->
                <a>
                        <img class="front" width="300" src="<?= '/public/images/'. $image['image']; ?>" alt="">
                        <span class="back">
<!--                            <img src="/public/icons/eye.png">-->
                        </span>
                        <br />Number of likes :<br />

                        <div id="addCommentContainer">
                            <p>Add comment</p>
                            <form id="addCommentForm" method="post" action="">
                                <div>
                                    <textarea name="body" id="body" cols="20" rows="4"></textarea>
                                    <input type="submit" id="btnSubmit" value="Send" />
                                </div>
                            </form>
                        </div>
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
