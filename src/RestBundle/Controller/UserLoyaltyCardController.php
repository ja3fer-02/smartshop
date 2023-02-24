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

use ModelBundle\Entity\UserLoyaltyCard;
use ModelBundle\Entity\Customer;
use ModelBundle\Entity\StoreLoyaltyCard;



class UserLoyaltyCardController extends FOSRestController
{
     

     /**
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @Rest\Get("/userLoyaltyCard")
     * @ApiDoc(
     * 		section="UserLoyaltyCard",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the UserLoyaltyCards",
     * 
     *
     * )
     */
    public function getAllUserLoyaltyCardsAction()
    {
       $userLoyaltyCards = $this->getDoctrine()->getRepository('ModelBundle:UserLoyaltyCard')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('user_loyalty_cards'=>$userLoyaltyCards , 'status' => 200, 'success' => 1); 
    }


  /**
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @Rest\Get("/api/userLoyaltyCard/customer/{customer_id}")
     * @ApiDoc(
     *      section="UserLoyaltyCard",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the UserLoyaltyCards of on user",
     * 
     *
     * )
     */
    public function getUserLoyaltyCardsAction($customer_id)
    {
         $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

       $userLoyaltyCards = $this->getDoctrine()->getRepository('ModelBundle:UserLoyaltyCard')->findBy(array('userLoyaltyCardCustomer' => $customer),array("id"=>"DESC"));

       return $data= array('user_loyalty_cards'=>$userLoyaltyCards , 'status' => 200, 'success' => 1); 
    }

