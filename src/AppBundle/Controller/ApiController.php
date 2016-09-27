<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\NoRoute;
use FOS\RestBundle\Controller\Annotations\Post as PostMethod;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ApiController extends FOSRestController
{

    //Returns a post by id (the id is passed through the URL)
    public function getPostAction(Post $post)
    {
      // $view = $this->view($post, 200);
      // $view->setHeader('Cache-control','public, max-age=10');
      // return $view;
      return $post;
    }

    //Returns a list of all posts
    public function getPostsAction()
    {
      return $this->getDoctrine()->getRepository('AppBundle\Entity\Post')->findAll();
    }

    /**
     * @NoRoute
     * @PostMethod("api/posts", name="post_post", defaults={"_format":"json"})
     * @ParamConverter("post", converter="fos_rest.request_body")
     */
    public function postPostAction(Post $post)
    {
      $em = $this->getDoctrine()->getManager();

      $em->persist($post);
      $em->flush();

      return $post;
    }

    //Delete a post by id (the id is passed through the URL)
    public function deletePostAction(Post $post)
    {
      $em = $this->getDoctrine()->getManager();
      $em->remove($post);
      $em->flush();

      return array('message' => 'Post deleted');
    }


    /**
     * @NoRoute
     * @Put("api/posts/{id}", name="put_post", defaults={"_format":"json"})
     * @ParamConverter("post", converter="fos_rest.request_body")
     */
    public function putPostAction($id, Post $post)
    {
      if (!$this->getDoctrine()->getRepository('AppBundle\Entity\Post')->find($id)) {
        throw new NotFoundHttpException("Post not found");
      }

      $em = $this->getDoctrine()->getManager();
      $em->merge($post);
      $em->flush();

      return array('message' => 'Post updated');
    }

}
