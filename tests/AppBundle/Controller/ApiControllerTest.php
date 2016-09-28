<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ApiControllerTest extends WebTestCase
{

    public function setUp(){
        $this->client = static::createClient();
    }

    public function testGetPost()
    {
        $this->client->request('GET', '/api/posts/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $post = json_decode($this->client->getResponse()->getContent());

        $this->assertObjectHasAttribute('id', $post);
        $this->assertObjectHasAttribute('title', $post);
        $this->assertObjectHasAttribute('body', $post);
    }

    public function testGetPosts()
    {
        $this->client->request('GET','api/posts');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $posts = json_decode($this->client->getResponse()->getContent());

        $this->assertInternalType('array', $posts);
        $this->assertGreaterThanOrEqual(1, count($posts));

        $this->assertObjectHasAttribute('id', $posts[0]);
        $this->assertObjectHasAttribute('title', $posts[0]);
        $this->assertObjectHasAttribute('body', $posts[0]);
    }

    public function testAddNewPost()
    {
        $post = array('title' => 'Title test', 'body' => 'body Test content');
        $this->client->request('POST', '/api/posts',  array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($post));
        $post = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('id', $post);
        $this->assertObjectHasAttribute('title', $post);
        $this->assertObjectHasAttribute('body', $post);
    }

    public function testDeletePost()
    {
        //First we add a post
        $post = $this->addPost();

        //Then we deleted
        $this->client->request('DELETE','/api/posts/' . $post->id);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $deletedPost = json_decode($this->client->getResponse()->getContent());

        $this->assertObjectNotHasAttribute('id', $deletedPost);
        $this->assertObjectHasAttribute('title', $deletedPost);
        $this->assertObjectHasAttribute('body', $deletedPost);
    }

    public function testUpdatePost()
    {
        //Frist we create a post
        $post = $this->addPost();

        //This post has title "Title test" let's change it to "Title for testing"
        $post->title = 'Title for testing';

        $this->client->request('PUT', '/api/posts/' . $post->id,  array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($post));
        $updatedPost = json_decode($this->client->getResponse()->getContent());

        //Test the response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('id', $updatedPost);
        $this->assertObjectHasAttribute('title', $updatedPost);
        $this->assertObjectHasAttribute('body', $updatedPost);

        //Test if data has been changed
        $this->assertEquals('Title for testing', $updatedPost->title);

    }

    // public function testUpdatePostWithWrongId()
    // {
    //     //Frist we create a post
    //     $post = $this->addPost();

    //     //This post has title "Title test" let's change it to "Title for testing"
    //     $post->title = 'Title for testing';

    //     $this->client->request('PUT', '/api/posts/' . 999,  array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($post));
    //     $response = json_decode($this->client->getResponse()->getContent());

    //     //Test the response
    //     $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

    //     //Test if data has NOT been changed
    //     $this->client->request('GET', '/api/posts/' . $post->id);
    //     $post = json_decode($this->client->getResponse()->getContent());
    //     $this->assertEquals('Title test', $post->title);

    // }

    private function addPost()
    {
        $post = array('title' => 'Title test', 'body' => 'body Test content');
        $this->client->request('POST', '/api/posts', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($post));
        return json_decode($this->client->getResponse()->getContent());
    }

}
