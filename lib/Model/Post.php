<?php

require_once 'CrudModel.php';

/**
 * Class Post
 * Post model implementation
 */
class Post extends CrudModel
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
    public function __construct($postId = null)
    {
        $this->postId = $postId;

        return parent::__construct();
    }

    /**
     * @return int
     */
    protected function getId()
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    protected function tableName()
    {
        return "posts";
    }

    /**
     * editable fields
     * @return array
     */
    protected function getFields()
    {
        return [
            'title',
            'body',
        ];
    }
}