<?php
/** @var \App\Model\Comment $comment */
/** @var \App\Service\Router $router */

$title = 'New Comment';
$bodyClass = 'create';

ob_start(); ?>

    <h1>Add Comment</h1>

    <form action="<?= $router->generatePath('comment-create') ?>" method="post">
        <div>
            <label>Author:
                <input type="text" name="author" value="">
            </label>
        </div>
        <div>
            <label>Content:
                <textarea name="content"></textarea>
            </label>
        </div>
        <button type="submit">Save</button>
    </form>

    <br>
    <a href="<?= $router->generatePath('comment-index') ?>">Back to list</a>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';

