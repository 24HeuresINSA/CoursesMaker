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

    /*
     * This tests needs a user "admin" / "admin"
     */
    public function testSuccessfullLogin()
    {
        $this
            ->request()
                ->GET('/login') // Symfony2 prevent login forms to be send from other pages
                    ->hasStatus(200)
                    ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                ->POST('/login_check', array('_username' => 'admin', '_password' => 'admin', '_target_path' => '/'))
                    ->hasStatus(302) // redirection
                ->GET('/') // go to accueil
                    ->hasStatus(200)
                    ->crawler
                        ->hasElement('.hero-unit') # main title, "hero" block
                        ->end()
                        ->hasElement('a')
                            ->withContent('admin')
                        ->end()
                        ->hasElement('a')
                            ->withContent('Infos Pratiques')
                        ->end()
                        ->hasElement('a')
                            ->withContent('Déconnexion')
                        ->end()
        ;
    }

    public function testWrongLogin()
    {
        $this
            ->request()
                ->GET('/login') // Symfony2 prevent login forms to be send from other pages
                    ->hasStatus(200)
                    ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                ->POST('/login_check', array('_username' => 'toto', '_password' => 'fghjkhgfdghjk', '_target_path' => '/'))
                    ->hasStatus(302) // redirection
                    ->crawler
                        ->hasElement('a')
                            ->withContent('http://localhost/login') // we are being redirected to login page
                        ->end()
                ->GET('/') // go to accueil
                    ->hasStatus(200)
                    ->crawler
                        ->hasElement('a')
                            ->withContent('Déconnexion')
                            ->exactly(0) // not!
                        ->end()
                        ->hasElement('a')
                            ->withContent('Connexion')
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

    public function testAccessDenied()
    {
        $this
            ->request()
                ->GET('/admin/equipe')
                    ->hasStatus(302)
                    ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                    ->crawler
                        ->hasElement('a')
                            ->withContent('http://localhost/login') // we are being redirected to login page
                        ->end()
                ->GET('/admin/course')
                    ->hasStatus(302)
                    ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                    ->crawler
                        ->hasElement('a')
                            ->withContent('http://localhost/login') // we are being redirected to login page
                        ->end()
        ;
    }
}