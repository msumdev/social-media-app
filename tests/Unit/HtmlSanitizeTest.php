<?php

namespace Tests\Unit;

use App\Services\SanitizationService;
use PHPUnit\Framework\TestCase;

class HtmlSanitizeTest extends TestCase
{
    public function test_blatant_xss_payload(): void
    {
        $service = new SanitizationService;
        $string = '<script>alert("XSS")</script>';
        $output = $service->sanitize($string);
        $this->assertEquals('', $output);
    }

    public function test_blatant_xss_payload_with_html_entities(): void
    {
        $service = new SanitizationService;
        $string = '&lt;script&gt;alert("XSS")&lt;/script&gt;';
        $output = $service->sanitize($string);
        $this->assertEquals('', $output);
    }

    public function test_blatant_xss_payload_with_double_encoded_html_entities(): void
    {
        $service = new SanitizationService;
        $string = '&amp;lt;script&amp;gt;alert("XSS")&amp;lt;/script&amp;gt;';
        $output = $service->sanitize($string);
        $this->assertEquals('', $output);
    }

    public function test_blatant_xss_payload_with_triple_encoded_html_entities(): void
    {
        $service = new SanitizationService;
        $string = '&amp;amp;lt;script&amp;amp;gt;alert("XSS")&amp;amp;lt;/script&amp;amp;gt;';
        $output = $service->sanitize($string);
        $this->assertEquals('', $output);
    }

    public function test_xss_payload_with_image_tag(): void
    {
        $service = new SanitizationService;
        $string = '<img src="https://example.com/image.jpg" />';
        $output = $service->sanitize($string);
        $this->assertEquals('', $output);
    }

    public function test_simple_allowed_html_html(): void
    {
        $service = new SanitizationService;
        $string = '<p>Hello, World!</p>';
        $output = $service->sanitize($string);
        $this->assertEquals($string, $output);
    }

    public function test_an_example_of_a_valid_post(): void
    {
        $service = new SanitizationService;
        $string = '<p>Hi there,</p><p><br /></p><p>How are you?</p><p><br /></p><p>I enjoyed speaking to <span class="badge mention">@jake</span></p><p><br /></p><p><span class="badge hashtag">#newfriends</span></p>';
        $output = $service->sanitize($string);
        $this->assertEquals($string, $output);
    }
}
