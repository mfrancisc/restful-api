<?php
namespace lib;

use lib\Api;
use lib\Model\Post;

/**
 * Class posts
 * posts Api implementation
 * save, update, read and delete posts
 */
final class posts extends Api
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
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->postId = $this->args[4] ?? null;// null coalescing 
    }

    /**
     * this method must match with the
     * route endpoint es: api/v1/posts
     * @return string|array
     */
    protected function posts() 
    {
        return $this->handleRequest();
    }

    /**
     * @return bool|string
     */
    protected function update()
    {
        $post = $this->readInput();
        if ( ! $this->postId || !is_numeric($this->postId)) return $this->response("Invalid id!");

        $exec = ($this->_buildPost($this->postId))->update($post);

        return $this->_result($exec);
    }

    /**
     * @return array|mixed|string
     */
    protected function read()
    {
        if ( ! $this->postId)
            return $this->_buildPost()->fetchAll();

        return $this->_buildPost($this->postId)->fetch();
    }

    /**
     * @return bool|string
     */
    protected function create()
    {

        $post = $this->readInput();

        $exec = ($this->_buildPost())->save($post);

        return $this->_result($exec);
    }

    /**
     * @return bool|string
     */
    protected function delete()
    {
        if ( ! $this->postId || !is_numeric($this->postId)) return $this->response("Invalid id!");

        $exec = ($this->_buildPost($this->postId))->delete();

        return $this->_result($exec);
    }

    /**
     * Return result description
     * @param  bool   $exec 
     * @return string       
     */
    private function _result(bool $exec): string
    {
        return ($exec) ? parent::RES_SUCESS : parent::RES_FAILURE;
    }

    /**
     * Post model factory method
     * @param  int|null $postId 
     * @return Post           
     */
    private function _buildPost(int $postId = null): Post
    {
        return ($postId) ? new Post($postId) : new Post();
    }

}

