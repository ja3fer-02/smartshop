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

use ModelBundle\Entity\StoreChain;

class StoreChainController extends FOSRestController
{
     

     /**
     * @Rest\View()
     * @Rest\Get("/storechains")
     * @ApiDoc(
     * 		section="Store Chain",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the Store chains",
     * 
     *
     * )
     */
    public function getStoreChainsAction()
    {
       $storeChains = $this->getDoctrine()->getRepository('ModelBundle:StoreChain')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('store_chains'=>$storeChains , 'status' => '200', 'success' => '1'); 
    }

     /**
     * @Rest\View()
     * @Rest\Get("/storechain/{id}")
     * @ApiDoc(
     *		section="Store Chain",
     *     statusCodes={
     *         200="Returned when successful",
     *		   404="Returned when store chain not found"
     *     },
     *     description="Find store chain by id",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the store chain"}
     *      }
     * )
     */
    public function getStoreChainAction($id)
    {
        $storeChain = $this->getDoctrine()->getRepository('ModelBundle:StoreChain')->find($id);
        if (empty($storeChain)) {
            return  ['message' => 'Store Chain not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        
        return array('storeChain'=>$storeChain, 'message' => 'Store Chain found', 'status'=> 200, 'success' => '1');
    }

     /**
     * @Rest\View()
     * @Rest\Post("/storechain")
     * @ApiDoc(
     *	section="Store Chain",
     *   description="Add Store Chain",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *	  requirements={
	 *      {"name"="store_chain_name", "dataType"="string", "requirement"=true, "description"="store chain name"},

            {"name"="store_chain_description", "dataType"="string", "requirement"=true, "description"="store chain description"},

            {"name"="store_chain_website", "dataType"="string", "requirement"=true, "description"="store chain website"},

            {"name"="store_chain_address", "dataType"="string", "requirement"=true, "description"="store chain address"},

            {"name"="store_chain_logo", "dataType"="string", "requirement"=true, "description"="store chain logo"},


     *      }
     * )
     */
    public function postAddStoreChainAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $storeChain = new StoreChain();
        $storeChain->setStoreChainName($request->get('store_chain_name'));
        $storeChain->setStoreChainDescription($request->get('store_chain_description'));
        $storeChain->setStoreChainWebsite($request->get('store_chain_website'));
        $storeChain->setStoreChainAddress($request->get('store_chain_address'));
        $storeChain->setStoreChainLogo($request->get('store_chain_logo'));

        $manager->persist($storeChain);
        $manager->flush();
        
       
        return ['store_chain'=>$storeChain, 'message' => 'store chain Created', 'status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/storechain/{id}")
     * @ApiDoc(
     *		section="Store Chain",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store chain not found",
     *     },
     *     description="Delete store chain",
	 *
     *	   requirements={
	 *      	{"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the store chain"}
     *      }
     * )
     */
    public function deleteStoreChainAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $storeChain = $manager->getRepository('ModelBundle:StoreChain')
                    ->find($id);
        if (empty($storeChain)) {
            return  ['message' => 'Store Chain not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        $manager->remove($storeChain);
        $manager->flush();
        return ['message' => 'Store Chain Deleted','status'=> 200, 'success' => '1'];
    }

    /**
     * @Rest\View()
     * @Rest\Put("/storechain/{id}")
     * @ApiDoc(
     *		section="Store Chain",
     *     	statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store chain not found",
     *     },
     *     description="update store chain",
	 *
     *    requirements={
     *      {"name"="store_chain_name", "dataType"="string", "requirement"=true, "description"="store chain name"},

            {"name"="store_chain_description", "dataType"="string", "requirement"=true, "description"="store chain description"},

            {"name"="store_chain_website", "dataType"="string", "requirement"=true, "description"="store chain website"},

            {"name"="store_chain_address", "dataType"="string", "requirement"=true, "description"="store chain address"},

            {"name"="store_chain_logo", "dataType"="string", "requirement"=true, "description"="store chain logo"},

     *      }
     * )
    
     */
    public function putStoreAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $storeChain = $manager->getRepository('ModelBundle:StoreChain')->find($id);
        if (empty($storeChain)) {
            return  ['message' => 'Store Chain not found', 'status'=>Response::HTTP_NOT_FOUND, 'success'=> 0];
        }
        
        $storeChain->setStoreChainName($request->get('store_chain_name',$storeChain->getStoreChainName()));
        $storeChain->setStoreChainDescription($request->get('store_chain_description',$storeChain->getStoreChainDescription()));
        $storeChain->setStoreChainWebsite($request->get('store_chain_website',$storeChain->getStoreChainWebsite()));
        $storeChain->setStoreChainAddress($request->get('store_chain_address',$storeChain->getStoreChainAddress()));
        $storeChain->setStoreChainLogo($request->get('store_chain_logo',$storeChain->getStoreChainLogo()));

        $manager->persist($storeChain);
        $manager->flush();
      
      
        return ['store' => $storeChain ,'message' => 'Store Chain Edited', 'status'=>200 , 'success'=> 1];
    }
   
    
}