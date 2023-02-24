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

use ModelBundle\Entity\StoreLoyaltyCard;



class StoreLoyaltyCardController extends FOSRestController
{
     /**
     * @Rest\View(serializerGroups={"userLoyaltyCardWithStoreChainGroup"})
     * @Rest\Get("/store/loyaltycards")
     * @ApiDoc(
     * 		section="Store Loyalty Card",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the store loyaltyCards",
     * 
     *
     * )
     */
    public function getStoreLoyaltyCardsAction()
    {
       $cards = $this->getDoctrine()->getRepository('ModelBundle:StoreLoyaltyCard')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('store_loyalty_cards'=>$cards , 'status' => '200', 'success' => '1'); 
    }


    /**
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @Rest\Get("/store/loyaltycards/{store_chain_id}")
     * @ApiDoc(
     *      section="Store Loyalty Card",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the store loyaltyCards",
     *     requirements={
     *          {"name"="store_chain_id", "dataType"="int", "requirement"=true, "description"="the id of the StoreChain"}
     *}
     * )
     */
    public function getStoreLoyaltyCardsByStoreAction($store_chain_id)
    {
       $storeChain = $this->getDoctrine()->getRepository('ModelBundle:StoreChain')->find($store_chain_id);
        if (empty($storeChain)) {
            return  ['message' => 'store Chain not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }

       $cards = $this->getDoctrine()->getRepository('ModelBundle:StoreLoyaltyCard')->findBy(array('loyaltyCardStoreChain' => $storeChain));

        return $data= array('store_loyalty_cards'=>$cards , 'status' => '200', 'success' => '1'); 
    }

     /**
     * @Rest\View(serializerGroups={"userLoyaltyCardWithStoreChainGroup"})
     * @Rest\Get("/store/loyaltycard/{id}")
     * @ApiDoc(
     *		section="Store Loyalty Card",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when Store Loyalty Card not found"
     *     },
     *     description="Find Store Loyalty Card by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the Store Loyalty Card"}
     *      }
     * )
     */
    public function getStoreLoyaltyCardAction($id)
    {
        $card = $this->getDoctrine()->getRepository('ModelBundle:StoreLoyaltyCard')->find($id);
        if (empty($card)) {
            return  ['message' => 'Store Loyalty Card not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        
        return array('store_loyalty_card'=>$card, 'message' => 'Store Loyalty Card found', 'status'=> 200, 'success' => '1');
    }

     /**
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @Rest\Post("/store/loyaltycard")
     * @ApiDoc(
     *	section="Store Loyalty Card",
     *   description="Add Store Loyalty Card",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *    requirements={
     *      {"name"="loyalty_card_name", "dataType"="string", "requirement"=true, "description"="store loyalty card name"},
     *      {"name"="loyalty_card_image", "dataType"="string", "requirement"=true, "description"="store loyalty card image"},
            {"name"="store_chain_id", "dataType"="integer", "requirement"=true, "description"="store chain id"},
     * })
     */
    public function postAddStoreLoyaltyCardAction(Request $request)
    {
       
          
        $storeChainId = $request->get('store_chain_id');
            
        $storeChain = $this->getDoctrine()->getRepository('ModelBundle:StoreChain')->find($storeChainId);
        if (empty($storeChain)) {
            return  ['message' => 'Store Chain not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }


        $card = new StoreLoyaltyCard();

        $card->setLoyaltyCardName($request->get('loyalty_card_name'));
        $card->setLoyaltyCardImage($request->get('loyalty_card_image'));
        $card->setLoyaltyCardStoreChain($storeChain);
      

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($card);
        $manager->flush();
       
        return ['store_loyalty_card'=>$card, 'message' => 'Store Loyalty Card Created', 'status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/store/loyaltycard/{id}")
     * @ApiDoc(
     *		section="Store Loyalty Card",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when Store Loyalty Card not found",
     *     },
     *     description="Delete Store Loyalty Card",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the Store Loyalty Card"}
     *      }
     * )
     */
    public function deleteStoreLoyaltyCardAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $card = $manager->getRepository('ModelBundle:StoreLoyaltyCard')
                    ->find($id);
        if (empty($card)) {
            return  ['message' => 'Store Loyalty Card not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        $manager->remove($card);
        $manager->flush();
        return ['message' => 'Store Loyalty Card Deleted','status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @Rest\Put("/store/loyaltycard/{id}")
     * @ApiDoc(
     *		section="Store Loyalty Card",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when Store Loyalty Card not found",
     *     },
     *     description="update Store Loyalty Card",
	*    requirements={
     {"name"="loyalty_card_name", "dataType"="string", "requirement"=true, "description"="store loyalty card name"},
     *      {"name"="loyalty_card_image", "dataType"="string", "requirement"=true, "description"="store loyalty card image"},
     * }
     )
     */
    public function putStoreLoyaltyCardAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $card = $manager->getRepository('ModelBundle:StoreLoyaltyCard')
                    ->find($id);
        if (empty($card)) {
            return  ['message' => 'Store Loyalty Card not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        $card->setLoyaltyCardName($request->get('loyalty_card_name'));
        $card->setLoyaltyCardImage($request->get('loyalty_card_image'));
        
        $manager->persist($card);
        $manager->flush();

        return ['store_loyalty_card' => $card ,'message' => 'Store Loyalty Card Edited', 'status'=>200 , 'success'=> 1];
    }
   
    
}