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
    public function testLoginPage()
    {
        $this
            ->request()
                ->GET('/login')
                ->hasStatus(200)
                ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                ->crawler
                    ->hasElement('#username')
                        ->hasNoContent()
                    ->end()
                    ->hasElement('#password')
                        ->hasNoContent()
                    ->end()
        ;
    }

    public function testSuccessfullLogin()
    {
        $this
            ->request(array('username' => 'admin', 'password' => 'admin')) // of course data-dependent!
                ->POST('/login')
                ->hasStatus(200)
                ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
        ;
    }

    public function testWrongLogin()
    {
        $this
            ->request(array('username' => 'toto', 'password' => 'ghjkjhvghj'))
                ->POST('/login')
                ->hasStatus(200)
                ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                ->crawler // back to the login page
                    ->hasElement('#username')
                        //->withAttribute('value', 'toto')
                    ->end()
                    ->hasElement('#password')
                        ->hasNoContent()
                    ->end()
        ;
    }

    public function testRegisterPage()
    {
        $this
            ->request()
                ->GET('/register')
                ->hasStatus(200)
                ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                ->crawler
                    ->hasElement('#registration_user_username')
                    ->end()
        ;
    }

    public function testAccessDeniedEquipe()
    {
        $this
            ->request()
                ->GET('/admin/equipe')
                    ->hasStatus(302)
                    ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
        ;
    }

    public function testAccessDeniedCourse()
    {
        $this
            ->request()
                ->GET('/admin/course')
                    ->hasStatus(302)
                    ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
        ;
    }
}