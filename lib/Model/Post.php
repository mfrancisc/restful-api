<?php
namespace lib\Model;

/**
 * Class Post
 * Post model implementation
 */
final class Post extends CrudModel
{
    /**
     * @var int
     */
    protected $postId;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $body;
    /**
     * @var datetime
     */
    protected $created;
    /**
     * @var datetime
     */
    protected $modified;

    /**
     * Post constructor.
     * @param null $postId
     */
    public function __construct(int $postId = null)
    {
        $this->postId = $postId;

        return parent::__construct();
    }

    /**
     * @return int
     */
    protected function getId(): int
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    protected function tableName(): string
    {
        return "posts";
    }

    /**
     * editable fields
     * @return array
     */
    protected function getFields(): array
    {
        return [
            'title',
            'body',
        ];
    }
}