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
use ModelBundle\Entity\Discount;
use ModelBundle\Entity\User;




class DiscountController extends FOSRestController
{
     
     
     /**
     * @Rest\View(serializerGroups={"shortDataDiscountGroup"})
     * @Rest\Get("/discounts")
     * @ApiDoc(
     * 		section="Discount",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Discounts",
     * 
     *
     * )
     */
    public function getDiscountsAction()
    {
       $discounts = $this->getDoctrine()->getRepository('ModelBundle:Discount')->findBy(array(),array("id"=>"ASC")); 
       return $data= array('discounts'=>$discounts , 'status' => 200, 'success' => 1); 
    }

     /**
     * @Rest\View(serializerGroups={"shortDataDiscountGroup"})
     * @Rest\Get("/discount/{id}")
     * @ApiDoc(
     *		section="Discount",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when discount not found"
     *     },
     *     description="Find discount by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the discount"}
     *      }
     * )
     */
    public function getDiscountAction($id)
    {
        $discount = $this->getDoctrine()->getRepository('ModelBundle:Discount')->find($id);
        if (empty($discount)) {
            return  ['message' => 'Discount not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        return array('discount'=>$discount, 'message' => 'Discount found', 'status'=> 200, 'success' => 1);
    }

     /**
     * @Rest\View(serializerGroups={"shortDataDiscountGroup"})
     * @Rest\Post("/discount")
     * @ApiDoc(
     *	section="Discount",
     *   description="Add discount",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *	  requirements={
	 *      {"name"="new_price", "dataType"="float", "requirement"=true, "description"="Discount new price"},
	 		{"name"="promotion_image", "dataType"="string", "requirement"=true, "description"="Discount image"},
            {"name"="promotion_is_available", "dataType"="boolean", "requirement"=true, "description"="Discount is available"},

	 		{"name"="promotion_bonus_points", "dataType"="integer", "requirement"=true, "description"="Discount bonus points"},

	 		{"name"="store_product_id", "dataType"="integer", "requirement"=true, "description"="store product id"},
            {"name"="discount_percentage", "dataType"="integer", "requirement"=true, "description"="discount percentage"}
     *      }
     * )
     */
    public function postAddDiscountAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $storeRepository = $this->getDoctrine()->getRepository('ModelBundle:Store');

        $idStoreProduct = $request->get('store_product_id');
        $storeProduct = $this->getDoctrine()->getRepository('ModelBundle:StoreProduct')->find($idStoreProduct);
        if (empty($storeProduct)) {
            return  ['message' => 'Store Product not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $discount = new Discount();
        $discount->setNewPrice($request->get('new_price'));
        $discount->setPromotionImage($request->get('promotion_image'));
        $discount->setPromotionIsAvailable($request->get('promotion_is_available'));
        $discount->setPromotionBonusPointsValue($request->get('promotion_bonus_points'));
        $discount->setPromotionStoreProduct($storeProduct);
        $discount->setDiscountPercentage($request->get('discount_percentage'));
        
        $manager->persist($discount);
        $manager->flush();
        
        $sendNotificationFunctionResponse = $storeRepository->sendNotificationToUser($storeProduct->getStoreProductStore()->getId(),$storeProduct->getStoreProductStore()->getStoreName() ,$discount->getDiscountPercentage() . "% for " . $storeProduct->getStoreProductProduct()->getProductName(),"Description" );

        return ['discount'=>$discount, 'message' => 'discount Created and users will be notified', 'status'=> 200, 'success' => 1];
    }

/**
     * @Rest\View()
     * @Rest\Delete("/discount/{id}")
     * @ApiDoc(
     *      section="Discount",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when discount not found",
     *     },
     *     description="Delete discount",
     *
     *     requirements={
     *          {"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the discount"}
     *      }
     * )
     */
    public function deleteDiscountAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $discount = $manager->getRepository('ModelBundle:Discount')
                    ->find($id);
        if (empty($discount)) {
            return  ['message' => 'discount not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        $manager->remove($discount);
        $manager->flush();
        return ['message' => 'discount Deleted','status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataDiscountGroup"})
     * @Rest\Put("/discount/{id}")
     * @ApiDoc(
     *      section="Discount",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when discount not found",
     *     },
     *     description="update discount",
     *
     *     requirements={
     *      {"name"="new_price", "dataType"="float", "requirement"=true, "description"="discount new price"},
            {"name"="promotion_image", "dataType"="string", "requirement"=true, "description"="discount image"},
            {"name"="promotion_is_available", "dataType"="boolean", "requirement"=true, "description"="discount is available"},
            {"name"="promotion_bonus_points", "dataType"="integer", "requirement"=true, "description"="discount bonus points"},
            {"name"="discount_percentage", "dataType"="integer", "requirement"=true, "description"="discount percentage"}    
     *      }
     * )
     */
    public function putDiscountAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $discount = $manager->getRepository('ModelBundle:Discount')->find($id);
        if (empty($discount)) {
            return  ['message' => 'discount not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
        $discount = new Discount();
        $discount->setNewPrice($request->get('new_price'));
        $discount->setPromotionImage($request->get('promotion_image'));
        $discount->setPromotionIsAvailable($request->get('promotion_is_available'));
        $discount->setPromotionBonusPointsValue($request->get('promotion_bonus_points'));
        $discount->setDiscountPercentage($request->get('discount_percentage'));

        
        $manager->persist($discount);
        $manager->flush();
        return ['discount' => $discount ,'message' => 'Discount Edited', 'status'=>200 , 'success'=> 1];
    }
   
    /**
     * @Rest\View(serializerGroups={"shortDataDiscountGroup"})
     * @Rest\Put("/api/discount/favoris/{discount_id}/{user_id}")
     * @ApiDoc(
     *      section="Discount",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when discount not found",
     *     },
     *     description="add discount to user favoris",
     *
     *     requirements={
     *               {"name"="discount_id", "dataType"="int", "requirement"=true, "description"="discount id"},

            {"name"="user_id", "dataType"="int", "requirement"=true, "description"="user id"}
}
     * )
     */
    public function putDiscountFavorisAction($discount_id,$user_id)
    {   $message='';
        $success=0;
        $manager = $this->getDoctrine()->getManager();

        $discount = $manager->getRepository('ModelBundle:Discount')->find($discount_id);
        if (empty($discount)) {
            return  ['message' => 'Discount not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
         $customer = $manager->getRepository('ModelBundle:Customer')->find($user_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }

    if(!$customer->getCustomerPromotionsFavored()->contains($discount)){
        $customer->addCustomerPromotionsFavored($discount);
        $message= 'Added discount to favoris';
        $success=1;
    }else{
        $customer->getCustomerPromotionsFavored()->removeElement($discount);
                $message= 'Removed discount from favoris';
                $success=2;

    }
        
        $manager->persist($customer);
        $manager->flush();
        return ['message' => $message, 'status'=>200 , 'success'=> $success];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataDiscountGroup"})
     * @Rest\Get("/discount/favoris/{customer_id}")
     * @ApiDoc(
     *      section="Discount",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the discounts with marked as favorite or not",
     * requirements={
     *          {"name"="customer_id", "dataType"="int", "requirement"=true, "description"="the id of the customer"}
     *      }
     *
     * )
     */
    public function getDiscountsWithUserFavoriteAction($customer_id)
    {
       $discounts = $this->getDoctrine()->getRepository('ModelBundle:Discount')->findBy(array(),array("id"=>"ASC"));

        $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

         $discountArray = array();

       foreach ($discounts as $discount) {
            if($customer->getCustomerPromotionsFavored()->contains($discount)){
                array_push($discountArray, ['discount' => $discount, 'isFavorite'=>true]);
            }else{
                array_push($discountArray, ['discount' => $discount, 'isFavorite'=>false]);
            }
       }  

       return $data= array('discounts'=>$discountArray , 'status' => 200, 'success' => 1); 
    }

}