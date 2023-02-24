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



class PromotionController extends FOSRestController
{
     
     
     /**
     * @Rest\View()
     * @Rest\Get("/api/promotions/{customer_id}")
     * @ApiDoc(
     * 		section="Promotion",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Promotions",
     * 
     *
     * )
     */
    public function getPromotionsAction($customer_id)
    {

         $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

    $manager = $this->getDoctrine()->getManager();
    $repository = $this->getDoctrine()->getRepository('ModelBundle:Promotion');


    // get promotions when type = discount or flash sale and endDate if > today and order desc
    $query = $repository->createQueryBuilder('p')
        ->Where('p INSTANCE OF :type')
        ->leftJoin('ModelBundle:Flashsale', 'f', 'WITH', 'p.id = f.id')
        ->OrWhere('f.flashSaleEndDate > :date')
        ->setParameter('type', $manager->getClassMetadata('ModelBundle\Entity\Discount'))
        ->setParameter('date', new \DateTime('now  -2 day') )
        ->orderBy('p.createdAt', 'DESC')
        ->getQuery();

        $promotions = $query->getResult();

        $promotionArray = array();
         foreach ($promotions as $promotion) {

            if($customer->getCustomerPromotionsFavored()->contains($promotion)){
                array_push($promotionArray, ['promotion' => $promotion, 'isFavorite'=>true, 'store'=> $promotion->getPromotionStoreProduct()->getStoreProductStore()]);
            }else{
                array_push($promotionArray, ['promotion' => $promotion, 'isFavorite'=>false, 'store'=> $promotion->getPromotionStoreProduct()->getStoreProductStore()]);
            }
       }  

       return $data= array('promotions'=>$promotionArray , 'status' => 200, 'success' => 1); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/promotion/{id}")
     * @ApiDoc(
     *		section="Promotion",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when promotion not found"
     *     },
     *     description="Find promotion by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the promotion"}
     *      }
     * )
     */
    public function getPromotionAction($id)
    {
        $promotion = $this->getDoctrine()->getRepository('ModelBundle:Promotion')->find($id);
        if (empty($promotion)) {
            return  ['message' => 'Promotion not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        
        return array('promotion'=>$promotion, 'message' => 'Promotion found', 'status'=> 200, 'success' => 1);
    }

 /**
     * @Rest\View()
     * @Rest\Get("/api/promotions/{customer_id}/{store_id}")
     * @ApiDoc(
     *		section="Promotion",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when store not found"
     *     },
     *     description="Find promotion by store_id",
	 *
     *	   requirements={
	 *      	{"name"="store_id", "dataType"="int", "requirement"=true, "description"="the id of the store"}
     *      }
     * )
     */
    public function getPromotionByStoreIdAction($store_id,$customer_id)
    {

        $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($store_id);
        if (empty($store)) {
            return  ['message' => 'Store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }


        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('ModelBundle:Promotion');

   // get promotions when type = discount or flash sale and endDate if > today and order desc  and where store is the store we are looking for
        $query = $repository->createQueryBuilder('p')
            ->Where('p INSTANCE OF :type')
            ->leftJoin('ModelBundle:Flashsale', 'f', 'WITH', 'p.id = f.id')
            ->OrWhere('f.flashSaleEndDate > :date')
            ->setParameter('type', $manager->getClassMetadata('ModelBundle\Entity\Discount'))
            ->setParameter('date', new \DateTime('now  -2 day'))
            ->orderBy('p.createdAt', 'DESC')

            ->leftJoin('p.promotionStoreProduct', 'psp')
            ->leftJoin('psp.storeProductStore', 'store')
            ->AndWhere('store.id = :store_id')
            ->setParameter('store_id', $store_id)
            
            ->getQuery();

         $promotions = $query->getResult();

        $promotionArray = array();
         foreach ($promotions as $promotion) {
            if($customer->getCustomerPromotionsFavored()->contains($promotion)){
                array_push($promotionArray, ['promotion' => $promotion, 'isFavorite'=>true]);
            }else{
                array_push($promotionArray, ['promotion' => $promotion, 'isFavorite'=>false]);
            }
       }  
        

       return $data= array('promotions'=>$promotionArray , 'status' => 200, 'success' => 1);
    }
     











}