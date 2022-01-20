<?php

namespace App\Tests\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ru/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Give your feedback!');
    }

    public function testCommentSubmission(): void
    {
        $email = 'nme@loopstation.uk';

        $client = static::createClient();
        $client->request('GET', '/ru/conference/voronezh-2022');
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Alex',
            'comment_form[text]' => 'Some feedback from an automated functional test',
            'comment_form[email]' => $email,
            'comment_form[photo]' => dirname(__DIR__, 2).'/public/images/under-construction.gif',
        ]);

        self::assertResponseRedirects();

        // simulate comment validation
        $comment = static::getContainer()->get(CommentRepository::class)->findOneByEmail($email);
        $comment->setState('published');
        static::getContainer()->get(EntityManagerInterface::class)->flush();

        $client->followRedirect();
        self::assertSelectorExists('div:contains("There are 2 comments")');
    }

    public function testConferencePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ru/');

        $this->assertCount(2, $crawler->filter('li'));

        $client->clickLink('View');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Voronezh');
        self::assertSelectorTextContains('h2', 'Voronezh 2022');
        self::assertSelectorExists('div:contains("There are 1 comments")');
    }
}
