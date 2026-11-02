<?php
namespace App\Model;

use PDO;
use App\Service\Config;

class Comment
{
    private ?int $id = null;
    private ?string $author = null;
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public static function fromArray(array $array): self
    {
        $comment = new self();
        $comment->fill($array);
        return $comment;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'author' => $this->getAuthor(),
            'content' => $this->getContent(),
        ];
    }

    public function fill(array $array): self
    {
        if (isset($array['id']) && !$this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['author'])) {
            $this->setAuthor($array['author']);
        }
        if (isset($array['content'])) {
            $this->setContent($array['content']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new PDO(\App\Service\Config::get('db_dsn') ?? 'sqlite:' . __DIR__ . '/../../data.db');
        $stmt = $pdo->query('SELECT * FROM comment');
        $comments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = self::fromArray($row);
        }
        return $comments;
    }

    public static function find(int $id): ?self
    {
        $pdo = new PDO(\App\Service\Config::get('db_dsn') ?? 'sqlite:' . __DIR__ . '/../../data.db');
        $stmt = $pdo->prepare('SELECT * FROM comment WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return self::fromArray($row);
    }

    public function save(): void
    {
        $pdo = new PDO(\App\Service\Config::get('db_dsn') ?? 'sqlite:' . __DIR__ . '/../../data.db');
        if (!$this->getId()) {
            $stmt = $pdo->prepare('INSERT INTO comment (author, content) VALUES (:author, :content)');
            $stmt->execute([
                'author' => $this->getAuthor(),
                'content' => $this->getContent()
            ]);
            $this->setId($pdo->lastInsertId());
        } else {
            $stmt = $pdo->prepare('UPDATE comment SET author = :author, content = :content WHERE id = :id');
            $stmt->execute([
                'author' => $this->getAuthor(),
                'content' => $this->getContent(),
                'id' => $this->getId()
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new PDO(\App\Service\Config::get('db_dsn') ?? 'sqlite:' . __DIR__ . '/../../data.db');
        $stmt = $pdo->prepare('DELETE FROM comment WHERE id = :id');
        $stmt->execute(['id' => $this->getId()]);
        $this->setId(null);
    }
}