     /**
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @Rest\Get("/userLoyaltyCard/{id}")
     * @ApiDoc(
     *		section="UserLoyaltyCard",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when UserLoyaltyCard not found"
     *     },
     *     description="Find userLoyaltyCard by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the userLoyaltyCard"}
     *      }
     * )
     */
    public function getUserLoyaltyCardAction($id)
    {
        $userLoyaltyCard = $this->getDoctrine()->getRepository('ModelBundle:UserLoyaltyCard')->find($id);
        if (empty($userLoyaltyCard)) {
            return  ['message' => 'UserLoyaltyCard not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        
        return array('user_loyalty_card'=>$userLoyaltyCard, 'message' => 'UserLoyaltyCard found', 'status'=> 200, 'success' => 1);
    }

     /**
     * @Rest\Post("/api/userLoyaltyCard/customer/{customer_id}")
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @ApiDoc(
     *	section="UserLoyaltyCard",
     *   description="Add UserLoyaltyCard",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *    requirements={
     *      {"name"="barcode", "dataType"="string", "requirement"=true, "description"="barcode"},
            {"name"="barcode_format", "dataType"="string", "requirement"=true, "description"="barcode format"},
     *      {"name"="customer_id", "dataType"="int", "requirement"=true, "description"="customer id "},
     *      {"name"="store_loyalty_card_id", "dataType"="int", "requirement"=true, "description"="store loyaltycard id"}
     * })
     */
    public function postAddUserLoyaltyCardAction($customer_id,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $userLoyaltyCard = new UserLoyaltyCard;

        $customer = $manager->getRepository('ModelBundle:Customer')
                    ->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $storeLoyaltyCard = $manager->getRepository('ModelBundle:StoreLoyaltyCard')
                    ->find($request->get('store_loyalty_card_id'));
        if (empty($storeLoyaltyCard)) {
            return  ['message' => 'storeLoyaltyCard not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $userLoyaltyCard->setBarcode($request->get('barcode'));
        $userLoyaltyCard->setBarcodeFormat($request->get('barcode_format'));
        $userLoyaltyCard->setUserLoyaltyCardCustomer($customer);
        $userLoyaltyCard->setStoreLoyaltyCard($storeLoyaltyCard);      
        
        $manager->persist($userLoyaltyCard);
        $manager->flush();
       
        return ['userLoyaltyCard'=>$userLoyaltyCard, 'message' => 'user loyalty card Created', 'status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/api/userLoyaltyCard/{id}")
     * @ApiDoc(
     *		section="UserLoyaltyCard",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when UserLoyaltyCard not found",
     *     },
     *     description="Delete UserLoyaltyCard",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the UserLoyaltyCard"}
     *      }
     * )
     */
    public function deleteUserLoyaltyCardAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $userLoyaltyCard = $manager->getRepository('ModelBundle:UserLoyaltyCard')
                    ->find($id);
        if (empty($userLoyaltyCard)) {
            return  ['message' => 'userLoyaltyCard not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }
        $manager->remove($userLoyaltyCard);
        $manager->flush();
        return ['message' => 'userLoyaltyCard Deleted','status'=> 200, 'success' => 1];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @Rest\Put("/userLoyaltyCard/{id}")
     * @ApiDoc(
     *		section="UserLoyaltyCard",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when UserLoyaltyCard not found",
     *     },
     *     description="update UserLoyaltyCard",
	 *requirements={
     *      {"name"="barcode", "dataType"="string", "requirement"=true, "description"="barcode"},
            {"name"="barcode_format", "dataType"="string", "requirement"=true, "description"="barcode format"},
     *      {"name"="store_loyalty_card_id", "dataType"="int", "requirement"=true, "description"="store loyaltycard id"}}
     * )
     */
    public function putUserLoyaltyCardAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $userLoyaltyCard = $manager->getRepository('ModelBundle:UserLoyaltyCard')->find($id);
        if (empty($userLoyaltyCard)) {
            return  ['message' => 'userLoyaltyCard not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }

   
     /*   $customer = $manager->getRepository('ModelBundle:Customer')
                    ->find($request->get('customer_id'));
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }*/

       
        $storeLoyaltyCard = $manager->getRepository('ModelBundle:StoreLoyaltyCard')
                    ->find($request->get('store_loyalty_card_id'));
        if (empty($storeLoyaltyCard)) {
            return  ['message' => 'storeLoyaltyCard not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $userLoyaltyCard->setBarcode($request->get('barcode',$userLoyaltyCard->getBarcode()));
        $userLoyaltyCard->setBarcodeFormat($request->get('barcode_format',$userLoyaltyCard->getBarcodeFormat()));
       // $userLoyaltyCard->setUserLoyaltyCardCustomer($customer);
        $userLoyaltyCard->setStoreLoyaltyCard($storeLoyaltyCard);

        $manager->persist($userLoyaltyCard);
        $manager->flush();
        return ['userLoyaltyCard' => $userLoyaltyCard ,'message' => 'userLoyaltyCard Edited', 'status'=>200 , 'success'=> 1];
    }
   
    /**
     * @Rest\Post("/api/userLoyaltyCard/share/{customer_id}")
     * @Rest\View(serializerGroups={"shortDataUserLoyaltyCardGroup"})
     * @ApiDoc(
     *  section="UserLoyaltyCard",
     *   description="Share UserLoyaltyCard",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *    requirements={
     *      {"name"="email", "dataType"="string", "requirement"=true, "description"="recipient email"},
            {"name"="user_loyalty_card_id", "dataType"="int", "requirement"=true, "description"="user loyalty card id"}
     * })
     */
    public function shareUserLoyaltyCardAction($customer_id,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $userLoyaltyCard = $manager->getRepository('ModelBundle:UserLoyaltyCard')
                    ->find($request->get('user_loyalty_card_id'));
             if (empty($userLoyaltyCard)) {
            return  ['message' => 'userLoyaltyCard not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => -1];
        }       

        $recipientCustomer = $manager->getRepository('ModelBundle:Customer')
                    ->findOneBy(array('email' => $request->get('email')));
        if (empty($recipientCustomer)) {
            return  ['message' => 'Recipient Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

       /* $customer = $manager->getRepository('ModelBundle:Customer')
                    ->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'Customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }*/
        $newUserLoyaltyCard= clone $userLoyaltyCard;
        $newUserLoyaltyCard->setUserLoyaltyCardCustomer($recipientCustomer);    
        
        $manager->persist($newUserLoyaltyCard);
        $manager->flush();
       
        return ['message' => 'user loyalty card Shared', 'status'=> 200, 'success' => 1];
    }
}