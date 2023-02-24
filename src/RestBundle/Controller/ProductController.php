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

use ModelBundle\Entity\Product;



class ProductController extends FOSRestController
{
     
     
     /**
     * @Rest\View()
     * @Rest\Get("/products")
     * @ApiDoc(
     * 		section="Product",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Products",
     * 
     *
     * )
     */
    public function getProductsAction()
    {
       $products = $this->getDoctrine()->getRepository('ModelBundle:Product')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('products'=>$products , 'status' => 200, 'success' => 1); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/api/product/barcode/{barcode}")
     * @ApiDoc(
     *      section="Product",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get the Product by barcode",
     * 
     *
     * )
     */
    public function getProductByBarcodeAction($barcode)
    {
       $product = $this->getDoctrine()->getRepository('ModelBundle:Product')->findOneBy(array('barcode' => $barcode));  
       if($product)
        return array('product'=>$product, 'status' => 200, 'success' => 1); 
        else
         return array( 'status' => 200, 'success' => 0); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/product/{id}")
     * @ApiDoc(
     *		section="Product",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when product not found"
     *     },
     *     description="Find product by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the product"}
     *      }
     * )
     */
    public function getProductAction($id)
    {
        $product = $this->getDoctrine()->getRepository('ModelBundle:Product')->find($id);
        if (empty($product)) {
            return  ['message' => 'Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        
        return array('product'=>$product, 'message' => 'Product found', 'status'=> 200, 'success' => 1);
    }

     /**
     * @Rest\View()
     * @Rest\Post("/product")
     * @ApiDoc(
     *	section="Product",
     *   description="Add product",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *	  requirements={
	 *      {"name"="product_description", "dataType"="string", "requirement"=true, "description"="product description"},

	 		{"name"="product_image", "dataType"="string", "requirement"=true, "description"="product image"},
            {"name"="product_name", "dataType"="string", "requirement"=true, "description"="product name"},

	 		{"name"="product_initial_price", "dataType"="float", "requirement"=true, "description"="product tinitial price"},
	 		{"name"="product_available", "dataType"="boolean", "requirement"=true, "description"="product available"}
     *      ,
            {"name"="barcode", "dataType"="string", "requirement"=true, "description"="barcode"}}
     * )
     */
    public function postAddProductAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $product = new Product();
        $category_id=$request->get('category_id','');
        if(!empty($category_id)){
          $category = $manager->getRepository('ModelBundle:Category')->find($category_id);
            if($category){
                $product->setProductCategory($category);
            }  
        }
        
        $product->setProductDescription($request->get('product_description'));
        $product->setProductImage($request->get('product_image'));
        $product->setProductName($request->get('product_name'));
        $product->setProductInitialPrice($request->get('product_initial_price'));
        $product->setProductAvailable($request->get('product_available'));
        $product->setBarcode($request->get('barcode'));
        
        $manager->persist($product);
        $manager->flush();
        
       
        return ['product'=>$product, 'message' => 'product Created', 'status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/product/{id}")
     * @ApiDoc(
     *		section="Product",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when product not found",
     *     },
     *     description="Delete product",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the product"}
     *      }
     * )
     */
    public function deleteProductAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository('ModelBundle:Product')
                    ->find($id);
        if (empty($product)) {
            return  ['message' => 'Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        $manager->remove($product);
        $manager->flush();
        return ['message' => 'Product Deleted','status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/product/{id}")
     * @ApiDoc(
     *		section="Product",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when product not found",
     *     },
     *     description="update product",
	 *
     *	   requirements={
	 *      	     {"name"="product_description", "dataType"="string", "requirement"=false, "description"="product description"},

	 		{"name"="product_image", "dataType"="string", "requirement"=false, "description"="product image"},

	 		{"name"="product_initial_price", "dataType"="float", "requirement"=false, "description"="product tinitial price"},

	 		{"name"="product_available", "dataType"="boolean", "requirement"=false, "description"="product available"}
     *      ,
     {"name"="barcode", "dataType"="string", "requirement"=false, "description"="barcode"}}
     * )
     */
    public function putProductAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository('ModelBundle:Product')->find($id);
        if (empty($product)) {
            return  ['message' => 'Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
        //var_dump($product->getProductCategory());
        $category_id=$request->get('category_id','');
        if(!empty($category_id)){
          $category = $manager->getRepository('ModelBundle:Category')->find($category_id);
            if($category){
                $product->setProductCategory($category);
            }  
        }
        
        $product->setProductDescription($request->get('product_description',$product->getProductDescription()));
        $product->setProductName($request->get('product_name',$product->getProductName()));
        $product->setProductImage($request->get('product_image',$product->getProductImage()));
        $product->setProductInitialPrice($request->get('product_initial_price',$product->getProductInitialPrice()));
        $product->setProductAvailable($request->get('product_available',$product->getProductAvailable()));
        $product->setBarcode($request->get('barcode',$product->getBarcode()));
        
        $manager->persist($product);
        $manager->flush();
        return ['product' => $product ,'message' => 'Product Edited', 'status'=>200 , 'success'=> 1];
    }
   
    /**
     * @Rest\View()
     * @Rest\Put("/api/product/favoris/{product_id}/{user_id}")
     * @ApiDoc(
     *      section="Product",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when product not found",
     *     },
     *     description="add product to user favoris",
     *
     *     requirements={
     *               {"name"="product_id", "dataType"="int", "requirement"=true, "description"="product id"},

            {"name"="user_id", "dataType"="int", "requirement"=true, "description"="user id"}
}
     * )
     */
    public function putProductFavorisAction($product_id,$user_id)
    {   $message='';
    $success=0;
        $manager = $this->getDoctrine()->getManager();

        $product = $manager->getRepository('ModelBundle:Product')->find($product_id);
        if (empty($product)) {
            return  ['message' => 'Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
         $customer = $manager->getRepository('ModelBundle:Customer')->find($user_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }

    if(!$customer->getCustomerProductsFavored()->contains($product)){
        $customer->addCustomerProductsFavored($product);
        $message= 'Added product to favoris';
        $success=1;
    }else{
        $customer->getCustomerProductsFavored()->removeElement($product);
                $message= 'Removed product from favoris';
                $success=2;

    }
        
        $manager->persist($customer);
        $manager->flush();
        return ['message' => $message, 'status'=>200 , 'success'=> $success];
    }

    /**
     * @Rest\View()
     * @Rest\Get("/product/favoris/{customer_id}")
     * @ApiDoc(
     *      section="Product",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Products with marked as favorite or not",
     * requirements={
     *          {"name"="customer_id", "dataType"="int", "requirement"=true, "description"="the id of the customer"}
     *      }
     *
     * )
     */
    public function getProductsWithUserFavoriteAction($customer_id)
    {
       $products = $this->getDoctrine()->getRepository('ModelBundle:Product')->findBy(array(),array("id"=>"ASC"));

        $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

         $productArray = array();

       foreach ($products as $product) {
            if($customer->getCustomerProductsFavored()->contains($product)){
                array_push($productArray, ['product' => $product, 'isFavorite'=>true]);
               // $productArray->add([);
            }else{
                array_push($productArray, ['product' => $product, 'isFavorite'=>false]);
                //$productArray->add(['product' => $product, 'isFavorite'=>false]);
            }
       }  

       return $data= array('products'=>$productArray , 'status' => 200, 'success' => 1); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/products/available")
     * @ApiDoc(
     *      section="Product",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get the Product by barcode",
     *  requirements={
     *          {"name"="available", "dataType"="boolean", "requirement"=true, "description"="1 for list products available/ 0 for list products not available"}
     *      }
     *
     * )
     */
    public function getProductByAvailabilityAction(Request $request)
    {
       $products = $this->getDoctrine()->getRepository('ModelBundle:Product')->findBy(array('productAvailable' => $request->get('available')));  
       if($products)
        return array('products'=>$products, 'status' => 200, 'success' => 1); 
        else
         return array('status' => 200, 'success' => 0); 
    }
}