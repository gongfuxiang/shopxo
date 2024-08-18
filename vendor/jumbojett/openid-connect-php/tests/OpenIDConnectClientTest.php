<?php

use Jumbojett\OpenIDConnectClient;
use Jumbojett\OpenIDConnectClientException;
use PHPUnit\Framework\MockObject\MockObject;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class OpenIDConnectClientTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetRedirectURL()
    {
        $client = new OpenIDConnectClient();

        self::assertSame('http:///', $client->getRedirectURL());

        $_SERVER['SERVER_NAME'] = 'domain.test';
        $_SERVER['REQUEST_URI'] = '/path/index.php?foo=bar&baz#fragment';
        self::assertSame('http://domain.test/path/index.php', $client->getRedirectURL());
    }

    public function testAuthenticateDoesNotThrowExceptionIfClaimsIsMissingNonce()
    {
        $fakeClaims = new StdClass();
        $fakeClaims->iss = 'fake-issuer';
        $fakeClaims->aud = 'fake-client-id';
        $fakeClaims->nonce = null;

        $_REQUEST['id_token'] = 'abc.123.xyz';
        $_REQUEST['state'] = false;
        $_SESSION['openid_connect_state'] = false;

        /** @var OpenIDConnectClient | MockObject $client */
        $client = $this->getMockBuilder(OpenIDConnectClient::class)->setMethods(['decodeJWT', 'getProviderConfigValue', 'verifyJWTSignature'])->getMock();
        $client->method('decodeJWT')->willReturn($fakeClaims);
        $client->method('getProviderConfigValue')->with('jwks_uri')->willReturn(true);
        $client->method('verifyJWTSignature')->willReturn(true);

        $client->setClientID('fake-client-id');
        $client->setIssuer('fake-issuer');
        $client->setIssuerValidator(function() {
            return true;
        });
        $client->setAllowImplicitFlow(true);
        $client->setProviderURL('https://jwt.io/');

        try {
            $authenticated = $client->authenticate();
            $this->assertTrue($authenticated);
        } catch ( OpenIDConnectClientException $e ) {
            if ( $e->getMessage() === 'Unable to verify JWT claims' ) {
                self::fail( 'OpenIDConnectClientException was thrown when it should not have been.' );
            }
        }
    }

    public function testSerialize()
    {
        $client = new OpenIDConnectClient('https://example.com', 'foo', 'bar', 'baz');
        $serialized = serialize($client);
        $this->assertInstanceOf(OpenIDConnectClient::class, unserialize($serialized));
    }

    /**
     * @dataProvider provider
     */
    public function testAuthMethodSupport($expected, $authMethod, $clientAuthMethods, $idpAuthMethods)
    {
        $client = new OpenIDConnectClient();
        if ($clientAuthMethods !== null) {
            $client->setTokenEndpointAuthMethodsSupported($clientAuthMethods);
        }
        $this->assertEquals($expected, $client->supportsAuthMethod($authMethod, $idpAuthMethods));
    }

    public function provider(): array
    {
        return [
            'client_secret_basic - default config' => [true, 'client_secret_basic', null, ['client_secret_basic']],

            'client_secret_jwt - default config' => [false, 'client_secret_jwt', null, ['client_secret_basic', 'client_secret_jwt']],
            'client_secret_jwt - explicitly enabled' => [true, 'client_secret_jwt', ['client_secret_jwt'], ['client_secret_basic', 'client_secret_jwt']],

            'private_key_jwt - default config' => [false, 'private_key_jwt', null, ['client_secret_basic', 'client_secret_jwt', 'private_key_jwt']],
            'private_key_jwt - explicitly enabled' => [true, 'private_key_jwt', ['private_key_jwt'], ['client_secret_basic', 'client_secret_jwt', 'private_key_jwt']],

        ];
    }

    /**
     * @covers       Jumbojett\\OpenIDConnectClient::verifyLogoutTokenClaims
     * @dataProvider provideTestVerifyLogoutTokenClaimsData
     * @throws OpenIDConnectClientException
     */
    public function testVerifyLogoutTokenClaims( $claims, $expectedResult )
    {
        /** @var OpenIDConnectClient | MockObject $client */
        $client = $this->getMockBuilder(OpenIDConnectClient::class)->setMethods(['decodeJWT'])->getMock();

        $client->setClientID('fake-client-id');
        $client->setIssuer('fake-issuer');
        $client->setIssuerValidator(function() {
            return true;
        });
        $client->setProviderURL('https://jwt.io/');

        $actualResult = $client->verifyLogoutTokenClaims( $claims );

        $this->assertEquals( $expectedResult, $actualResult );
    }

    /**
     * @return array
     */
    public function provideTestVerifyLogoutTokenClaimsData(): array
    {
        return [
            'valid-single-aud' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => 'fake-client-id',
                    'sid' => 'fake-client-sid',
                    'sub' => 'fake-client-sub',
                    'iat' => time(),
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ],
                ],
                true
            ],
            'valid-multiple-auds' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sid' => 'fake-client-sid',
                    'sub' => 'fake-client-sub',
                    'iat' => time(),
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ],
                ],
                true
            ],
            'invalid-no-sid-and-no-sub' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'iat' => time(),
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ],
                ],
                false
            ],
            'valid-no-sid' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sub' => 'fake-client-sub',
                    'iat' => time(),
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ],
                ],
                true
            ],
            'valid-no-sub' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sid' => 'fake-client-sid',
                    'iat' => time(),
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ],
                ],
                true
            ],
            'invalid-with-nonce' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sid' => 'fake-client-sid',
                    'iat' => time(),
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ],
                    'nonce' => 'must-not-be-set'
                ],
                false
            ],
            'invalid-no-events' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sid' => 'fake-client-sid',
                    'iat' => time(),
                    'nonce' => 'must-not-be-set'
                ],
                false
            ],
            'invalid-no-backchannel-event' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sid' => 'fake-client-sid',
                    'iat' => time(),
                    'events' => (object) [],
                    'nonce' => 'must-not-be-set'
                ],
                false
            ],
            'invalid-no-iat' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sid' => 'fake-client-sid',
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ]
                ],
                false
            ],
            'invalid-bad-iat' => [
                (object)[
                    'iss' => 'fake-issuer',
                    'aud' => [ 'fake-client-id', 'some-other-aud' ],
                    'sid' => 'fake-client-sid',
                    'iat' => time() + 301,
                    'events' => (object) [
                        'http://schemas.openid.net/event/backchannel-logout' => (object)[]
                    ]
                ],
                false
            ],
        ];
    }
}
