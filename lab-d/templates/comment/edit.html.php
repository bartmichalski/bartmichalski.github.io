<?php
/** @var \App\Model\Comment $comment */
/** @var \App\Service\Router $router */

$title = 'Edit Comment';
$bodyClass = 'edit';

ob_start(); ?>

    <h1>Edit Comment</h1>

    <form action="<?= $router->generatePath('comment-edit', ['id' => $comment->getId()]) ?>" method="post">
        <div>
            <label>Author:
                <input type="text" name="author" value="<?= htmlspecialchars($comment->getAuthor()) ?>">
            </label>
        </div>
        <div>
            <label>Content:
                <textarea name="content"><?= htmlspecialchars($comment->getContent()) ?></textarea>
            </label>
        </div>
        <button type="submit">Save changes</button>
    </form>

    <form action="<?= $router->generatePath('comment-delete', ['id' => $comment->getId()]) ?>" method="post">
        <button type="submit">Delete this comment</button>
    </form>

    <br>
    <a href="<?= $router->generatePath('comment-index') ?>">Back to list</a>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';

