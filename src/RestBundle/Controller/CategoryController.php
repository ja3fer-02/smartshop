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

use ModelBundle\Entity\Category;



class CategoryController extends FOSRestController
{
     

     /**
     * @Rest\View()
     * @Rest\Get("/categories")
     * @ApiDoc(
     * 		section="Category",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the categories",
     * 
     *
     * )
     */
    public function getCategoriesAction()
    {
       $categories = $this->getDoctrine()->getRepository('ModelBundle:Category')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('categories'=>$categories , 'status' => '200', 'success' => '1'); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/category/{id}")
     * @ApiDoc(
     *		section="Category",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when Category not found"
     *     },
     *     description="Find category by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the category"}
     *      }
     * )
     */
    public function getCategoryAction($id)
    {
        $category = $this->getDoctrine()->getRepository('ModelBundle:Category')->find($id);
        if (empty($category)) {
            return  ['message' => 'Category not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        
        return array('category'=>$category, 'message' => 'Category found', 'status'=> 200, 'success' => '1');
    }

     /**
     * @Rest\View()
     * @Rest\Post("/category")
     * @ApiDoc(
     *	section="Category",
     *   description="Add Category",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *    requirements={
     *      {"name"="category_name", "dataType"="string", "requirement"=true, "description"="category name"},
     *      {"name"="category_icon", "dataType"="string", "requirement"=true, "description"="category icon"},
     * })
     */
    public function postAddCategoryAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $category = new Category();

       $category->setCategoryName($request->get('category_name'));
       $category->setCategoryIcon($request->get('category_icon'));
       
        
        $manager->persist($category);
        $manager->flush();
       
        return ['category'=>$category, 'message' => 'category Created', 'status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/category/{id}")
     * @ApiDoc(
     *		section="Category",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when Category not found",
     *     },
     *     description="Delete Category",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the Category"}
     *      }
     * )
     */
    public function deleteCategoryAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $category = $manager->getRepository('ModelBundle:Category')
                    ->find($id);
        if (empty($category)) {
            return  ['message' => 'category not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        $manager->remove($category);
        $manager->flush();
        return ['message' => 'category Deleted','status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/category/{id}")
     * @ApiDoc(
     *		section="Category",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when Category not found",
     *     },
     *     description="update Category",
	*    requirements={
     *      {"name"="category_name", "dataType"="string", "requirement"=true, "description"="category name"},
     *      {"name"="category_icon", "dataType"="string", "requirement"=true, "description"="category icon"},
            {"name"="id", "dataType"="string", "requirement"=true, "description"="category id"},
     * }
     )
     */
    public function putCategoryAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $category = $manager->getRepository('ModelBundle:Category')
                    ->find($id);
        if (empty($category)) {
            return  ['message' => 'category not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

        $category->setCategoryName($request->get('category_name',$category->getCategoryName()));
        $category->setCategoryIcon($request->get('category_icon',$category->getCategoryIcon()));
       
        
        $manager->persist($category);
        $manager->flush();

        return ['category' => $category ,'message' => 'Category Edited', 'status'=>200 , 'success'=> 1];
    }
   
    
}