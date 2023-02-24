<?php

namespace RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ModelBundle\Entity\User;
use ModelBundle\Entity\Customer;
use ModelBundle\Entity\DeviceToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends Controller
{
     
    /**
     * @Rest\View()
     * @Rest\Get("/users")
     * @ApiDoc(
     *      section="User",
     *     statusCodes={
     *         200="Returned when successful",
     *     },
     *     description="Get all the user",
     * 
     *
     * )
     */
    public function getUsersAction()
    {
       $users = $this->getDoctrine()->getRepository('ModelBundle:User')->findBy(array(),array("id"=>"ASC"));  
       return $data= array('users'=>$users , 'status' => '200', 'success' => '1'); 
    }

    
    
    /**
     * @Rest\View()
     * @Rest\Delete("/user/{id}")
     * @ApiDoc(
     *      section="User",
     *     statusCodes={
     *         200="Returned when successful", 
     *     },
     *     description="Delete user",
     *
     *     requirements={
     *          {"name"="id", "dataType"="int", "requirement"=true, "description"="the id of the user"}
     *      }
     * )
     */
    public function deleteUserAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $demande = $manager->getRepository('ModelBundle:User')
                    ->find($id);
        $manager->remove($demande);
        $manager->flush();
        return  ['message' => 'user Deleted', 'status'=>Response::HTTP_NO_CONTENT];
    }
     /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Post("/register")
     * @ApiDoc(
     *     section="User",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when error"
     *     },
     *     description="Register user",
     *     parameters={
	 *      	{"name"="firstname", "dataType"="string", "required"=true, "description"="firstname"},
	 *      	{"name"="lastname", "dataType"="string", "required"=true, "description"="lastname"},
	 *          {"name"="email", "dataType"="string", "required"=true, "description"="email"},
	 *      	{"name"="password", "dataType"="string", "required"=true, "description"="password"},
	 *          {"name"="country_code", "dataType"="string", "required"=true, "description"="country_code"},
     *          {"name"="facebook_id", "dataType"="string", "required"=true, "description"="facebook_id"},
     *          {"name"="activate_notification", "dataType"="boolean", "required"=true, "description"="activate_notification"},
     *          {"name"="photo", "dataType"="string", "required"=true, "description"="photo"},
     *          {"name"="phone", "dataType"="string", "required"=true, "description"="phone"},  
     *          {"name"="role", "dataType"="string", "required"=true, "description"="role"}
     *               }   
     *       
     *         
     *
     * )
     */
  public function RegisterAction(Request $request)
    {       

           
           $repository = $this->getDoctrine()->getRepository('ModelBundle:User');
           $verifedemail = $repository->findOneBy(array('email' => @$request->get('email')));
           if(!$verifedemail){
               if($request->get('role') == 'customer'){
                 $user = $repository->registerCustomer($request,$this->container);
               }elseif($request->get('role') == 'admin'){
                 $user = $repository->registerAdmin($request,$this->container);
               }elseif($request->get('role') == 'store_manager') {
                 $user = $repository->registerStoreManager($request,$this->container);
               }elseif($request->get('role') == 'store_chain_manager'){
                 $user = $repository->registerStoreChainManager($request,$this->container);
               }else{
                return ["message"=>"Select your type of user","success"=>-1,"status"=>200];
               }
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($user );
            $em->flush();
           /*  if($request->get('role') == 'customer'){
                $path =$request->getSchemeAndHttpHost().$request->getBasePath();
             
                          $data = array('_username'=>$user->getEmail(),'_password'=>$request->get('password'));
                           $data_string = json_encode($data);
                           $ch = curl_init($path.'/app_dev.php/login_check');
                             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                             curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                             curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                 'Content-Type: application/json',
                                 'Content-Length: ' . strlen($data_string))
                             );
                             curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                             curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
             
                             //execute post
                             $result = json_decode(curl_exec($ch),true);
             
                             //close connection
                             curl_close($ch);
                             if(!empty($result['token'])){
                                 $token = $result['token'];
                             }
                         }*/
              
                 $message = \Swift_Message::newInstance()
                        ->setSubject('Smarty Email')
                        ->setFrom('contact.smarty@gmail.com')
                        ->setTo(@$request->get('email'))
                        ->setBody(
                        $this->renderView(
                                     'Emails/registerEmail.html.twig',
                                      array('token' => $user->getTokenVerified())
                                    ),
                                    'text/html'
                                );
                $this->get('mailer')->send($message);
            return ["message"=>"Votre compte a été créé ! Félicitations,votre compte a été crée avec succès !","success"=>1,"status"=>200];
           }
            return ["message"=>"Account exist","success"=>0,"status"=>200];
    }
     /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Post("/login")
     * @ApiDoc(
     *   section="User",
     *   description="Login with a valid account and obtain token",
     *   parameters={
     *      {"name"="email", "dataType"="string", "required"=true, "format"="text", "description"="The username"},
     *      {"name"="password", "dataType"="string", "required"=true, "format"="text", "description"="The password"},
     *   },
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    }
     * )
     */
    public function loginAction(Request $request)
    {
        
        $data = $this->getDoctrine()->getRepository('ModelBundle:User');
       // $encoder = $this->container->get('security.password_encoder');

        // $client = $this->RegisterAction();
        // return $client->request('POST', '/login', array(), array(), array(
        //     'PHP_AUTH_USER' => $request->get('email'), 
        //     'PHP_AUTH_PW'   => $request->get('password')
        // ));
        // $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        
      
        $user_loged = $data->findOneByEmail(['email' => @$request->get('email')]);
        if($user_loged){ 
        //$bool = $encoder->isPasswordValid($user_loged,$request->get('password'));
        $bool = md5($request->get('password')) == $user_loged->getPlainPassword();

       }else{
        $bool = null;
       }
        if($bool==true){
            $user = $user_loged;
        }else{
            $user = null;
        }
        $email = $data->findOneByEmail($request->get('email'));
           if($user && $user->getRole()=='customer'){
            $path =$request->getSchemeAndHttpHost().$request->getBasePath();
        
                 $data = array('_username'=>$request->get('email'),'_password'=>$request->get('email'));//$request->get('password'));
                      $data_string = json_encode($data);
                      $ch = curl_init($path.'/app_dev.php/login_check');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
                        //execute post
                        $result = json_decode(curl_exec($ch),true);
        
                        //close connection
                        curl_close($ch);

                        if(!empty($result['token'])){
                            $token = $result['token'];
                        }
                        if(!empty($result['token'])){
                            $tokenRefresh = $result['refresh_token'];
                        }
                    }else{
                        $token = null;
                        $tokenRefresh = null;
                    }
        if($user){
            if($user->getVerifed()==1){
            return ["message"=>"Your email is valid","success"=>1,"user"=>$user,"status"=>200,"role"=>$user->getRole(),'token'=>$token,'refresh_token' => $tokenRefresh];
            }else{
                 return ["message"=>"your email is not valid","success"=>0,"status"=>200,"user"=>NULL];
            }       
        }elseif(is_null($email)){

          return ["message"=>"Email does not exist","success"=>-1,"status"=>200,"user"=>NULL];
        }

        return ["message"=>"Your password is incorrect ","success"=>-2,"status"=>200,"user"=>NULL];
      
    }
     /**
     * @Rest\Get("/Activate/{token}" , name="activate")
     * @ApiDoc(
     *   section="User",
     *   description="Active account user",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    }
     * )
     */
    public function activateAccountAction($token)
    {   
        $em = $this->getDoctrine()->getManager();
     	$data = $this->getDoctrine()->getRepository('ModelBundle:User');
        $user = $data->findOneByTokenVerified(['token_verified' => $token]);
        if(!$user){
            return ['message' => 'Invalid code',"success"=>0,"status"=>200];
        }
            $user->setVerifed(1);
            $em->persist($user);
            $em->flush();

   		//return ["message"=>"Your code is correct","success"=>1,"status"=>200];
            return new Response('Congratulations "'.$user->getFirstname().' '.$user->getLastname().'" Your account has been activated.');


                ;    
    }
    
     /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Put("/api/user/update/{user_id}")
     * @ApiDoc(
     *      section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when product not found",
     *     },
     *     description="update user",
     *     parameters={
     *          {"name"="firstname", "dataType"="string", "required"=true, "description"="firstname"},
     *          {"name"="lastname", "dataType"="string", "required"=true, "description"="lastname"},
     *          {"name"="email", "dataType"="string", "required"=true, "description"="email"},
     *          {"name"="country_code", "dataType"="string", "required"=true, "description"="country_code"},
     *          {"name"="activate_notification", "dataType"="boolean", "required"=true, "description"="activate_notification"},
     *          {"name"="photo", "dataType"="string", "required"=true, "description"="photo"},
     *          {"name"="phone", "dataType"="string", "required"=true, "description"="phone"},
     *          {"name"="role", "dataType"="string", "required"=true, "description"="role"},     
     *               
     *      }
     * )
     */
    public function putUserAction(Request $request ,$user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:User');
        $user = $data->find($user_id);
        if(!$user){
            return ['message' => 'user not found',"success"=>0,"status"=>200];
        }
           $user->setFirstname($request->get('firstname',$user->getFirstname()));
           $user->setLastname($request->get('lastname',$user->getLastname()));
           $user->setEmail($request->get('email',$user->getEmail()));
           $user->setActivated($request->get('activated',$user->getActivated()));
           if($request->get('role')== 'customer'){
            $user->setCountryCode($request->get('country_code',$user->getCountryCode()));
            $user->setActivateNotification($request->get('activate_notification',$user->getActivateNotification()));

           }
           $user->setPhoto($request->get('photo',$user->getPhoto()));
           $user->setPhone($request->get('phone',$user->getPhone()));
           $em->persist($user);
           $em->flush();

        return ["message"=>"User edited","user"=>$user,"success"=>1,"status"=>200];
    }
    /**
     * @Rest\View()
     * @Rest\Put("/api/user/password/{user_id}")
     * @ApiDoc(
     *      section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when product not found",
     *     },
     *     description="update user password",
     *     parameters={
     *          {"name"="old_password", "dataType"="string", "required"=true, "description"="old_password"},
     *          {"name"="new_password", "dataType"="string", "required"=true, "description"="new_password"},
     *          
     *               
     *      }
     * )
     */
    public function putPasswordAction(Request $request ,$user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:User');
        $user = $data->find($user_id);
     
        if(md5($request->get('old_password')) == $user->getPlainPassword()){

           $user->setPassword(md5(@$request->get('new_password')));
           $em->persist($user);
           $em->flush(); 

           return ["success"=>1,"message"=>"Your password updated","status"=>200];
        }   

        return ["message"=>"Your old password incorrectly typed","success"=>0,"status"=>200];
    }

    /**
     * @Rest\Post("/user/password/email")
     * @ApiDoc(
     *   section="User",
     *   description="Send email reset password",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *   parameters={
     *          {"name"="email", "dataType"="string", "required"=true, "description"="email"},
     *              }
     * )
     */

    public function sendResetEmailAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:User');
        $user = $data->findOneByEmail($request->get('email'));
        if($user){
            $user->setTokenReset(md5(time()));
            $em->persist($user);
            $em->flush(); 
            $message = \Swift_Message::newInstance()
                        ->setSubject('Smarty Reset Password')
                        ->setFrom('contact.smarty@gmail.com')
                        ->setTo(@$request->get('email'))
                        ->setBody(
                        $this->renderView(
                                     'Emails/resetPasswordEmail.html.twig',
                                      array('token' => $user->getTokenReset())
                                    ),
                                    'text/html'
                                );
           $this->get('mailer')->send($message);
             return ["success" =>1,'message' => "We have e-mailed your password reset link!", "status"=>200];
        }
        return ["success" =>0,"message"=> "We can't find a user with that e-mail address.", "status"=>200];
    }

    /**
     * @Rest\Post("/user/password/reset")
     * @ApiDoc(
     *   section="User",
     *   description="Send email reset password",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    },
     *   description="reset password",
     *   parameters={
     *          {"name"="email", "dataType"="string", "required"=true, "description"="email"},
     *          {"name"="token_reset", "dataType"="string", "required"=true, "description"="token_reset"},
     *           {"name"="new_password", "dataType"="string", "required"=true, "description"="new_password"},
     *              },
     *          
     * )
     */

    public function resetPasswordAction(Request $request)
    {     
          $em = $this->getDoctrine()->getManager();
          $data = $this->getDoctrine()->getRepository('ModelBundle:User');
          $user = $data->findOneByEmail($request->get('email'));
          if($user && $user->getTokenReset()==$request->get('token_reset')){
            if(@$request->get('new_password')){

               $user->setPassword(md5(@$request->get('new_password')));
               $user->setTokenReset(null);
               $em->persist($user);
               $em->flush(); 

               return ["success"=>1, "message" => "Token valid and password changed", "status"=>200];
            }
            return ["success" => -1,"message" => "Token is valid but password not changed.", "status"=>200];

          }
          return ["success" => 0,"message" => "Token is invalid.","status"=>200];
    }

     /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Get("/api/user/{user_id}")
     * @ApiDoc(
     *   section="User",
     *   description="get user",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    }
     * )
     */
    public function getUserAction($user_id)
    {
        $data = $this->getDoctrine()->getRepository('ModelBundle:User');
        $user = $data->find($user_id);
        if($user){
            return ["success"=>1,"message"=>"Current user","user"=>$user,"status"=>200,"role"=>$user->getRole()];
        }
              return ["success"=>0,"message"=>"User not exist","user"=>NULL,"status"=>200];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Post("/register/social")
     * @ApiDoc(
     *     section="User",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when error"
     *     },
     *     description="Register user with facebook",
     *     parameters={
     *          {"name"="firstname", "dataType"="string", "required"=true, "description"="firstname"},
     *          {"name"="lastname", "dataType"="string", "required"=true, "description"="lastname"},
     *          {"name"="email", "dataType"="string", "required"=true, "description"="email"},
     *          {"name"="facebook_id", "dataType"="string", "required"=true, "description"="facebook_id"},
     *          {"name"="photo", "dataType"="string", "required"=true, "description"="photo"},  
     *               }   
     *       
     *         
     *
     * )
     */
  public function RegisterSocialAction(Request $request)
    {        
          
           $repository = $this->getDoctrine()->getRepository('ModelBundle:Customer');
           $verifedemail = $repository->findOneBy(array('email' => @$request->get('email')));
           if(!$verifedemail && (!empty($request->get('facebook_id')))){
                $customer = new Customer(@$request->get('email'));
                $customer->setFirstname(@$request->get('firstname'));
                $customer->setLastname(@$request->get('lastname'));
                $customer->setEmail(@$request->get('email'));
                $customer->setFacebookId(@$request->get('facebook_id'));
                $customer->setVerifed(1);
                $customer->setActivated(0);
                $customer->setPhoto(@$request->get('photo'));
        
                $em = $this->getDoctrine()->getManager();
                $em->persist($customer );
                $em->flush();
                      
                      $path =$request->getSchemeAndHttpHost().$request->getBasePath();
        
                      $data = array('_username'=>$request->get('email'),'_password'=>$request->get('email'));
                      $data_string = json_encode($data);
                      $ch = curl_init($path.'/app_dev.php/login_check');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
                        //execute post
                        $result = json_decode(curl_exec($ch),true);
        
                        //close connection
                        curl_close($ch);

                            $token = $result['token'];
                            $tokenRefresh = $result['refresh_token'];
                      
                    
                   return ["success"=>1,"message"=>"Your account has been successfully created!","user"=>$customer,"status"=>200,'token'=>$token,'refresh_token' => $tokenRefresh];
             
            }elseif($verifedemail && $verifedemail->getFacebookId()==$request->get('facebook_id')){
                
                     $path =$request->getSchemeAndHttpHost().$request->getBasePath();
        
                      $data = array('_username'=>$request->get('email'),'_password'=>$request->get('email'));
                      $data_string = json_encode($data);
                      $ch = curl_init($path.'/app_dev.php/login_check');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
                        //execute post
                        $result = json_decode(curl_exec($ch),true);
        
                        //close connection
                        curl_close($ch);

                            $token = $result['token'];
                            $tokenRefresh = $result['refresh_token'];


                return ["success"=>2,"message"=>"login","user"=>$verifedemail,"status"=>200,'token'=>$token,'refresh_token' => $tokenRefresh];

             }elseif($verifedemail && (!empty($request->get('facebook_id')))){

               $verifedemail->setFirstname(@$request->get('firstname'));
               $verifedemail->setLastname(@$request->get('lastname'));
               $verifedemail->setEmail(@$request->get('email'));
               $verifedemail->setFacebookId($request->get('facebook_id'));
               $verifedemail->setVerifed(1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($verifedemail);
                $em->flush();

                    $path =$request->getSchemeAndHttpHost().$request->getBasePath();
        
                      $data = array('_username'=>$request->get('email'),'_password'=>$request->get('email'));
                      $data_string = json_encode($data);
                      $ch = curl_init($path.'/app_dev.php/login_check');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
                        //execute post
                        $result = json_decode(curl_exec($ch),true);
        
                        //close connection
                        curl_close($ch);

                            $token = $result['token'];
                            $tokenRefresh = $result['refresh_token'];

                return ["success"=>3,"message"=>"Account exist with this email and it updated","user"=>$verifedemail,"status"=>200,'token'=>$token,'refresh_token' => $tokenRefresh];
            }
             return ["success"=>0,"message"=>"Erro","user"=>NULL,"status"=>200];
    }

    /**
     * @Rest\View()
     * @Rest\Get("/api/user/favoris/{user_id}")
     * @ApiDoc(
     *   section="User",
     *   description="get user",
     *   statusCodes={
     *       200="Returned when successful",
     *       500="Returned when input data not validated"
     *    }
     * )
     */
    public function getFavoriteUserAction($user_id)
    {
        $data = $this->getDoctrine()->getRepository('ModelBundle:User');
        $customer = $data->find($user_id);

        $promotionArray = array();
        foreach ($customer->getCustomerPromotionsFavored() as $promotion) {
                array_push($promotionArray, ['promotion' => $promotion, 'isFavorite'=>true, 'store'=> $promotion->getPromotionStoreProduct()->getStoreProductStore()]);    
        }  

        $productsArray = array();
        foreach ($customer->getCustomerProductsFavored() as $product) {
                array_push($productsArray, ['product' => $product, 'isFavorite'=>true]);
        }  

        return ["success"=>1,"message"=>"Current user favoris","products"=>$productsArray,"promotions"=>$promotionArray,"status"=>200];


    }

    /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Put("/api/user/update/location/{user_id}")
     * @ApiDoc(
     *      section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when user not found",
     *     },
     *     description="update customer location",
       parameters={
     *          {"name"="longitude", "dataType"="float", "required"=true, "description"="longitude"},
     *          {"name"="latitude", "dataType"="float", "required"=true, "description"="latitude"}
     *               }  
     * )
     */
    public function putLocationAction($user_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:Customer');
        $user = $data->find($user_id);
        if(!$user){
            return ['message' => 'customer not found',"success"=>0,"status"=>200];
        }
           $user->setLongitude($request->get('longitude'));
           $user->setLatitude($request->get('latitude'));
           $em->persist($user);
           $em->flush();

        return ["message"=>"Location updated","user"=>$user,"success"=>1,"status"=>200];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Put("/user/add/deviceToken/{user_id}/{device_token}")
     * @ApiDoc(
     *      section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when user not found",
     *     },
     *     description="add customer deviceToken",
         parameters={
     *          {"name"="device_os", "dataType"="string", "required"=true, "description"="the device OS (android/ios)"},
     *          {"name"="device_language", "dataType"="string", "required"=true, "description"="the device language (fr/en)"}
     *               }  
     * )
     */
    public function putDeviceTokenAction($user_id,$device_token, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:Customer');
        $dataDeviceToken = $this->getDoctrine()->getRepository('ModelBundle:DeviceToken');
        $user = $data->find($user_id);
       
       /* if($deviceToken){
            foreach ($deviceToken as $dt) {
            if($dt->getDeviceTokenCustomer()->getId() == $user_id){
            
            }}            
        }*/
       
        if(!$user){
            return ['message' => 'customer not found',"success"=>0,"status"=>200];
        }

        $deviceToken=$dataDeviceToken->findOneBy(array('notificationDeviceToken' => $device_token, 'deviceTokenCustomer' => $user_id));

        if($deviceToken){
            return ["message"=>"DeviceToken already exist","success"=>-1,"status"=>200];
        }

        $deviceToken=new DeviceToken();
        $deviceToken->setNotificationDeviceToken($device_token);
        $deviceToken->setDeviceTokenCustomer($user);
        $deviceToken->setDeviceOS($request->get('device_os'));
        $deviceToken->setDeviceLanguage($request->get('device_language'));

           $user->addCustomerDeviceToken($deviceToken);

           $em->persist($user);
           $em->flush();

        return ["message"=>"DeviceToken added","user"=>$user,"success"=>1,"status"=>200];}


    /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Put("/user/remove/deviceToken/{user_id}/{device_token}")
     * @ApiDoc(
     *      section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when user not found",
     *     },
     *     description="remove customer deviceToken",
        
     * )
     */
    public function putRemoveDeviceTokenAction($user_id,$device_token)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:Customer');
        $dataDeviceToken = $this->getDoctrine()->getRepository('ModelBundle:DeviceToken');
        $user = $data->find($user_id);
       
       /* if($deviceToken){
            foreach ($deviceToken as $dt) {
            if($dt->getDeviceTokenCustomer()->getId() == $user_id){
            
            }}            
        }*/
       
        if(!$user){
            return ['message' => 'customer not found',"success"=>0,"status"=>200];
        }

        $deviceToken=$dataDeviceToken->findOneBy(array('notificationDeviceToken' => $device_token, 'deviceTokenCustomer' => $user_id));

        if($deviceToken){
            //return ["message"=>"DeviceToken already exist","success"=>-1,"status"=>200];


           $em->remove($deviceToken);
            $em->flush();
             
            return ["message"=>"DeviceToken removed","user"=>$user,"success"=>1,"status"=>200];
        }
        
        return ["message"=>"DeviceToken not found","success"=>-1,"status"=>200];
       

       }
       
    /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Put("/api/user/update/points/{user_id}/{points}")
     * @ApiDoc(
     *      section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when user not found",
     *     },
     *     description="update customer points"
     * )
     */
    public function putPointsAction($user_id, $points)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:Customer');
        $user = $data->find($user_id);
        if(!$user){
            return ['message' => 'customer not found',"success"=>0,"status"=>200];
        }

        $currentPoints=$user->getPoints();
        if(!$currentPoints){
        $currentPoints=0;
            }

           $user->setPoints(($currentPoints+$points));

           $em->persist($user);
           $em->flush();

        return ["message"=>"Location updated","user"=>$user,"success"=>1,"status"=>200];
    }

    /**
     * @Rest\View(serializerGroups={"shortDataCustomerGroup"})
     * @Rest\Put("/api/user/update/adsviewed/{user_id}/{advertisement_id}")
     * @ApiDoc(
     *      section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when user or ads not found",
     *     },
     *     description="add an advertisement as viewed"
     * )
     */
    public function putAdsViewedAction($user_id, $advertisement_id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('ModelBundle:Customer');
        $user = $data->find($user_id);
        if(!$user){
            return ['message' => 'customer not found',"success"=>0,"status"=>200];
        }

        $advertisement=$this->getDoctrine()->getRepository('ModelBundle:Advertisement')->find($advertisement_id);
        if(!$advertisement){
        return ['message' => 'advertisement not found',"success"=>0,"status"=>200];
            }

        $isAlreadyViewed=$user->getCustomerAdvertisementsView()->contains($advertisement);
        if($isAlreadyViewed){
            return ["message"=>"Advertisement already viewed", "success"=>0,"status"=>200];
        }

        $user->addCustomerAdvertisementsView($advertisement);

        $em->persist($user);
        $em->flush();

        return ["message"=>"Advertisement added", "success"=>1,"status"=>200];
    }

/**
     * @Rest\View()
     * @Rest\Get("/user/byemail/{email}")
     * @ApiDoc(
     *      section="User",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when store chain manager not found"
     *     },
     *     description="Find store chain manager by email",
     *
     *     requirements={
     *          {"name"="email", "dataType"="string", "requirement"=true, "description"="the email of the store chain manager"}
     *      }
     * )
     */
    public function getStoreChainManagerAction($email)
    {
        $storeChainManager = $this->getDoctrine()->getRepository('ModelBundle:StoreChainManager')->findOneBy(array('email' =>$email));
        if (empty($storeChainManager)) {
            return  ['message' => 'Store Chain manager not found', 'status'=>Response::HTTP_NOT_FOUND, 'success' => '0'];
        }
        
        return array('storeChainManager'=>$storeChainManager, 'message' => 'Store Chain manager found', 'status'=> 200, 'success' => '1');
    }


}
