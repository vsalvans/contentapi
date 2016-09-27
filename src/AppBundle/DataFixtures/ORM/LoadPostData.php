<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Post;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post1 = new Post();
        $post1->setTitle('title 1');
        $post1->setBody('body test 1');

        $post2 = new Post();
        $post2->setTitle('title 2');
        $post2->setBody('body test 2');

        $manager->persist($post1);
        $manager->persist($post2);
        $manager->flush();
    }
}
