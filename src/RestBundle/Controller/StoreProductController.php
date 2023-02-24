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

use ModelBundle\Entity\Store;
use ModelBundle\Entity\StoreProduct;
use ModelBundle\Entity\Product;


class StoreProductController extends FOSRestController
{
     

     /**
     * @Rest\View()
     * @Rest\Get("/store/products")
     * @ApiDoc(
     * 		section="Store Product",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Store Products",
     * 
     *
     * )
     */
    public function getStoreProductsAction()
    {
       $storeProducts = $this->getDoctrine()->getRepository('ModelBundle:StoreProduct')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('store_products'=>$storeProducts , 'status' => '200', 'success' => '1'); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/store/products/{store_id}")
     * @ApiDoc(
     *      section="Store Product",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Store Products of a store",
     * requirements={
     *          {"name"="store_id", "dataType"="int", "requirement"=true, "description"="the id of the store"}
     *      }
     *
     * )
     */
    public function getStoreStoreProductsAction($store_id)
    {
       $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($store_id);
        if (empty($store)) {
            return  ['message' => 'store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

       $storeProducts = $this->getDoctrine()->getRepository('ModelBundle:StoreProduct')->findBy(array('storeProductStore' => $store));

       return $data= array('store_products'=>$storeProducts , 'status' => '200', 'success' => '1'); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/store/product/{id}")
     * @ApiDoc(
     *		section="Store Product",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when store product not found"
     *     },
     *     description="Find store product by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the store product"}
     *      }
     * )
     */
    public function getStoreProductAction($id)
    {
        $storeProduct = $this->getDoctrine()->getRepository('ModelBundle:StoreProduct')->find($id);
        if (empty($storeProduct)) {
            return  ['message' => 'Store Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        
        return array('store_product'=>$storeProduct, 'message' => 'Store Product found', 'status'=> 200, 'success' => '1');
    }

     /**
     * @Rest\View()
     * @Rest\Post("/store/product")
     * @ApiDoc(
     *	section="Store Product",
     *   description="Add Store Product",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *	  requirements={
	 *      {"name"="store_product_price", "dataType"="float", "requirement"=true, "description"="store product price"},
           
            {"name"="store_product_image", "dataType"="string", "requirement"=true, "description"="store product image"},
           
             {"name"="product_id", "dataType"="int", "requirement"=true, "description"="product id"},

              {"name"="store_id", "dataType"="int", "requirement"=true, "description"="store id"},

	 		
     *      }
     * )
     */
    public function postAddStoreProductAction(Request $request)
    {
        $storeRepository = $this->getDoctrine()->getRepository('ModelBundle:Store');

        
        $productID = $request->get('product_id');
        $product = $this->getDoctrine()->getRepository('ModelBundle:Product')->find($productID);
        if (empty($product)) {
            return  ['message' => 'product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

        $storeID = $request->get('store_id');
        $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($storeID);
        if (empty($store)) {
            return  ['message' => 'store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
       
        $storeProduct = new StoreProduct();
        $storeProduct->setStoreProductPrice($request->get('store_product_price'));
        $storeProduct->setStoreProductImage($request->get('store_product_image'));
        $storeProduct->setStoreProductProduct($product);
        $storeProduct->setStoreProductStore($store);
       
     
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($storeProduct);
        $manager->flush();
        
        $sendNotificationFunctionResponse = $storeRepository->NotifyUsersAboutProductNewPrice($storeProduct->getId(),$storeProduct->getStoreProductStore()->getStoreName() ,"The product ".$product->getProductName()." is available in a nearby store, ".$storeProduct->getStoreProductPrice(),"Description" );

       
        return ['store_product'=>$storeProduct, 'message' => 'store Product Created', 'status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/store/product/{id}")
     * @ApiDoc(
     *		section="Store Product",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store product not found",
     *     },
     *     description="Delete store product",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the store product"}
     *      }
     * )
     */
    public function deleteStoreProductAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $storeProduct = $manager->getRepository('ModelBundle:StoreProduct')
                    ->find($id);
        if (empty($storeProduct)) {
            return  ['message' => 'Stores Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        $manager->remove($storeProduct);
        $manager->flush();
        return ['message' => 'Store Product Deleted','status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/store/product/{id}")
     * @ApiDoc(
     *		section="Store Product",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store product not found",
     *     },
     *     description="update store product",
	 *
     *    requirements={
     *      {"name"="store_product_price", "dataType"="float", "requirement"=true, "description"="store product price"},
           
            {"name"="store_product_image", "dataType"="string", "requirement"=true, "description"="store product image"},

              {"name"="id", "dataType"="string", "requirement"=true, "description"="store product id"},
            
     *      }
     
     * )
     */
    public function putStoreProductAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $storeProduct = $manager->getRepository('ModelBundle:StoreProduct')->find($id);
        if (empty($storeProduct)) {
            return  ['message' => 'Store Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
        $storeProduct->setStoreProductPrice($request->get('store_product_price'));
        $storeProduct->setStoreProductImage($request->get('store_product_image'));
        
     
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($storeProduct);
        $manager->flush();
      
        return ['store_product' => $storeProduct ,'message' => 'Store Product Edited', 'status'=>200 , 'success'=> 1];
    }

    /**
     * @Rest\View(serializerGroups={"fullDataStoreProductGroup"})
     * @Rest\Get("/api/store/product/favoris/{store_id}/{customer_id}")
     * @ApiDoc(
     *      section="Store Product",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the store Products with marked as favorite or not",
     * requirements={
     *          {"name"="customer_id", "dataType"="int", "requirement"=true, "description"="the id of the customer"},
     {"name"="store_id", "dataType"="int", "requirement"=true, "description"="the id of the store"}
     *      }
     *
     * )
     */
    public function getStoreProductsWithUserFavoriteAction($customer_id,$store_id)
    {
        $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($store_id);
        if (empty($store)) {
            return  ['message' => 'store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }


        $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $storeProducts = $this->getDoctrine()->getRepository('ModelBundle:StoreProduct')->findBy(array('storeProductStore' => $store));


         $storeProductsArray = array();

       foreach ($storeProducts as $storeProduct) {
            if($customer->getCustomerProductsFavored()->contains($storeProduct->getStoreProductProduct())){
                array_push($storeProductsArray, ['store_product' => $storeProduct, 'isFavorite'=>true]);
               // $productArray->add([);
            }else{
                array_push($storeProductsArray, ['store_product' => $storeProduct, 'isFavorite'=>false]);
                //$productArray->add(['product' => $product, 'isFavorite'=>false]);
            }
       }  

       return $data= array('store_products'=>$storeProductsArray , 'status' => 200, 'success' => 1); 
    }
   

     /**
     * @Rest\View()
     * @Rest\Put("/api/store/product/favoris/{store_product_id}/{customer_id}")
     * @ApiDoc(
     *      section="Store Product",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when product not found",
     *     },
     *     description="add product by store products to user favoris",
     *
     *     requirements={
     *               {"name"="store_product_id", "dataType"="int", "requirement"=true, "description"="store product id"},

            {"name"="customer_id", "dataType"="int", "requirement"=true, "description"="user id"}
}
     * )
     */
    public function putProductFavorisAction($store_product_id,$customer_id)
    {   
        $message='';
        $success=0;
        $manager = $this->getDoctrine()->getManager();

        $storeProduct = $this->getDoctrine()->getRepository('ModelBundle:StoreProduct')->find($store_product_id);
        if (empty($storeProduct)) {
            return  ['message' => 'Store Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        
       
         $customer = $manager->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }

     
        $product = $storeProduct->getStoreProductProduct();

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
}