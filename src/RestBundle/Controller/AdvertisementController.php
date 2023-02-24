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

use ModelBundle\Entity\Advertisement;

class AdvertisementController extends FOSRestController
{
     

     /**
     * @Rest\View()
     * @Rest\Get("/advertisements")
     * @ApiDoc(
     * 		section="Advertisement",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Advertisments",
     * 
     *
     * )
     */
    public function getAdvertisementsAction()
    {
       $advertisements = $this->getDoctrine()->getRepository('ModelBundle:Advertisement')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('advertisements'=>$advertisements , 'status' => '200', 'success' => '1'); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/advertisement/{id}")
     * @ApiDoc(
     *		section="Advertisement",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when advertisement not found"
     *     },
     *     description="Find advertisement by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the advertisement"}
     *      }
     * )
     */
    public function getAdvertisementAction($id)
    {
        $advertisement = $this->getDoctrine()->getRepository('ModelBundle:Advertisement')->find($id);
        if (empty($advertisement)) {
            return  ['message' => 'Advertisement not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        
        return array('advertisement'=>$advertisement, 'message' => 'Advertisement found', 'status'=> 200, 'success' => '1');
    }

     /**
     * @Rest\View()
     * @Rest\Post("/advertisement")
     * @ApiDoc(
     *  section="Advertisement",
     *   description="Add advertisement",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *    requirements={
     *      {"name"="advertisement_type", "dataType"="string", "requirement"=true, "description"="advertisement type"},

      {"name"="advertisement_name", "dataType"="string", "requirement"=true, "description"="advertisement name"},

            {"name"="advertisement_link", "dataType"="string", "requirement"=true, "description"="advertisement link"},

            {"name"="advertisement_photo_link", "dataType"="string", "requirement"=true, "description"="advertisement photo link"},

            {"name"="advertisement_bonus_points_value", "dataType"="int", "requirement"=true, "description"="advertisement bonus points value"}
     *      }
     * )
     */
    public function postAddAdvertisementAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $advertisement = new Advertisement();
        $advertisement->setAdvertisementType($request->get('advertisement_type'));
        $advertisement->setAdvertisementName($request->get('advertisement_name'));
        $advertisement->setAdvertisementLink($request->get('advertisement_link'));
        $advertisement->setAdvertisementPhotoLink($request->get('advertisement_photo_link'));
        $advertisement->setAdvertisementBonusPointsValue($request->get('advertisement_bonus_points_value'));
      
        
        $manager->persist($advertisement);
        $manager->flush();
        
       
        return ['advertisement'=>$advertisement, 'message' => 'Advertisement Created', 'status'=> 200, 'success' => '1'];
    }

     /**
     * @Rest\View()
     * @Rest\Post("/addadvertisement/bystore/{store_id}")
     * @ApiDoc(
     *  section="Advertisement",
     *   description="Add advertisement to a store",
     *   statusCodes={
     *       200="Returned when successful",
     *       404="Returned when store not found"
     *    },
     *    requirements={
     *      {"name"="advertisement_type", "dataType"="string", "requirement"=true, "description"="advertisement type"},

      {"name"="advertisement_name", "dataType"="string", "requirement"=true, "description"="advertisement name"},

            {"name"="advertisement_link", "dataType"="string", "requirement"=true, "description"="advertisement link"},

            {"name"="advertisement_photo_link", "dataType"="string", "requirement"=true, "description"="advertisement photo link"},

            {"name"="advertisement_bonus_points_value", "dataType"="int", "requirement"=true, "description"="advertisement bonus points value"}
     *      }
     * )
     */
    public function postAddAdvertisementByStoreAction(Request $request,$store_id)
    {
        $manager = $this->getDoctrine()->getManager();

        $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($store_id);
        if (empty($store)) {
            return  ['message' => 'Store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $advertisement = new Advertisement();
        $advertisement->setAdvertisementType($request->get('advertisement_type'));
        $advertisement->setAdvertisementName($request->get('advertisement_name'));
        $advertisement->setAdvertisementLink($request->get('advertisement_link'));
        $advertisement->setAdvertisementStore($store);
        $advertisement->setAdvertisementPhotoLink($request->get('advertisement_photo_link'));
        $advertisement->setAdvertisementBonusPointsValue($request->get('advertisement_bonus_points_value'));
      
        
        $manager->persist($advertisement);
        $manager->flush();
        
       
        return ['advertisement'=>$advertisement, 'message' => 'Advertisement Created', 'status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/advertisement/{id}")
     * @ApiDoc(
     *		section="Advertisement",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when advertisement not found",
     *     },
     *     description="Delete advertisement",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the advertisement"}
     *      }
     * )
     */
    public function deleteAdvertisementAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $advertisement = $manager->getRepository('ModelBundle:Advertisement')
                    ->find($id);
        if (empty($advertisement)) {
            return  ['message' => 'Advertisement not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        $manager->remove($advertisement);
        $manager->flush();
        return ['message' => 'Advertisement Deleted','status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/advertisement/{id}")
     * @ApiDoc(
     *		section="Advertisement",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when advertisement not found",
     *     },
     *     description="update advertisement",
	 *
     *	   requirements={
     *      {"name"="advertisement_type", "dataType"="string", "requirement"=false, "description"="advertisement type"},
           {"name"="advertisement_name", "dataType"="string", "requirement"=true, "description"="advertisement name"},


            {"name"="advertisement_link", "dataType"="string", "requirement"=false, "description"="advertisement link"},

            {"name"="advertisement_photo_link", "dataType"="string", "requirement"=false, "description"="advertisement photo link"},

            {"name"="advertisement_bonus_points_value", "dataType"="int", "requirement"=false, "description"="advertisement bonus points value"}
     *      }
     * )
     */
    public function putAdvertisementAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $advertisement = $manager->getRepository('ModelBundle:Advertisement')->find($id);
        if (empty($advertisement)) {
            return  ['message' => 'Advertisement not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
       
        $advertisement->setAdvertisementType($request->get('advertisement_type',$advertisement->getAdvertisementType()));
        $advertisement->setAdvertisementName($request->get('advertisement_name'),$advertisement->getAdvertisementName());
        $advertisement->setAdvertisementLink($request->get('advertisement_link',$advertisement->getAdvertisementLink()));
        $advertisement->setAdvertisementPhotoLink($request->get('advertisement_photo_link',$advertisement->getAdvertisementPhotoLink()));
        $advertisement->setAdvertisementBonusPointsValue($request->get('advertisement_bonus_points_value',$advertisement->getAdvertisementBonusPointsValue()));

        
        $manager->persist($advertisement);
        $manager->flush();
        return ['advertisement' => $advertisement ,'message' => 'Advertisement Edited', 'status'=>200 , 'success'=> 1];
    }
   

   /**
     * @Rest\View()
     * @Rest\Get("/api/advertisements/{store_id}/{customer_id}")
     * @ApiDoc(
     *      section="Advertisement",
     *         statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store or cutomer not found"
     *     },
     *     description="Find advertisement by store_id and with isViewed",
     *
     *    
     * )
     */
    public function getAdvertisementByStoreIdAction($store_id,$customer_id)
    {
        $store = $this->getDoctrine()->getRepository('ModelBundle:Store')->find($store_id);
        if (empty($store)) {
            return  ['message' => 'Store not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $customer = $this->getDoctrine()->getRepository('ModelBundle:Customer')->find($customer_id);
        if (empty($customer)) {
            return  ['message' => 'customer not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => 0];
        }

        $repository = $this->getDoctrine()->getRepository('ModelBundle:Advertisement');

         $advertisements = $store->getStoreAdvertisements();
        
         $advertisementArray = array();
         foreach ($advertisements as $advertisement) {
            if($customer->getCustomerAdvertisementsView()->contains($advertisement)){
                array_push($advertisementArray, ['advertisement' => $advertisement, 'isViewed'=>true]);
            }else{
                array_push($advertisementArray, ['advertisement' => $advertisement, 'isViewed'=>false]);
            }
       }

       return $data= array('advertisements'=>$advertisementArray , 'status' => 200, 'success' => 1);
    }

}