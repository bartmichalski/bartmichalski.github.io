<?php
namespace App\Controller;

use App\Model\Comment;
use App\Service\Router;
use App\Service\Templating;

class CommentController
{
    public function indexAction(): ?string
    {
        $comments = Comment::findAll();
        $html = (new Templating())->render('comment/index.html.php', [
            'comments' => $comments,
            'router' => new Router()
        ]);
        return $html;
    }

    public function createAction(?array $requestPost): ?string
    {
        if ($requestPost) {
            $comment = Comment::fromArray($requestPost);
            $comment->save();

            $path = (new Router())->generatePath('comment-index');
            header('Location: ' . $path);
            return null;
        }

        $html = (new Templating())->render('comment/create.html.php', [
            'comment' => new Comment(),
            'router' => new Router()
        ]);
        return $html;
    }

    public function showAction(int $commentId): ?string
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            $path = (new Router())->generatePath('comment-index');
            header('Location: ' . $path);
            return null;
        }

        $html = (new Templating())->render('comment/show.html.php', [
            'comment' => $comment,
            'router' => new Router()
        ]);
        return $html;
    }

    public function editAction(int $commentId, ?array $requestPost): ?string
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            $path = (new Router())->generatePath('comment-index');
            header('Location: ' . $path);
            return null;
        }

        if ($requestPost) {
            $comment->fill($requestPost);
            $comment->save();

            $path = (new Router())->generatePath('comment-index');
            header('Location: ' . $path);
            return null;
        }

        $html = (new Templating())->render('comment/edit.html.php', [
            'comment' => $comment,
            'router' => new Router()
        ]);
        return $html;
    }

    public function deleteAction(int $commentId): ?string
    {
        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->delete();
        }

        $path = (new Router())->generatePath('comment-index');
        header('Location: ' . $path);
        return null;
    }
}

