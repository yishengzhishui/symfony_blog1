<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('home', [
            'route' => 'homepage',
            'label' => '首页',
        ]);

        $menu->addChild('blog', [
            'route' => 'blog_index',
            'label' => '群组'
        ]);

        $menu->addChild('register', [
            'route' => 'fos_user_registration_register',
            'label' => '注册',
        ]);

        $menu->addChild('login', [
            'route' => 'fos_user_security_login',
            'label' => '登录',
        ]);

        $menu->addChild('test', [
           'route' => 'blog_test',
           'label' => '搜索',
        ]);

        return $menu;
    }
}