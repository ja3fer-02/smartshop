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

use ModelBundle\Entity\Post;
use ModelBundle\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;



class PostController extends FOSRestController
{
     
     
     /**
     * @Rest\View()
     * @Rest\Get("/api/posts")
     * @ApiDoc(
     * 		section="Post",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Posts",
     * 
     *
     * )
     */
    public function getPostsAction()
    {
       $posts = $this->getDoctrine()->getRepository('ModelBundle:Post')->findBy(array(),array("id"=>"DESC"));  
       return $data= array('posts'=>$posts , 'status' => 200, 'success' => 1); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/api/post/{id}")
     * @ApiDoc(
     *		section="Post",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when post not found"
     *     },
     *     description="Find post by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the post"}
     *      }
     * )
     */
    public function getPostAction($id)
    {
        $post = $this->getDoctrine()->getRepository('ModelBundle:Post')->find($id);
        if (empty($post)) {
            return  ['message' => 'Post not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        
        return array('post'=>$post, 'message' => 'Post found', 'status'=> 200, 'success' => 1);
    }

     /**
     * @Rest\View(serializerGroups={"othersPostGroup"})
     * @Rest\Post("/api/post/{customer_id}")
     * @ApiDoc(
     *	section="Post",
     *   description="Add customer post",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *	  requirements={
	 *      {"name"="post_text", "dataType"="string", "requirement"=true, "description"="post text"},
	 		{"name"="post_image", "dataType"="string", "requirement"=true, "description"="post image"},
            {"name"="longitude", "dataType"="float", "required"=false, "description"="longitude"},
     *      {"name"="latitude", "dataType"="float", "required"=false, "description"="latitude"}
     *      }
     * )
     */
    public function postAddCustomerPostAction(Request $request,$customer_id)
    {
        $manager  = $this->getDoctrine()->getManager();  
        $customer = $manager->getRepository('ModelBundle:Customer')->find($customer_id);
        $post     = $manager->getRepository('ModelBundle:Post'); 
        $path     =$request->getSchemeAndHttpHost().$request->getBasePath();

        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        $data = [
         'customer'   => $customer,
         'post_text'  => $request->get('post_text'),
         'post_image' => $request->get('post_image'),
         'longitude'  => $request->get('longitude'),
         'latitude'   => $request->get('latitude'),
         'path'       => $path
        ];
        $newPost = $post->addPost($data);
        
        return ['post'=>$newPost, 'message' => 'post Created', 'status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/api/post/{id}")
     * @ApiDoc(
     *		section="Post",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when post not found",
     *     },
     *     description="Delete post",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the post"}
     *      }
     * )
     */
    public function deletePostAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $post = $manager->getRepository('ModelBundle:Post')
                    ->find($id);
        if (empty($post)) {
            return  ['message' => 'Post not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        $manager->remove($post);
        $manager->flush();
        return ['message' => 'Post Deleted','status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/api/post/{id}")
     * @ApiDoc(
     *		section="Post",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when post not found",
     *     },
     *     description="update post",
	 *
     *	   requirements={
	 *      {"name"="post_text", "dataType"="string", "requirement"=false, "description"="post text"},
	 		{"name"="post_image", "dataType"="string", "requirement"=false, "description"="post image"}
     *      }
     * )
     */
    public function putPostAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $post = $manager->getRepository('ModelBundle:Post')->find($id);
        if (empty($post)) {
            return  ['message' => 'Post not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
        $post->setPostText($request->get('post_text',$post->getPostText()));
        $post->setPostImage($request->get('post_image',$post->getPostImage()));
                
        $manager->persist($post);
        $manager->flush();
        return ['post' => $post ,'message' => 'Post Edited', 'status'=>200 , 'success'=> 1];
    }
   
   /**
     * @Rest\View(serializerGroups={"othersPostGroup"})
     * @Rest\Get("/api/posts/{customer_id}")
     * @ApiDoc(
     *      section="Post",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the customer's Posts ",
     * 
     *
     * )
     */
    public function getPostsByCustomerIdAction($customer_id)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $customer = $manager->getRepository('ModelBundle:Customer')
                    ->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

       $posts = $customer->getCustomerPostsShared(); 
       
       $posts = $posts->toArray();
        usort($posts,function ($a, $b) {
            return ($a->getUpdatedAt() < $b->getUpdatedAt());
        });
       return $data= array('posts'=>$posts , 'status' => 200, 'success' => 1); 
    }

    /**
     * @Rest\View(serializerGroups={"othersPostGroup"})
     * @Rest\Get("/api/posts/others/{customer_id}")
     * @ApiDoc(
     *      section="Post",
     *       statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all others Posts ",
     * requirements={
     *      {"name"="longitude", "dataType"="integer", "requirement"=true, "description"="current longitude"},
     *          {"name"="latitude", "dataType"="integer", "requirement"=true, "description"="current latitude"},
     *          {"name"="distance", "dataType"="float", "requirement"=true, "description"="required distance"}
     *      }
     *
     * )
     */
    public function getOthersPostsAction($customer_id,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository     = $this->getDoctrine()->getRepository('ModelBundle:Post');

        $customer = $manager->getRepository('ModelBundle:Customer')
                    ->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

      // $allPosts = $this->getDoctrine()->getRepository('ModelBundle:Post')->findBy(array(),array("createdAt"=>"ASC"));  
        $data = [
        'current_customer_id' => $customer_id,
        'longitude' => $request->get('longitude'),
        'latitude'  => $request->get('latitude'),
        'distance'  => $request->get('distance')
        ];

       $otherNearbyPosts = $repository->getNearPosts($data);
 
       $formattedOtherNearbyPosts=array();
        foreach ($otherNearbyPosts as $otherNearbyPost) {
               array_push($formattedOtherNearbyPosts, $otherNearbyPost[0]);
          }

       return $data= array('posts'=>$formattedOtherNearbyPosts , 'status' => 200, 'success' => 1); 
    
}
}