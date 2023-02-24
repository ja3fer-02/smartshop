<?php

namespace ModelBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ModelBundle\Entity\Admin;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new Admin();
       $userAdmin->setFirstname('admin');
        $userAdmin->setLastname('admin');
        $userAdmin->setPhoto('user.png');
        $userAdmin->setPhone('7777');
        $userAdmin->setPassword('admin@admin.com');
        $userAdmin->setActivated(true);
        $userAdmin->setVerifed(true);
        $userAdmin->setEmail('admin@admin.com');


        $manager->persist($userAdmin);
        $manager->flush();



        /*
			$loader = new Loader($manager);
$loader->addFixture(new LoadUserData());
$fixtures = $loader->getFixtures();
 var_dump($fixtures);
 $purger = new ORMPurger();
$executor = new ORMExecutor($manager, $purger);
$executor->execute($loader->getFixtures());
	
        */
    }
}
?>