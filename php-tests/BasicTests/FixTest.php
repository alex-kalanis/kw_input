<?php

use kalanis\kw_input\Parsers;


class FixTest extends CommonTestClass
{
    /**
     * @param array<string, string> $vars
     * @param string $result
     * @dataProvider providerRequestIsapi
     */
    public function testRequestIsApi(array $vars, string $result): void
    {
        $data = Parsers\FixServer::updateVars($vars);
        $this->assertEquals($result, $data['REQUEST_URI']);
    }

    public function providerRequestIsApi(): array
    {
        return [
            [[
                'HTTP_X_ORIGINAL_URL' => 'something',
            ], 'something'],
            [[
                'HTTP_X_REWRITE_URL' => 'anything',
            ], 'anything'],
            [[
                'PATH_INFO' => 'whatToKnow',
            ], 'whatToKnow'],
            [[
                'ORIG_PATH_INFO' => 'whatToKnow',
            ], 'whatToKnow'],
            [[
                'PATH_INFO' => 'samePath',
                'SCRIPT_NAME' => 'samePath',
            ], 'samePath'],
            [[
                'PATH_INFO' => 'SamePath',
                'SCRIPT_NAME' => 'Different',
            ], 'DifferentSamePath'],
            [[
                'PATH_INFO' => 'whatToKnow',
                'QUERY_STRING' => 'extraKnowledge',
            ], 'whatToKnow?extraKnowledge'],
        ];
    }

    /**
     * @param array<string, string> $vars
     * @param string $result
     * @dataProvider providerScriptFileName
     */
    public function testScriptFileName(array $vars, string $result): void
    {
        $data = Parsers\FixServer::updateVars($vars);
        $this->assertEquals($result, $data['SCRIPT_FILENAME']);
    }

    public function providerScriptFileName(): array
    {
        return [
            [[
                'SCRIPT_FILENAME' => 'php.cgi',
                'PATH_TRANSLATED' => 'something',
            ], 'something'],
            [[
                'SCRIPT_FILENAME' => 'different',
                'PATH_TRANSLATED' => 'something',
            ], 'different'],
        ];
    }

    /**
     * @param array<string, string> $vars
     * @param bool $result
     * @dataProvider providerScriptName
     */
    public function testScriptName(array $vars, bool $result): void
    {
        $data = Parsers\FixServer::updateVars($vars);
        $this->assertEquals($result, isset($data['PATH_INFO']));
    }

    public function providerScriptName(): array
    {
        return [
            [[
                'SCRIPT_NAME' => 'php.cgi',
                'PATH_INFO' => 'something',
            ], false],
            [[
                'SCRIPT_NAME' => 'different',
                'PATH_INFO' => 'something',
            ], true],
        ];
    }

    /**
     * @param array<string, string> $vars
     * @param string $result
     * @dataProvider providerRequestUrlSelf
     */
    public function testRequestUrlSelf(array $vars, string $result): void
    {
        $data = Parsers\FixServer::updateVars($vars);
        $this->assertEquals($result, $data['PHP_SELF']);
    }

    public function providerRequestUrlSelf(): array
    {
        return [
            [['REQUEST_URI' => '//something',], '//something'],
            [['PHP_SELF' => '//something',], '//something'],
            [['PHP_SELF' => 'different', 'REQUEST_URI' => '//something',], 'different'],
        ];
    }

    /**
     * @param array<string, string> $vars
     * @param string $user
     * @param string $pw
     * @dataProvider providerAuthUpdExists
     */
    public function testAuthUpdExists(array $vars, string $user, string $pw): void
    {
        $data = Parsers\FixServer::updateAuth($vars);
        $this->assertEquals($user, $data['PHP_AUTH_USER']);
        $this->assertEquals($pw, $data['PHP_AUTH_PW']);
    }

    public function providerAuthUpdExists(): array
    {
        return [
            [[
                'HTTP_AUTHORIZATION' => 'somewhere',
                'PHP_AUTH_USER' => 'something',
                'PHP_AUTH_PW' => 'oh-no-no',
            ], 'something', 'oh-no-no', ],
            [[
                'HTTP_AUTHORIZATION' => 'Basic c29tZXRoaW5nOndpdGhvdXQtc2Vuc2U=',
            ], 'something', 'without-sense', ],
        ];
    }

    /**
     * @param array<string, string> $vars
     * @dataProvider providerAuthUpdNotExists
     */
    public function testAuthUpdNotExists(array $vars): void
    {
        $data = Parsers\FixServer::updateAuth($vars);
        $this->assertFalse(isset($data['PHP_AUTH_USER']));
        $this->assertFalse(isset($data['PHP_AUTH_PW']));
    }

    public function providerAuthUpdNotExists(): array
    {
        return [
            [[
                'HTTP_X_ORIGINAL_URL' => 'something',
            ], ],
            [[
                'HTTP_AUTHORIZATION' => 'not-a-header',
            ], ],
            [[
                'REDIRECT_HTTP_AUTHORIZATION' => 'not-a-header',
            ], ],
            [[
                'HTTP_AUTHORIZATION' => 'Basic c29tZXRoaW5nLXdpdGhvdXQtc2Vuc2U=',
            ], ],
        ];
    }
}
