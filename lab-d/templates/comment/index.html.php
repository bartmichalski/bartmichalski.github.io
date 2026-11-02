<?php
/** @var \App\Model\Comment $comments */
/** @var \App\Service\Router $router */

$title = 'Comments List';
$bodyClass = 'index';

ob_start(); ?>

    <h1>Comments List</h1>
    <a href="<?= $router->generatePath('comment-create') ?>">Create new</a>

    <ul>
        <?php foreach ($comments as $comment): ?>
            <li>
                <strong><?= htmlspecialchars($comment->getAuthor()) ?>:</strong> <?= htmlspecialchars($comment->getContent()) ?>
                <a href="<?= $router->generatePath('comment-show', ['id' => $comment->getId()]) ?>">Details</a>
                <a href="<?= $router->generatePath('comment-edit', ['id' => $comment->getId()]) ?>">Edit</a>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';