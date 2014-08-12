<?php

namespace spec\RyanNielson\Meta;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetaSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RyanNielson\Meta\Meta');
    }

    function it_sets_attributes_correctly()
    {
        $this->set(array('title' => 'Test Title', 'description' => 'Test Description'))->shouldBe(array('title' => 'Test Title', 'description' => 'Test Description'));
        $this->set(array('author' => 'Test Author'))->shouldBe(array('title' => 'Test Title', 'description' => 'Test Description', 'author' => 'Test Author'));
        $this->set(array('title' => 'Test Title 2'))->shouldBe(array('title' => 'Test Title 2', 'description' => 'Test Description', 'author' => 'Test Author'));
    }

    function it_sets_nested_attributes_correctly()
    {
        $this->set(array('title' => 'Test Title', 'og' => array('title' => 'OG Test Title', 'url' => 'http://example.com')))->shouldBe(array('title' => 'Test Title', 'og' => array('title' => 'OG Test Title', 'url' => 'http://example.com')));
        $this->set(array('title' => 'Test Title', 'og' => array('title' => 'OG Test Title 2')))->shouldBe(array('title' => 'Test Title', 'og' => array('title' => 'OG Test Title 2', 'url' => 'http://example.com')));
    }

    function it_clears_attributes_correctly()
    {
        $this->set(array('title' => 'Test Title', 'description' => 'Test Description'));
        $this->clear()->shouldBe(array());
        $this->getAttributes()->shouldHaveCount(0);
    }

    function it_gets_attributes()
    {
        $this->set(array('title' => 'Test Title', 'description' => 'Test Description'));
        $this->getAttributes()->shouldBeArray();
        $this->getAttributes()->shouldBe(array('title' => 'Test Title', 'description' => 'Test Description'));
    }

    function it_displays_meta_tags_correctly()
    {
        $this->set(array('title' => 'Test Title', 'description' => 'Test Description', 'keywords' => array('keyword1', 'keyword2')));
        $this->display()->shouldBe("<meta name=\"title\" content=\"Test Title\"/>\n<meta name=\"description\" content=\"Test Description\"/>\n<meta name=\"keywords\" content=\"keyword1, keyword2\"/>");

        $this->set(array('title' => 'Test Title', 'description' => 'Test Description', 'keywords' => 'keyword3, keyword4'));
        $this->display()->shouldBe("<meta name=\"title\" content=\"Test Title\"/>\n<meta name=\"description\" content=\"Test Description\"/>\n<meta name=\"keywords\" content=\"keyword3, keyword4\"/>");
    }

    function it_displays_og_tags_as_properties_instead_of_meta_names()
    {
        $this->set(array('og' => array('title' => 'OG Test Title', 'url' => 'http://example.com')));
        $this->display()->shouldBe("<meta property=\"og:title\" content=\"OG Test Title\"/>\n<meta property=\"og:url\" content=\"http://example.com\"/>");
    }

    function it_displays_nested_meta_tags_correctly()
    {
        $this->set(array('title' => 'Test Title', 'og' => array('title' => 'OG Test Title', 'url' => 'http://example.com')));
        $this->display()->shouldBe("<meta name=\"title\" content=\"Test Title\"/>\n<meta property=\"og:title\" content=\"OG Test Title\"/>\n<meta property=\"og:url\" content=\"http://example.com\"/>");
    }

    function it_displays_custom_meta_tags_correctly()
    {
        $this->set(array('title' => 'Test Title', 'custom1' => 'Test Custom 1', 'custom2' => 'Test Custom 2'));
        $this->display()->shouldBe("<meta name=\"title\" content=\"Test Title\"/>\n<meta name=\"custom1\" content=\"Test Custom 1\"/>\n<meta name=\"custom2\" content=\"Test Custom 2\"/>");

        $this->clear();
        $this->set(array('title' => 'Test Title', 'custom' => array('Test Custom 1', 'Test Custom 2')));
        $this->display()->shouldBe("<meta name=\"title\" content=\"Test Title\"/>\n<meta name=\"custom\" content=\"Test Custom 1\"/>\n<meta name=\"custom\" content=\"Test Custom 2\"/>");
    }

    function it_displays_meta_tags_with_defaults_correctly()
    {
        $this->set(array('title' => 'Test Title'));
        $this->display(array('description' => 'Test Description'))->shouldBe("<meta name=\"description\" content=\"Test Description\"/>\n<meta name=\"title\" content=\"Test Title\"/>");

        $this->set(array('title' => 'Test Title', 'description' => 'Test Description'));
        $this->display(array('description' => 'Test Description 2'))->shouldBe("<meta name=\"description\" content=\"Test Description\"/>\n<meta name=\"title\" content=\"Test Title\"/>");
    }

    function it_displays_meta_tags_and_title_tag()
    {
        $this->set(array('title' => 'Test Title', 'description' => 'Test Description'));
        $this->display(array(), true)->shouldBe("<meta name=\"title\" content=\"Test Title\"/>\n<meta name=\"description\" content=\"Test Description\"/>\n<title>Test Title</title>");
    }
}
