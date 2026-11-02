<?php
/** @var \App\Model\Comment $comment */
/** @var \App\Service\Router $router */

$title = 'Comment Details';
$bodyClass = 'show';

ob_start(); ?>

    <h1><?= htmlspecialchars($comment->getAuthor()) ?></h1>
    <p><?= htmlspecialchars($comment->getContent()) ?></p>

    <a href="<?= $router->generatePath('comment-edit', ['id' => $comment->getId()]) ?>">Edit</a>
    <a href="<?= $router->generatePath('comment-index') ?>">Back to list</a>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
