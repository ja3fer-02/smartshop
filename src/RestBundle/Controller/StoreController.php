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
use ModelBundle\Entity\Location;

class StoreController extends FOSRestController
{
     

     /**
     * @Rest\View(serializerGroups={"shortDataStoreGroup"})
     * @Rest\Get("/stores")
     * @ApiDoc(
     * 		section="Store",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Stores",
     * 
     *
     * )
     */
    public function getStoresAction()
    {
       $stores = $this->getDoctrine()->getRepository('ModelBundle:Store')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('stores'=>$stores , 'status' => 200, 'success' => 1); 
    }


    /**
     * @Rest\View(serializerGroups={"shortDataStoreGroup"})
     * @Rest\Get("/storechain/stores/{store_chain_id}")
     * @ApiDoc(
     *      section="Store",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get the Stores of a StoreChain",
     *     
     requirements={
     *          {"name"="store_chain_id", "dataType"="integer", "requirement"=true, "description"="the id of the store chain"}
     *      } 
     *
     * )
     */
    public function getStoreChainStoresAction($store_chain_id)
    {
         $storeChain = $this->getDoctrine()->getRepository('ModelBundle:StoreChain')->find($store_chain_id);
        if (empty($storeChain)) {
            return  ['message' => 'Store chain not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

       $stores = $this->getDoctrine()->getRepository('ModelBundle:Store')->findBy(array('storeChain' => $storeChain));

        return $data= array('stores'=>$stores , 'status' => 200, 'success' => 1); 
    }
     /**
     * @Rest\View(serializerGroups={"shortDataStoreGroup"})
     * @Rest\Get("/stores/{id}")
     * @ApiDoc(
     *		section="Store",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when product not found"
     *     },
     *     description="Find store by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="integer", "requirement"=true, "description"="the id of the store"}
     *      }
     * )
     */
    public function getStoreAction($id)
    {
        $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($id);
        if (empty($store)) {
            return  ['message' => 'Store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        
        return array('store'=>$store, 'message' => 'Store found', 'status'=> 200, 'success' => 1);
    }

     /**
     * @Rest\View(serializerGroups={"shortDataStoreGroup"})
     * @Rest\Post("/stores")
     * @ApiDoc(
     *	section="Store",
     *   description="Add Store",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *	  requirements={
	 *      {"name"="store_name", "dataType"="string", "requirement"=true, "description"="store name"},

	 		{"name"="store_description", "dataType"="string", "requirement"=true, "description"="store description"},

	 		{"name"="store_logo", "dataType"="string", "requirement"=true, "description"="store logo"},

	 		{"name"="store_website", "dataType"="string", "requirement"=true, "description"="store website"},
           
            {"name"="store_facebook", "dataType"="string", "requirement"=true, "description"="store facebook page link"},

            {"name"="store_opening_time", "dataType"="datetime", "requirement"=true, "description"="store opening time"},

            {"name"="store_closing_time", "dataType"="datetime", "requirement"=true, "description"="store closing time"},

            {"name"="store_location", "dataType"="Location", "requirement"=true, "description"="store location (longitude , latitude)"},

            {"name"="store_chain_id", "dataType"="integer", "requirement"=true, "description"="store chain id"},


            {"name"="longitude", "dataType"="float", "requirement"=true, "description"="long"},

            {"name"="latitude", "dataType"="float", "requirement"=true, "description"="lat"},
     *      }
     * )
     */
    public function postAddStoreAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $idStoreChaine = $request->get('store_chain_id');
        $storeChain = $this->getDoctrine()->getRepository('ModelBundle:StoreChain')->find($idStoreChaine);
        if (empty($storeChain)) {
            return  ['message' => 'Store Chain not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        
        $store = new Store();
        $store->setStoreChain($storeChain);
        $store->setStoreName($request->get('store_name'));
        $store->setStoreDescription($request->get('store_description'));
        $store->setStoreLogo($request->get('store_logo'));
        $store->setStoreWebsite($request->get('store_website'));
        $store->setStoreFacebookPageLink($request->get('store_facebook'));
        $store->setStoreOpeningTime(new \DateTime($request->get('store_opening_time')));
        $store->setStoreClosingTime(new \DateTime($request->get('store_closing_time')));

      //  $longitude = $request->get('store_location')->get('longitude');
     //   $latitude = $request->get('store_location')->get('latitude');
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');
        $location = new Location();
        $location->setLongitude($longitude);
        $location->setLatitude($latitude);        
        $store->setLocation($location);
      
        
        $manager->persist($store);
        $manager->flush();
        
       
        return ['store'=>$store, 'message' => 'store Created', 'status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataStoreGroup"})
     * @Rest\Delete("/stores/{id}")
     * @ApiDoc(
     *		section="Store",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store not found",
     *     },
     *     description="Delete store",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the store"}
     *      }
     * )
     */
    public function deleteStoreAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $store = $manager->getRepository('ModelBundle:Store')
                    ->find($id);
        if (empty($store)) {
            return  ['message' => 'Store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        $manager->remove($store);
        $manager->flush();
        return ['message' => 'Store Deleted','status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataStoreGroup"})
     * @Rest\Put("/stores/{id}")
     * @ApiDoc(
     *		section="Store",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when product not found",
     *     },
     *     description="update product",
	 *
     requirements={
            {"name"="id", "dataType"="integer", "requirement"=true, "description"="store id"},

     *      {"name"="store_name", "dataType"="string", "requirement"=true, "description"="store name"},

            {"name"="store_description", "dataType"="string", "requirement"=true, "description"="store description"},

            {"name"="store_logo", "dataType"="string", "requirement"=true, "description"="store logo"},

            {"name"="store_website", "dataType"="string", "requirement"=true, "description"="store website"},
           
            {"name"="store_facebook", "dataType"="string", "requirement"=true, "description"="store facebook page link"},

            {"name"="store_opening_time", "dataType"="datetime", "requirement"=true, "description"="store opening time"},

            {"name"="store_closing_time", "dataType"="datetime", "requirement"=true, "description"="store closing time"},

            {"name"="store_location", "dataType"="Location", "requirement"=true, "description"="store location (longiture , latitude)"},

     *      }
     * )
     */
    public function putStoreAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $store = $manager->getRepository('ModelBundle:Store')->find($id);
        if (empty($store)) {
            return  ['message' => 'Store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
        $store->setStoreName($request->get('store_name',$store->getStoreName()));
        $store->setStoreDescription($request->get('store_description',$store->getStoreDescription()));
        $store->setStoreLogo($request->get('store_logo',$store->getStoreLogo()));
        $store->setStoreWebsite($request->get('store_website',$store->getStoreWebsite()));
        $store->setStoreFacebookPageLink($request->get('store_facebook',$store->getStoreFacebookPageLink()));
        $store->setStoreOpeningTime(new \DateTime($request->get('store_opening_time')),$store->getStoreOpeningTime());
        $store->setStoreClosingTime(new \DateTime($request->get('store_closing_time')),$store->getStoreClosingTime());

        $location = $request->get('store_location',$store->getLocation());
        $store->setLocation($location);
      
        
        $manager->persist($store);
        $manager->flush();
        return ['store' => $store ,'message' => 'Store Edited', 'status'=>200 , 'success'=> 1];
    }

       /**
     * @Rest\View(serializerGroups={"shortDataStoreGroup","shortDataCustomerGroup","shortDataStoreProductsGroup","shortDataStoreProductPriceGroup"})
     * @Rest\Get("/api/stores/find/near")
     * @ApiDoc(
     *      section="Store",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get the Stores near a location (default 70km)",
     *     
     requirements={
     *          {"name"="longitude", "dataType"="integer", "requirement"=true, "description"="current longitude"},
     *          {"name"="latitude", "dataType"="integer", "requirement"=true, "description"="current latitude"},
     *          {"name"="distance", "dataType"="float", "requirement"=true, "description"="required distance"},
     *          {"name"="product_id", "dataType"="integer", "requirement"=true, "description"="required product_id"}
     *      } 
     *
     * )
     */
    public function getNearStoresAction(Request $request)
    {        
        $repository     = $this->getDoctrine()->getRepository('ModelBundle:Store');
        $repository_p   = $this->getDoctrine()->getRepository('ModelBundle:Product');
        $product = null;
        if($request->get('product_id') != null){
             $product        = $repository_p->find($request->get('product_id')); 
        }
      
        $data = [
        'longitude' => $request->get('longitude'),
        'latitude'  => $request->get('latitude'),
        'distance'  => $request->get('distance'),
        'product'   => $product,
        ];

        $stores = $repository->getNearStores($data);
        
            if($stores){
                return ['success'=>1,'message'=>' list stores near a location','stores'=>$stores,'status'=>200];
            }

            if(empty($stores)){
                return ['success'=> -1,'message'=> 'empty list','stores'=>$stores,'status'=>200];
            }

         return ['success'=>0,'message'=>'list not found','stores'=>null,'status'=>404];
     
    }

   /**
     * @Rest\View()
     * @Rest\Get("/store/advertisements/{store_id}")
     * @ApiDoc(
     *      section="Store",
     *         statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store   not found"
     *     },
     *     description="Find advertisement by store_id  ",
     *
     *    
     * )
     */
    public function getStoresAdvertisementAction($store_id)
    {
        $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($store_id);
        
       if (empty($store)) {
            return  ['message' => 'Store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        } 
        $repository = $this->getDoctrine()->getRepository('ModelBundle:Advertisement');
        $advertisements = $repository->findByAdvertisementStore($store);
         

         
       return $data= array('advertisements'=>$advertisements , 'status' => 200, 'success' => 1);
    }
}