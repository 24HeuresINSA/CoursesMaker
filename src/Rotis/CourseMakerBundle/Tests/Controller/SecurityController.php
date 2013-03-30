<?php

namespace Rotis\CourseMakerBundle\Tests\Controller;

use atoum\AtoumBundle\Test\Units\WebTestCase;
use atoum\AtoumBundle\Test\Controller\ControllerTest;

/*
 * Here we will test for security: protected pages,
 * login for admins...
 */

class SecurityController extends ControllerTest
{
    public function testAccueil()
    {
        $this
            ->request(array('username' => 'toto'))
                ->GET('/login')
                ->hasStatus(200)
                ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                ->crawler
                    ->hasElement('#username')
                        ->hasNoContent('toto')
                    ->end()
                    ->hasElement('#password')
                        ->hasNoContent()
                    ->end()
        ;
    }
}