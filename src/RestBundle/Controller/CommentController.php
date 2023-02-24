<?php

namespace RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\JsonResponse;

use ModelBundle\Entity\Comment;
use ModelBundle\Entity\Customer;
use ModelBundle\Entity\Post;


class CommentController extends FOSRestController
{
     

     /**
     * @Rest\View()
     * @Rest\Get("/comments")
     * @ApiDoc(
     * 		section="Comment",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Comments",
     * 
     *
     * )
     */
    public function getCommentsAction()
    {
       $comments = $this->getDoctrine()->getRepository('ModelBundle:Comment')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('comments'=>$comments , 'status' => '200', 'success' => '1'); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/comment/{id}")
     * @ApiDoc(
     *		section="Comment",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when comment not found"
     *     },
     *     description="Find comment by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the comment"}
     *      }
     * )
     */
    public function getCommentAction($id)
    {
        $comment = $this->getDoctrine()->getRepository('ModelBundle:Comment')->find($id);
        if (empty($comment)) {
            return  ['message' => 'Comment not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        
        return array('comment'=>$comment, 'message' => 'Comment found', 'status'=> 200, 'success' => '1');
    }

     /**
     * @Rest\View(serializerGroups={"commentGroup"})
     * @Rest\Post("/api/comment")
     * @ApiDoc(
     *	section="Comment",
     *   description="Add comment",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *    requirements={
     *      {"name"="comment_text", "dataType"="string", "requirement"=true, "description"="comment text"},
     *      {"name"="comment_post_id", "dataType"="int", "requirement"=true, "description"="post id of the comment"},
     *      {"name"="comment_customer_id", "dataType"="int", "requirement"=true, "description"="customer id"}
     * })
     */
    public function postAddCommentAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $comment = new Comment();

       
        $customer = $manager->getRepository('ModelBundle:Customer')
                    ->find($request->get('comment_customer_id'));
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

        $post=new Post();
        $post = $manager->getRepository('ModelBundle:Post')
                    ->find($request->get('comment_post_id'));
        if (empty($post)) {
            return  ['message' => 'Post not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

        $comment->setCommentText($request->get('comment_text'));
        $comment->setCommentPost($post);
        $comment->setCommentCustomer($customer);      
        
        $manager->persist($comment);
        $manager->flush();
       
        return ['comment'=>$comment, 'message' => 'comment Created', 'status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/api/comment/{id}")
     * @ApiDoc(
     *		section="Comment",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when comment not found",
     *     },
     *     description="Delete comment",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the comment"}
     *      }
     * )
     */
    public function deleteCommentAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $comment = $manager->getRepository('ModelBundle:Comment')
                    ->find($id);
        if (empty($comment)) {
            return  ['message' => 'comment not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        $manager->remove($comment);
        $manager->flush();
        return ['message' => 'comment Deleted','status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/comment/{id}")
     * @ApiDoc(
     *		section="Comment",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when comment not found",
     *     },
     *     description="update comment",
	 *requirements={
     *      {"name"="comment_text", "dataType"="string", "requirement"=false, "description"="comment text"},
     *      {"name"="comment_post_id", "dataType"="int", "requirement"=false, "description"="post id of the comment"},
     *      {"name"="comment_customer_id", "dataType"="int", "requirement"=false, "description"="customer id"}
     *   }
     * )
     */
    public function putCommentAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $comment = $manager->getRepository('ModelBundle:Comment')->find($id);
        if (empty($comment)) {
            return  ['message' => 'Comment not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }

       
        $customer = $manager->getRepository('ModelBundle:Customer')
                    ->find($request->get('comment_customer_id'));
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

        $post=new Post();
        $post = $manager->getRepository('ModelBundle:Post')
                    ->find($request->get('comment_post_id'));
        if (empty($post)) {
            return  ['message' => 'Post not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

        $comment->setCommentText($request->get('comment_text',$comment->getCommentText()));
        $comment->setCommentPost($post);
        $comment->setCommentCustomer($customer); 

        $manager->persist($comment);
        $manager->flush();
        return ['comment' => $comment ,'message' => 'Comment Edited', 'status'=>200 , 'success'=> 1];
    }
   

     /**
     * @Rest\View(serializerGroups={"commentGroup"})
     * @Rest\Get("/api/comment/post/{post_id}")
     * @ApiDoc(
     *      section="Comment",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when comment not found"
     *     },
     *     description="Find comment by post id",
     *
     *     requirements={
     *          {"name"="post_id", "dataType"="int", "requirement"=true, "description"="the id of the post"}
     *      }
     * )
     */
    public function getCommentByPostAction($post_id)
    {
        $post = $this->getDoctrine()->getRepository('ModelBundle:Post')->find($post_id);
        if (empty($post)) {
            return  ['message' => 'post not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

       $comments = $this->getDoctrine()->getRepository('ModelBundle:Comment')->findBy(array('commentPost' => $post),array("id"=>"DESC"));

       return $data= array('comments'=>$comments , 'status' => '200', 'success' => '1'); 
    }
    
}