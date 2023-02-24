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

use ModelBundle\Entity\Promotion;
use ModelBundle\Entity\FlashSale;



class FlashSaleController extends FOSRestController
{
     
     
     /**
     * @Rest\View()
     * @Rest\Get("/flashsales")
     * @ApiDoc(
     * 		section="Flashsale",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the FlashSales",
     * 
     *
     * )
     */
    public function getFlashSalesAction()
    {
       $flashsales = $this->getDoctrine()->getRepository('ModelBundle:FlashSale')->findBy(array(),array("id"=>"ASC")); 
       return $data= array('flashsales'=>$flashsales , 'status' => 200, 'success' => 1); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/flashsale/{id}")
     * @ApiDoc(
     *		section="Flashsale",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when flashsale not found"
     *     },
     *     description="Find flashsale by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the flashsale"}
     *      }
     * )
     */
    public function getFlashSaleAction($id)
    {
        $flashsale = $this->getDoctrine()->getRepository('ModelBundle:FlashSale')->find($id);
        if (empty($flashsale)) {
            return  ['message' => 'flashsale not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        return array('flashsale'=>$flashsale, 'message' => 'Flashsale found', 'status'=> 200, 'success' => 1);
    }

     /**
     * @Rest\View()
     * @Rest\Post("/flashsale")
     * @ApiDoc(
     *	section="Flashsale",
     *   description="Add flashsale",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *	  requirements={
	 *      {"name"="new_price", "dataType"="float", "requirement"=true, "description"="flashsale new price"},
	 		{"name"="promotion_image", "dataType"="string", "requirement"=true, "description"="flashsale image"},
            {"name"="promotion_is_available", "dataType"="boolean", "requirement"=true, "description"="flashsale is available"},

	 		{"name"="promotion_bonus_points", "dataType"="integer", "requirement"=true, "description"="flashsale bonus points"},

	 		{"name"="store_product_id", "dataType"="integer", "requirement"=true, "description"="store product id"},
            {"name"="flashsale_start_date", "dataType"="datetime", "requirement"=true, "description"="flashsale start date"},
            {"name"="flashsale_end_date", "dataType"="datetime", "requirement"=true, "description"="flashsale end date"}
     *      }
     * )
     */
    public function postAddFlashAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $storeRepository = $this->getDoctrine()->getRepository('ModelBundle:Store');

        $idStoreProduct = $request->get('store_product_id');
        $storeProduct = $this->getDoctrine()->getRepository('ModelBundle:StoreProduct')->find($idStoreProduct);
        if (empty($storeProduct)) {
            return  ['message' => 'Store Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $flashsale = new FlashSale();
        $flashsale->setNewPrice($request->get('new_price'));
        $flashsale->setPromotionImage($request->get('promotion_image'));
        $flashsale->setPromotionIsAvailable($request->get('promotion_is_available'));
        $flashsale->setPromotionBonusPointsValue($request->get('promotion_bonus_points'));
        $flashsale->setPromotionStoreProduct($storeProduct);

        $startDate=new \DateTime();
        $startDate->setTimestamp($request->get('flashsale_start_date'));
        $endDate=new \DateTime();
        $endDate->setTimestamp($request->get('flashsale_end_date'));

        $flashsale->setFlashSaleStartDate($startDate);
        $flashsale->setFlashSaleEndDate($endDate);

        
        $manager->persist($flashsale);
        $manager->flush();
        
        $sendNotificationFunctionResponse = $storeRepository->sendNotificationToUser($storeProduct->getStoreProductStore()->getId(),$storeProduct->getStoreProductStore()->getStoreName() ,"A flashsale is available for " . $storeProduct->getStoreProductProduct()->getProductName()." Hurry Up!","Description" );
        return ['flashsale'=>$flashsale, 'message' => 'flashsale Created', 'status'=> 200, 'success' => 1];
    }

/**
     * @Rest\View()
     * @Rest\Delete("/flashsale/{id}")
     * @ApiDoc(
     *      section="Flashsale",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when flashsale not found",
     *     },
     *     description="Delete flashsale",
     *
     *     requirements={
     *          {"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the flashsale"}
     *      }
     * )
     */
    public function deleteFlashSaleAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $flashsale = $manager->getRepository('ModelBundle:FlashSale')
                    ->find($id);
        if (empty($flashsale)) {
            return  ['message' => 'flashsale not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        $manager->remove($flashsale);
        $manager->flush();
        return ['message' => 'flashsale Deleted','status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/flashsale/{id}")
     * @ApiDoc(
     *      section="Flashsale",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when flashsale not found",
     *     },
     *     description="update flashsale",
     *
     *     requirements={
     *      {"name"="new_price", "dataType"="float", "requirement"=true, "description"="flashsale new price"},
            {"name"="promotion_image", "dataType"="string", "requirement"=true, "description"="flashsale image"},
            {"name"="promotion_is_available", "dataType"="boolean", "requirement"=true, "description"="flashsale is available"},
            {"name"="promotion_bonus_points", "dataType"="integer", "requirement"=true, "description"="flashsale bonus points"},
            {"name"="flashsale_start_date", "dataType"="datetime", "requirement"=true, "description"="flashsale start date"},
            {"name"="flashsale_end_date", "dataType"="datetime", "requirement"=true, "description"="flashsale end date"}  
     *      }
     * )
     */
    public function putFlashSaleAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $flashsale = $manager->getRepository('ModelBundle:FlashSale')->find($id);
        if (empty($flashsale)) {
            return  ['message' => 'flashsale not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
        $flashsale = new FlashSale();
        $flashsale->setNewPrice($request->get('new_price'));
        $flashsale->setPromotionImage($request->get('promotion_image'));
        $flashsale->setPromotionIsAvailable($request->get('promotion_is_available'));
        $flashsale->setPromotionBonusPointsValue($request->get('promotion_bonus_points'));
        $flashsale->setFlashSaleStartDate($request->get('flashsale_start_date'));
        $flashsale->setFlashSaleEndDate($request->get('flashsale_end_date'));
        
        $manager->persist($flashsale);
        $manager->flush();
        return ['flashsale' => $flashsale ,'message' => 'Discount Edited', 'status'=>200 , 'success'=> 1];
    }
   
    /**
     * @Rest\View()
     * @Rest\Put("/api/flashsale/favoris/{flashsale_id}/{user_id}")
     * @ApiDoc(
     *      section="Flashsale",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when flashsale not found",
     *     },
     *     description="add flashsale to user favoris",
     *
     *     requirements={
     *               {"name"="flashsale_id", "dataType"="int", "requirement"=true, "description"="flashsale id"},

            {"name"="user_id", "dataType"="int", "requirement"=true, "description"="user id"}
}
     * )
     */
    public function putFlashSaleFavorisAction($flashsale_id,$user_id)
    {   $message='';
        $success=0;
        $manager = $this->getDoctrine()->getManager();

        $flashsale = $manager->getRepository('ModelBundle:FlashSale')->find($flashsale_id);
        if (empty($flashsale)) {
            return  ['message' => 'Flashsale not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
         $customer = $manager->getRepository('ModelBundle:Customer')->find($user_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }

    if(!$customer->getCustomerPromotionsFavored()->contains($flashsale)){
        $customer->addCustomerPromotionsFavored($flashsale);
        $message= 'Added flashsale to favoris';
        $success=1;
    }else{
        $customer->getCustomerPromotionsFavored()->removeElement($flashsale);
                $message= 'Removed flashsale from favoris';
                $success=2;

    }
        
        $manager->persist($customer);
        $manager->flush();
        return ['message' => $message, 'status'=>200 , 'success'=> $success];
    }

    /**
     * @Rest\View()
     * @Rest\Get("/flashsale/favoris/{customer_id}")
     * @ApiDoc(
     *      section="Flashsale",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the flashsales with marked as favorite or not",
     * requirements={
     *          {"name"="customer_id", "dataType"="int", "requirement"=true, "description"="the id of the customer"}
     *      }
     *
     * )
     */
    public function getFlashSalesWithUserFavoriteAction($customer_id)
    {
       $flashsales = $this->getDoctrine()->getRepository('ModelBundle:FlashSale')->findBy(array(),array("id"=>"ASC"));

        $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

         $flashsaleArray = array();

       foreach ($flashsales as $flashsale) {
            if($customer->getCustomerPromotionsFavored()->contains($flashsale)){
                array_push($flashsaleArray, ['flashsale' => $flashsale, 'isFavorite'=>true]);
            }else{
                array_push($flashsaleArray, ['flashsale' => $flashsale, 'isFavorite'=>false]);
            }
       }  

       return $data= array('flashsales'=>$flashsaleArray , 'status' => 200, 'success' => 1); 
    }

}