<?php
require_once 'Api.php';
require_once 'Model' . DIRECTORY_SEPARATOR . 'Post.php';

/**
 * Class posts
 * posts Api implementation
 * save, update, read and delete posts
 */
class posts extends Api
{

    /**
     * @var int
     */
    private $postId;

    /**
     * posts constructor.
     * @param $request
     * @param $method
     */
    public function __construct($request, $method)
    {
        parent::__construct($request, $method);
        $this->postId = ($this->args[4]) ? $this->args[4] : null;
    }

    /**
     * this method must match with the
     * route es: api/v1/posts
     * @return string
     */
    protected function posts()
    {
        return $this->handleRequest();
    }

    /**
     * @return bool|string
     */
    protected function delete()
    {
        if ( ! $this->postId) return $this->response("Post id is required");

        return (new Post($this->postId))->delete();

    }

    /**
     * @return bool|string
     */
    protected function update()
    {
        $post = $this->readInput();
        if ( ! $this->postId) return $this->response("Post id is required");

        return (new Post($this->postId))->update($post);
    }

    /**
     * @return array|mixed|string
     */
    protected function read()
    {
        $post = new Post();
        if ( ! $this->postId)
            return $post->fetchAll();

        return $post->fetch($this->postId);
    }

    /**
     * @return bool|string
     */
    protected function create()
    {

        $post = $this->readInput();

        return (new Post())->save($post);

    }
}

