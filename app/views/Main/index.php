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

<!--                    <div id="main---><?//=$i;?><!--" class="invisible">-->
<!--                        --><?php //$comments = $model->FindBySql("SELECT * FROM `comments` WHERE `image` LIKE '%{$image['image']}'"); ?>
<!--                        --><?php //foreach ($comments as $comment): ?>
<!--                        <div class="comment">-->
<!--                        --><?php //echo $model->markUp($comment['login'], $comment['text'], $comment['date']); ?>
<!--                        </div>-->
<!--                        --><?php //endforeach;?>
<!--                        <div id="addCommentContainer---><?//=$i;?><!--">-->
<!--                            <p>Add comment</p>-->
<!--                            <div id="addCommentForm---><?//=$i;?><!--">-->
<!--                                <div>-->
<!--                                    <textarea name="body" id="body---><?//=$i;?><!--" cols="20" rows="4"></textarea>-->
<!--                                    <input type="submit" id="btnSubmit---><?//=$i;?><!--" value="Send" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
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

<script src="/public/js/test.js"></script>
<!--<script src="/public/js/comments.js"></script>-->

<div class="modal" id="modal">
    <?php if (isset($_POST['img'])) : ?>
<!--        <div class="modal-container">-->
<!--            <header><h2>User picture test</h2><header>-->
<!--            <section><img src="--><?//=$_POST['img']; ?><!--"></section>-->
<!--            <footer class="footer">-->
<!--                <a href="#" class="btn"><input type="button" value="Close"></a>-->
<!--            </footer>-->
<!--        </div>-->
    <?php endif; ?>

</div>

<!--<script src="/public/js/modal.js"></script>-->






<!---->
<!--<a href="#modal">-->
<!--    <img class="front" width="300" src="--><?//= '/public/images/'. $image['image']; ?><!--" alt="">-->
<!--    <span class="back">-->
<!--                            <img src="/public/icons/eye.png">-->
<!--                        </span>-->
<!--    <br />Number of likes :<br />-->

<!--                            <div id="main---><?//=$i;?><!--">-->
<!--                                --><?php //$comments = $model->FindBySql("SELECT * FROM `comments` WHERE `image` LIKE '%{$image['image']}'"); ?>
<!--                                --><?php //foreach ($comments as $comment): ?>
<!--                                <div class="comment">-->
<!--                                --><?php //echo $model->markUp($comment['login'], $comment['text'], $comment['date']); ?>
<!--                                </div>-->
<!--                                --><?php //endforeach;?>
<!--                                <div id="addCommentContainer---><?//=$i;?><!--">-->
<!--                                    <p>Add comment</p>-->
<!--                                    <div id="addCommentForm---><?//=$i;?><!--">-->
<!--                                        <div>-->
<!--                                            <textarea name="body" id="body---><?//=$i;?><!--" cols="20" rows="4"></textarea>-->
<!--                                            <input type="submit" id="btnSubmit---><?//=$i;?><!--" value="Send" />-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--</a>-->



<!--<div id="main-modal"></div>-->


