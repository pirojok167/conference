<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Give your feedback!');
    }

    public function testConferencePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(2, $crawler->filter('li'));

        $client->clickLink('View');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Voronezh');
        self::assertSelectorTextContains('h2', 'Voronezh 2022');
        self::assertSelectorExists('div:contains("There are 1 comments")');
    }

    public function testCommentSubmission(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conference/voronezh-2022');
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Alex',
            'comment_form[text]' => 'Some feedback from an automated functional test',
            'comment_form[email]' => 'pirojok167@gmail.com',
            'comment_form[photo]' => dirname(__DIR__, 2).'/public/images/under-construction.gif',
        ]);

        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertSelectorExists('div:contains("There are 2 comments")');
    }
}
