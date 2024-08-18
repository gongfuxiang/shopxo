<?php
/**
 *
 * Copyright MITRE 2020
 *
 * OpenIDConnectClient for PHP5
 * Author: Michael Jett <mjett@mitre.org>
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 */

namespace Jumbojett;


use Error;
use Exception;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Math\BigInteger;
use stdClass;
use function bin2hex;
use function is_object;
use function random_bytes;

/**
 * A wrapper around base64_decode which decodes Base64URL-encoded data,
 * which is not the same alphabet as base64.
 * @param string $base64url
 * @return bool|string
 */
function base64url_decode(string $base64url) {
    return base64_decode(b64url2b64($base64url));
}

/**
 * Per RFC4648, "base64 encoding with URL-safe and filename-safe
 * alphabet".  This just replaces characters 62 and 63.  None of the
 * reference implementations seem to restore the padding if necessary,
 * but we'll do it anyway.
 * @param string $base64url
 * @return string
 */
function b64url2b64(string $base64url): string
{
    // "Shouldn't" be necessary, but why not
    $padding = strlen($base64url) % 4;
    if ($padding > 0) {
        $base64url .= str_repeat('=', 4 - $padding);
    }
    return strtr($base64url, '-_', '+/');
}


/**
 * OpenIDConnect Exception Class
 */
class OpenIDConnectClientException extends Exception
{
}

/**
 *
 * Please note this class stores nonces by default in $_SESSION['openid_connect_nonce']
 *
 */
class OpenIDConnectClient
{

    /**
     * @var string arbitrary id value
     */
    private $clientID;

    /**
     * @var string arbitrary name value
     */
    private $clientName;

    /**
     * @var string arbitrary secret value
     */
    private $clientSecret;

    /**
     * @var array holds the provider configuration
     */
    private $providerConfig = [];

    /**
     * @var string http proxy if necessary
     */
    private $httpProxy;

    /**
     * @var string full system path to the SSL certificate
     */
    private $certPath;

    /**
     * @var bool Verify SSL peer on transactions
     */
    private $verifyPeer = true;

    /**
     * @var bool Verify peer hostname on transactions
     */
    private $verifyHost = true;

    /**
     * @var string if we acquire an access token it will be stored here
     */
    protected $accessToken;

    /**
     * @var string if we acquire a refresh token it will be stored here
     */
    private $refreshToken;

    /**
     * @var string if we acquire an id token it will be stored here
     */
    protected $idToken;

    /**
     * @var object stores the token response
     */
    private $tokenResponse;

    /**
     * @var array holds scopes
     */
    private $scopes = [];

    /**
     * @var int|null Response code from the server
     */
    private $responseCode;

    /**
     * @var string|null Content type from the server
     */
    private $responseContentType;

    /**
     * @var array holds response types
     */
    private $responseTypes = [];

    /**
     * @var array holds authentication parameters
     */
    private $authParams = [];

    /**
     * @var array holds additional registration parameters for example post_logout_redirect_uris
     */
    private $registrationParams = [];

    /**
     * @var mixed holds well-known openid server properties
     */
    private $wellKnown = false;

    /**
     * @var mixed holds well-known openid configuration parameters, like policy for MS Azure AD B2C User Flow
     * @see https://docs.microsoft.com/en-us/azure/active-directory-b2c/user-flow-overview 
     */
    private $wellKnownConfigParameters = [];

    /**
     * @var int timeout (seconds)
     */
    protected $timeOut = 60;

    /**
     * @var int leeway (seconds)
     */
    private $leeway = 300;

    /**
     * @var array holds response types
     */
    private $additionalJwks = [];

    /**
     * @var object holds verified jwt claims
     */
    protected $verifiedClaims = [];

    /**
     * @var callable|null validator function for issuer claim
     */
    private $issuerValidator;

    /**
     * @var callable|null generator function for private key jwt client authentication
     */
    private $privateKeyJwtGenerator;

    /**
     * @var bool Allow OAuth 2 implicit flow; see http://openid.net/specs/openid-connect-core-1_0.html#ImplicitFlowAuth
     */
    private $allowImplicitFlow = false;

    /**
     * @var string
     */
    private $redirectURL;

    /**
     * @var int defines which URL-encoding http_build_query() uses
     */
    protected $encType = PHP_QUERY_RFC1738;

    /**
     * @var bool Enable or disable upgrading to HTTPS by paying attention to HTTP header HTTP_UPGRADE_INSECURE_REQUESTS
     */
    protected $httpUpgradeInsecureRequests = true;

    /**
     * @var string holds code challenge method for PKCE mode
     * @see https://tools.ietf.org/html/rfc7636
     */
    private $codeChallengeMethod = false;

    /**
     * @var array holds PKCE supported algorithms
     */
    private $pkceAlgs = ['S256' => 'sha256', 'plain' => false];

    /**
     * @var string if we acquire a sid in back-channel logout it will be stored here
     */
    private $backChannelSid;

    /**
     * @var string if we acquire a sub in back-channel logout it will be stored here
     */
    private $backChannelSubject;

    /**
     * @var array list of supported auth methods
     */
    private $token_endpoint_auth_methods_supported = ['client_secret_basic'];

    /**
     * @param string|null $provider_url optional
     * @param string|null $client_id optional
     * @param string|null $client_secret optional
     * @param string|null $issuer
     */
    public function __construct(string $provider_url = null, string $client_id = null, string $client_secret = null, string $issuer = null) {
        $this->setProviderURL($provider_url);
        if ($issuer === null) {
            $this->setIssuer($provider_url);
        } else {
            $this->setIssuer($issuer);
        }

        $this->clientID = $client_id;
        $this->clientSecret = $client_secret;
    }

    /**
     * @param $provider_url
     */
    public function setProviderURL($provider_url) {
        $this->providerConfig['providerUrl'] = $provider_url;
    }

    /**
     * @param $issuer
     */
    public function setIssuer($issuer) {
        $this->providerConfig['issuer'] = $issuer;
    }

    /**
     * @param $response_types
     */
    public function setResponseTypes($response_types) {
        $this->responseTypes = array_merge($this->responseTypes, (array)$response_types);
    }

    /**
     * @return bool
     * @throws OpenIDConnectClientException
     */
    public function authenticate(): bool
    {
        // Do a preemptive check to see if the provider has thrown an error from a previous redirect
        if (isset($_REQUEST['error'])) {
            $desc = isset($_REQUEST['error_description']) ? ' Description: ' . $_REQUEST['error_description'] : '';
            throw new OpenIDConnectClientException('Error: ' . $_REQUEST['error'] .$desc);
        }

        // If we have an authorization code then proceed to request a token
        if (isset($_REQUEST['code'])) {

            $code = $_REQUEST['code'];
            $token_json = $this->requestTokens($code);

            // Throw an error if the server returns one
            if (isset($token_json->error)) {
                if (isset($token_json->error_description)) {
                    throw new OpenIDConnectClientException($token_json->error_description);
                }
                throw new OpenIDConnectClientException('Got response: ' . $token_json->error);
            }

            // Do an OpenID Connect session check
	    if (!isset($_REQUEST['state']) || ($_REQUEST['state'] !== $this->getState())) {
                throw new OpenIDConnectClientException('Unable to determine state');
            }

            // Cleanup state
            $this->unsetState();

            if (!property_exists($token_json, 'id_token')) {
                throw new OpenIDConnectClientException('User did not authorize openid scope.');
            }

            $id_token = $token_json->id_token;
            $idTokenHeaders = $this->decodeJWT($id_token);
            if (isset($idTokenHeaders->enc)) {
                // Handle JWE
                $id_token = $this->handleJweResponse($id_token);
            }

            $claims = $this->decodeJWT($id_token, 1);

            // Verify the signature
            $this->verifySignatures($id_token);

            // Save the id token
            $this->idToken = $id_token;

            // Save the access token
            $this->accessToken = $token_json->access_token;

            // If this is a valid claim
            if ($this->verifyJWTClaims($claims, $token_json->access_token)) {

                // Clean up the session a little
                $this->unsetNonce();

                // Save the full response
                $this->tokenResponse = $token_json;

                // Save the verified claims
                $this->verifiedClaims = $claims;

                // Save the refresh token, if we got one
                if (isset($token_json->refresh_token)) {
                    $this->refreshToken = $token_json->refresh_token;
                }

                // Success!
                return true;
            }

            throw new OpenIDConnectClientException ('Unable to verify JWT claims');
        }

        if ($this->allowImplicitFlow && isset($_REQUEST['id_token'])) {
            // if we have no code but an id_token use that
            $id_token = $_REQUEST['id_token'];

            $accessToken = $_REQUEST['access_token'] ?? null;

            // Do an OpenID Connect session check
	    if (!isset($_REQUEST['state']) || ($_REQUEST['state'] !== $this->getState())) {            
                throw new OpenIDConnectClientException('Unable to determine state');
            }

            // Cleanup state
            $this->unsetState();

            $claims = $this->decodeJWT($id_token, 1);

            // Verify the signature
            $this->verifySignatures($id_token);

            // Save the id token
            $this->idToken = $id_token;

            // If this is a valid claim
            if ($this->verifyJWTClaims($claims, $accessToken)) {

                // Clean up the session a little
                $this->unsetNonce();

                // Save the verified claims
                $this->verifiedClaims = $claims;

                // Save the access token
                if ($accessToken) {
                    $this->accessToken = $accessToken;
                }

                // Success!
                return true;

            }

            throw new OpenIDConnectClientException ('Unable to verify JWT claims');
        }

        $this->requestAuthorization();
        return false;
    }

    /**
     * It calls the end-session endpoint of the OpenID Connect provider to notify the OpenID
     * Connect provider that the end-user has logged out of the relying party site
     * (the client application).
     *
     * @param string $idToken ID token (obtained at login)
     * @param string|null $redirect URL to which the RP is requesting that the End-User's User Agent
     * be redirected after a logout has been performed. The value MUST have been previously
     * registered with the OP. Value can be null.
     *
     * @throws OpenIDConnectClientException
     */
    public function signOut(string $idToken, $redirect) {
        $sign_out_endpoint = $this->getProviderConfigValue('end_session_endpoint');

        if($redirect === null){
            $signout_params = ['id_token_hint' => $idToken];
        }
        else {
            $signout_params = [
                'id_token_hint' => $idToken,
                'post_logout_redirect_uri' => $redirect];
        }

        $sign_out_endpoint  .= (strpos($sign_out_endpoint, '?') === false ? '?' : '&') . http_build_query( $signout_params, '', '&', $this->encType);
        $this->redirect($sign_out_endpoint);
    }


    /**
     * Decode and then verify a logout token sent as part of
     * back-channel logout flows.
     *
     * This function should be evaluated as a boolean check
     * in your route that receives the POST request for back-channel
     * logout executed from the OP.
     *
     * @return bool
     * @throws OpenIDConnectClientException
     */
    public function verifyLogoutToken(): bool
    {
        if (isset($_REQUEST['logout_token'])) {
            $logout_token = $_REQUEST['logout_token'];

            $claims = $this->decodeJWT($logout_token, 1);

            // Verify the signature
            if (!$this->getProviderConfigValue('jwks_uri')) {
                throw new OpenIDConnectClientException('Back-channel logout: Unable to verify signature due to no jwks_uri being defined');
            }
            if (!$this->verifyJWTSignature($logout_token)) {
                throw new OpenIDConnectClientException('Back-channel logout: Unable to verify JWT signature');
            }

            // Verify Logout Token Claims
            if ($this->verifyLogoutTokenClaims($claims)) {
                $this->verifiedClaims = $claims;
                return true;
            }

            return false;
        }

        throw new OpenIDConnectClientException('Back-channel logout: There was no logout_token in the request');
    }

    /**
     * Verify each claim in the logout token according to the
     * spec for back-channel logout.
     *
     * @param object $claims
     * @return bool
     * @throws OpenIDConnectClientException
     */
    public function verifyLogoutTokenClaims($claims): bool
    {
        // Verify that the Logout Token doesn't contain a nonce Claim.
        if (isset($claims->nonce)) {
            return false;
        }

        // Verify that the logout token contains a sub or sid, or both
        if (!isset($claims->sid) && !isset($claims->sub)) {
            return false;
        }
        // Set the sid, which could be used to map to a session in
        // the RP, and therefore be used to help destroy the RP's
        // session.
        if (isset($claims->sid)) {
            $this->backChannelSid = $claims->sid;
        }

        // Set the sub, which could be used to map to a session in
        // the RP, and therefore be used to help destroy the RP's
        // session.
        if (isset($claims->sub)) {
            $this->backChannelSubject = $claims->sub;
        }

        // Verify that the Logout Token contains an events Claim whose
        // value is a JSON object containing the member name
        // http://schemas.openid.net/event/backchannel-logout
        if (isset($claims->events)) {
            $events = (array) $claims->events;
            if (!isset($events['http://schemas.openid.net/event/backchannel-logout']) ||
                !is_object($events['http://schemas.openid.net/event/backchannel-logout'])) {
                return false;
            }
        }

        // Validate the iss
        if (!$this->validateIssuer($claims->iss)) {
            return false;
        }
        // Validate the aud
        $auds = $claims->aud;
        $auds = is_array( $auds ) ? $auds : [ $auds ];
        if (!in_array($this->clientID, $auds, true)) {
            return false;
        }
        // Validate the iat. At this point we can return true if it is ok
        if (isset($claims->iat) && ((is_int($claims->iat)) && ($claims->iat <= time() + $this->leeway))) {
            return true;
        }

        return false;
    }

    /**
     * @param array $scope - example: openid, given_name, etc...
     */
    public function addScope(array $scope) {
        $this->scopes = array_merge($this->scopes, $scope);
    }

    /**
     * @param array $param - example: prompt=login
     */
    public function addAuthParam(array $param) {
        $this->authParams = array_merge($this->authParams, $param);
    }

    /**
     * @param array $param - example: post_logout_redirect_uris=[http://example.com/successful-logout]
     */
    public function addRegistrationParam(array $param) {
        $this->registrationParams = array_merge($this->registrationParams, $param);
    }

    public function setTokenEndpointAuthMethodsSupported(array $token_endpoint_auth_methods_supported)
    {
        $this->token_endpoint_auth_methods_supported = $token_endpoint_auth_methods_supported;
    }

    /**
     * @param $jwk object - example: (object) ['kid' => ..., 'nbf' => ..., 'use' => 'sig', 'kty' => "RSA", 'e' => "", 'n' => ""]
     */
    protected function addAdditionalJwk($jwk) {
        $this->additionalJwks[] = $jwk;
    }

    /**
     * Gets anything that we need configuration wise including endpoints, and other values
     *
     * @param string $param
     * @param string|string[]|bool|null $default optional
     * @return string|string[]|bool
     *
     * @throws OpenIDConnectClientException
     */
    protected function getProviderConfigValue(string $param, $default = null)
    {
        // If the configuration value is not available, attempt to fetch it from a well known config endpoint
        // This is also known as auto "discovery"
        if (!isset($this->providerConfig[$param])) {
            $this->providerConfig[$param] = $this->getWellKnownConfigValue($param, $default);
        }

        return $this->providerConfig[$param];
    }

    /**
     * Gets anything that we need configuration wise including endpoints, and other values
     *
     * @param string $param
     * @param string|string[]|bool|null $default optional
     * @return string|string[]|bool
     *
     * @throws OpenIDConnectClientException
     */
    protected function getWellKnownConfigValue(string $param, $default = null)
    {

        // If the configuration value is not available, attempt to fetch it from a well known config endpoint
        // This is also known as auto "discovery"
        if(!$this->wellKnown) {
            $well_known_config_url = rtrim($this->getProviderURL(), '/') . '/.well-known/openid-configuration';
            if (count($this->wellKnownConfigParameters) > 0){
                $well_known_config_url .= '?' .  http_build_query($this->wellKnownConfigParameters) ;
            }
            $this->wellKnown = json_decode($this->fetchURL($well_known_config_url), false);
        }

        $value = $this->wellKnown->{$param} ?? false;

        if ($value) {
            return $value;
        }

        if (isset($default)) {
            // Uses default value if provided
            return $default;
        }

        throw new OpenIDConnectClientException("The provider $param could not be fetched. Make sure your provider has a well known configuration available.");
    }

    /**
     * Set optional parameters for .well-known/openid-configuration
     */
    public function setWellKnownConfigParameters(array $params = []){
        $this->wellKnownConfigParameters=$params;
    }


    /**
     * @param string $url Sets redirect URL for auth flow
     */
    public function setRedirectURL(string $url) {
        if (parse_url($url,PHP_URL_HOST) !== false) {
            $this->redirectURL = $url;
        }
    }

    /**
     * Gets the URL of the current page we are on, encodes, and returns it
     *
     * @return string
     */
    public function getRedirectURL(): string
    {
        // If the redirect URL has been set then return it.
        if (property_exists($this, 'redirectURL') && $this->redirectURL) {
            return $this->redirectURL;
        }

        // Other-wise return the URL of the current page

        /**
         * Thank you
         * http://stackoverflow.com/questions/189113/how-do-i-get-current-page-full-url-in-php-on-a-windows-iis-server
         */

        /*
         * Compatibility with multiple host headers.
         * The problem with SSL over port 80 is resolved and non-SSL over port 443.
         * Support of 'ProxyReverse' configurations.
         */

        if ($this->httpUpgradeInsecureRequests && isset($_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS']) && ($_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] === '1')) {
            $protocol = 'https';
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
        } elseif (isset($_SERVER['REQUEST_SCHEME'])) {
            $protocol = $_SERVER['REQUEST_SCHEME'];
        } elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }
	    
        if (isset($_SERVER['HTTP_X_FORWARDED_PORT'])) {
            $port = (int)$_SERVER['HTTP_X_FORWARDED_PORT'];
        } elseif (isset($_SERVER['SERVER_PORT'])) {
            $port = $_SERVER['SERVER_PORT'];
        } elseif ($protocol === 'https') {
            $port = 443;
        } else {
            $port = 80;
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $host = explode(':', $_SERVER['HTTP_HOST'])[0];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'];
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            $host = $_SERVER['SERVER_ADDR'];
        } else {
            return 'http:///';
        }

        $port = (443 === $port) || (80 === $port) ? '' : ':' . $port;
	    
        $explodedRequestUri = isset($_SERVER['REQUEST_URI']) ? explode('?', $_SERVER['REQUEST_URI']) : [];
        return sprintf('%s://%s%s/%s', $protocol, $host, $port, trim(reset($explodedRequestUri), '/'));
    }

    /**
     * Used for arbitrary value generation for nonces and state
     *
     * @return string
     * @throws OpenIDConnectClientException
     */
    protected function generateRandString(): string
    {
        try {
            return bin2hex(random_bytes(16));
        } catch (Error $e) {
            throw new OpenIDConnectClientException('Random token generation failed.');
        } catch (Exception $e) {
            throw new OpenIDConnectClientException('Random token generation failed.');
        }
    }

    /**
     * Start Here
     * @return void
     * @throws OpenIDConnectClientException
     * @throws Exception
     */
    private function requestAuthorization() {

        $auth_endpoint = $this->getProviderConfigValue('authorization_endpoint');
        $response_type = 'code';

        // Generate and store a nonce in the session
        // The nonce is an arbitrary value
        $nonce = $this->setNonce($this->generateRandString());

        // State essentially acts as a session key for OIDC
        $state = $this->setState($this->generateRandString());

        $auth_params = array_merge($this->authParams, [
            'response_type' => $response_type,
            'redirect_uri' => $this->getRedirectURL(),
            'client_id' => $this->clientID,
            'nonce' => $nonce,
            'state' => $state,
            'scope' => 'openid'
        ]);

        // If the client has been registered with additional scopes
        if (count($this->scopes) > 0) {
            $auth_params = array_merge($auth_params, ['scope' => implode(' ', array_merge($this->scopes, ['openid']))]);
        }

        // If the client has been registered with additional response types
        if (count($this->responseTypes) > 0) {
            $auth_params = array_merge($auth_params, ['response_type' => implode(' ', $this->responseTypes)]);
        }

        // If the client supports Proof Key for Code Exchange (PKCE)
        $codeChallengeMethod = $this->getCodeChallengeMethod();
        if (!empty($codeChallengeMethod) && in_array($codeChallengeMethod, $this->getProviderConfigValue('code_challenge_methods_supported', []), true)) {
            $codeVerifier = bin2hex(random_bytes(64));
            $this->setCodeVerifier($codeVerifier);
            if (!empty($this->pkceAlgs[$codeChallengeMethod])) {
                $codeChallenge = rtrim(strtr(base64_encode(hash($this->pkceAlgs[$codeChallengeMethod], $codeVerifier, true)), '+/', '-_'), '=');
            } else {
                $codeChallenge = $codeVerifier;
            }
            $auth_params = array_merge($auth_params, [
                'code_challenge' => $codeChallenge,
                'code_challenge_method' => $codeChallengeMethod
            ]);
        }

        $auth_endpoint .= (strpos($auth_endpoint, '?') === false ? '?' : '&') . http_build_query($auth_params, '', '&', $this->encType);

        $this->commitSession();
        $this->redirect($auth_endpoint);
    }

    /**
     * Requests a client credentials token
     *
     * @throws OpenIDConnectClientException
     */
    public function requestClientCredentialsToken() {
        $token_endpoint = $this->getProviderConfigValue('token_endpoint');

        $headers = [];

        $grant_type = 'client_credentials';

        $post_data = [
            'grant_type'    => $grant_type,
            'client_id'     => $this->clientID,
            'client_secret' => $this->clientSecret,
            'scope'         => implode(' ', $this->scopes)
        ];

        // Convert token params to string format
        $post_params = http_build_query($post_data, '', '&', $this->encType);

        return json_decode($this->fetchURL($token_endpoint, $post_params, $headers), false);
    }

    /**
     * Requests a resource owner token
     * (Defined in https://tools.ietf.org/html/rfc6749#section-4.3)
     *
     * @param boolean $bClientAuth Indicates that the Client ID and Secret be used for client authentication
     * @return mixed
     * @throws OpenIDConnectClientException
     */
    public function requestResourceOwnerToken(bool $bClientAuth = false) {
        $token_endpoint = $this->getProviderConfigValue('token_endpoint');

        $headers = [];

        $grant_type = 'password';

        $post_data = [
            'grant_type'    => $grant_type,
            'username'      => $this->authParams['username'],
            'password'      => $this->authParams['password'],
            'scope'         => implode(' ', $this->scopes)
        ];

        //For client authentication include the client values
        if($bClientAuth) {
            $token_endpoint_auth_methods_supported = $this->getProviderConfigValue('token_endpoint_auth_methods_supported', ['client_secret_basic']);
            if ($this->supportsAuthMethod('client_secret_basic', $token_endpoint_auth_methods_supported)) {
                $headers = ['Authorization: Basic ' . base64_encode(urlencode($this->clientID) . ':' . urlencode($this->clientSecret))];
            } else {
                $post_data['client_id']     = $this->clientID;
                $post_data['client_secret'] = $this->clientSecret;
            }
        }

        // Convert token params to string format
        $post_params = http_build_query($post_data, '', '&', $this->encType);

        return json_decode($this->fetchURL($token_endpoint, $post_params, $headers), false);
    }


    /**
     * Requests ID and Access tokens
     *
     * @param string $code
     * @param string[] $headers Extra HTTP headers to pass to the token endpoint
     * @return mixed
     * @throws OpenIDConnectClientException
     */
    protected function requestTokens(string $code, array $headers = []) {
        $token_endpoint = $this->getProviderConfigValue('token_endpoint');
        $token_endpoint_auth_methods_supported = $this->getProviderConfigValue('token_endpoint_auth_methods_supported', ['client_secret_basic']);

        $grant_type = 'authorization_code';

        $token_params = [
            'grant_type' => $grant_type,
            'code' => $code,
            'redirect_uri' => $this->getRedirectURL(),
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret
        ];

        $authorizationHeader = null;
        # Consider Basic authentication if provider config is set this way
        if ($this->supportsAuthMethod('client_secret_basic', $token_endpoint_auth_methods_supported)) {
            $authorizationHeader = 'Authorization: Basic ' . base64_encode(urlencode($this->clientID) . ':' . urlencode($this->clientSecret));
            unset($token_params['client_secret'], $token_params['client_id']);
        }

        // When there is a private key jwt generator, and it is supported then use it as client authentication
        if ($this->privateKeyJwtGenerator !== null && $this->supportsAuthMethod('private_key_jwt', $token_endpoint_auth_methods_supported)) {
            $token_params['client_assertion_type'] = 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer';
            $token_params['client_assertion'] = $this->privateKeyJwtGenerator->__invoke($token_endpoint);
        }

        if ($this->supportsAuthMethod('client_secret_jwt', $token_endpoint_auth_methods_supported)) {
            $client_assertion_type = $this->getProviderConfigValue('client_assertion_type');

            if(isset($this->providerConfig['client_assertion'])){
                $client_assertion = $this->getProviderConfigValue('client_assertion');
            }
            else{
                $client_assertion = $this->getJWTClientAssertion($this->getProviderConfigValue('token_endpoint'));
            }

            $token_params['client_assertion_type'] = $client_assertion_type;
            $token_params['client_assertion'] = $client_assertion;
            unset($token_params['client_secret']);
    	}

        $ccm = $this->getCodeChallengeMethod();
        $cv = $this->getCodeVerifier();
        if (!empty($ccm) && !empty($cv)) {
            $cs = $this->getClientSecret();
            if (empty($cs)) {
                $authorizationHeader = null;
                unset($token_params['client_secret']);
            }
            $token_params = array_merge($token_params, [
                'client_id' => $this->clientID,
                'code_verifier' => $this->getCodeVerifier()
            ]);
        }

        // Convert token params to string format
        $token_params = http_build_query($token_params, '', '&', $this->encType);

        if (null !== $authorizationHeader) {
            $headers[] = $authorizationHeader;
        }

        $this->tokenResponse = json_decode($this->fetchURL($token_endpoint, $token_params, $headers), false);

        return $this->tokenResponse;
    }

    /**
     * Request RFC8693 Token Exchange
     * https://datatracker.ietf.org/doc/html/rfc8693
     *
     * @param string $subjectToken
     * @param string $subjectTokenType
     * @param string $audience
     * @return mixed
     * @throws OpenIDConnectClientException
     */
    public function requestTokenExchange(string $subjectToken, string $subjectTokenType, string $audience = '') {
        $token_endpoint = $this->getProviderConfigValue('token_endpoint');
        $token_endpoint_auth_methods_supported = $this->getProviderConfigValue('token_endpoint_auth_methods_supported', ['client_secret_basic']);
        $headers = [];
        $grant_type = 'urn:ietf:params:oauth:grant-type:token-exchange';

        $post_data = array(
            'grant_type'    => $grant_type,
            'subject_token_type' => $subjectTokenType,
            'subject_token' => $subjectToken,
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'scope'         => implode(' ', $this->scopes)
        );

        if (!empty($audience)) {
            $post_data['audience'] = $audience;
        }

        # Consider Basic authentication if provider config is set this way
        if ($this->supportsAuthMethod('client_secret_basic', $token_endpoint_auth_methods_supported)) {
            $headers = ['Authorization: Basic ' . base64_encode(urlencode($this->clientID) . ':' . urlencode($this->clientSecret))];
            unset($post_data['client_secret'], $post_data['client_id']);
        }

        // Convert token params to string format
        $post_params = http_build_query($post_data, null, '&', $this->encType);

        return json_decode($this->fetchURL($token_endpoint, $post_params, $headers), false);
    }


    /**
     * Requests Access token with refresh token
     *
     * @param string $refresh_token
     * @return mixed
     * @throws OpenIDConnectClientException
     */
    public function refreshToken(string $refresh_token) {
        $token_endpoint = $this->getProviderConfigValue('token_endpoint');
        $token_endpoint_auth_methods_supported = $this->getProviderConfigValue('token_endpoint_auth_methods_supported', ['client_secret_basic']);

        $headers = [];

        $grant_type = 'refresh_token';

        $token_params = [
            'grant_type' => $grant_type,
            'refresh_token' => $refresh_token,
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'scope'         => implode(' ', $this->scopes),
        ];

        # Consider Basic authentication if provider config is set this way
        if ($this->supportsAuthMethod('client_secret_basic', $token_endpoint_auth_methods_supported)) {
            $headers = ['Authorization: Basic ' . base64_encode(urlencode($this->clientID) . ':' . urlencode($this->clientSecret))];
            unset($token_params['client_secret'], $token_params['client_id']);
        }

        if ($this->supportsAuthMethod('client_secret_jwt', $token_endpoint_auth_methods_supported)) {
            $client_assertion_type = $this->getProviderConfigValue('client_assertion_type');
            $client_assertion = $this->getJWTClientAssertion($this->getProviderConfigValue('token_endpoint'));

            $token_params["grant_type"] = "urn:ietf:params:oauth:grant-type:token-exchange";
            $token_params["subject_token"] = $refresh_token;
            $token_params["audience"] = $this->clientID;
            $token_params["subject_token_type"] = "urn:ietf:params:oauth:token-type:refresh_token";
			$token_params["requested_token_type"] = "urn:ietf:params:oauth:token-type:access_token";
            $token_params['client_assertion_type']=$client_assertion_type;
            $token_params['client_assertion'] = $client_assertion;

            unset($token_params['client_secret'], $token_params['client_id']);
        }
        // Convert token params to string format
        $token_params = http_build_query($token_params, '', '&', $this->encType);

        $json = json_decode($this->fetchURL($token_endpoint, $token_params, $headers), false);

        if (isset($json->access_token)) {
            $this->accessToken = $json->access_token;
        }

        if (isset($json->refresh_token)) {
            $this->refreshToken = $json->refresh_token;
        }

        return $json;
    }

    /**
     * @throws OpenIDConnectClientException
     */
    private function getKeyForHeader(array $keys, stdClass $header) {
        foreach ($keys as $key) {
            if ($key->kty === 'RSA') {
                if (!isset($header->kid) || $key->kid === $header->kid) {
                    return $key;
                }
            } else if (isset($key->alg) && $key->alg === $header->alg && $key->kid === $header->kid) {
                return $key;
            }
        }
        if ($this->additionalJwks) {
            foreach ($this->additionalJwks as $key) {
                if ($key->kty === 'RSA') {
                    if (!isset($header->kid) || $key->kid === $header->kid) {
                        return $key;
                    }
                } else if (isset($key->alg) && $key->alg === $header->alg && $key->kid === $header->kid) {
                    return $key;
                }
            }
        }
        if (isset($header->kid)) {
            throw new OpenIDConnectClientException('Unable to find a key for (algorithm, kid):' . $header->alg . ', ' . $header->kid . ')');
        }

        throw new OpenIDConnectClientException('Unable to find a key for RSA');
    }

    /**
     * @throws OpenIDConnectClientException
     */
    private function verifyRSAJWTSignature(string $hashType, stdClass $key, $payload, $signature, $signatureType): bool
    {
        if (!(property_exists($key, 'n') && property_exists($key, 'e'))) {
            throw new OpenIDConnectClientException('Malformed key object');
        }

        $key = RSA::load([
            'publicExponent' => new BigInteger(base64_decode(b64url2b64($key->e)), 256),
            'modulus' => new BigInteger(base64_decode(b64url2b64($key->n)), 256),
            'isPublicKey' => true,
        ])
            ->withHash($hashType);
        if ($signatureType === 'PSS') {
            $key = $key->withMGFHash($hashType)
                ->withPadding(RSA::SIGNATURE_PSS);
        } else {
            $key = $key->withPadding(RSA::SIGNATURE_PKCS1);
        }
        return $key->verify($payload, $signature);
    }

    private function verifyHMACJWTSignature(string $hashType, string $key, string $payload, string $signature): bool
    {
        $expected = hash_hmac($hashType, $payload, $key, true);
        return hash_equals($signature, $expected);
    }

    /**
     * @param string $jwt encoded JWT
     * @return bool
     * @throws OpenIDConnectClientException
     */
    public function verifyJWTSignature(string $jwt): bool
    {
        $parts = explode('.', $jwt);
        if (!isset($parts[0])) {
            throw new OpenIDConnectClientException('Error missing part 0 in token');
        }
        $signature = base64url_decode(array_pop($parts));
        if (false === $signature || '' === $signature) {
            throw new OpenIDConnectClientException('Error decoding signature from token');
        }
        $header = json_decode(base64url_decode($parts[0]), false);
        if (!is_object($header)) {
            throw new OpenIDConnectClientException('Error decoding JSON from token header');
        }
        if (!isset($header->alg)) {
            throw new OpenIDConnectClientException('Error missing signature type in token header');
        }

        $payload = implode('.', $parts);
        switch ($header->alg) {
            case 'RS256':
            case 'PS256':
            case 'PS512':
            case 'RS384':
            case 'RS512':
                $hashType = 'sha' . substr($header->alg, 2);
                $signatureType = $header->alg === 'PS256' || $header->alg === 'PS512' ? 'PSS' : '';
                if (isset($header->jwk)) {
                    $jwk = $header->jwk;
                    $this->verifyJWKHeader($jwk);
                } else {
                    $jwks = json_decode($this->fetchURL($this->getProviderConfigValue('jwks_uri')), false);
                    if ($jwks === NULL) {
                        throw new OpenIDConnectClientException('Error decoding JSON from jwks_uri');
                    }
                    $jwk = $this->getKeyForHeader($jwks->keys, $header);
                }

                $verified = $this->verifyRSAJWTSignature($hashType,
                    $jwk,
                    $payload, $signature, $signatureType);
                break;
            case 'HS256':
            case 'HS512':
            case 'HS384':
                $hashType = 'SHA' . substr($header->alg, 2);
                $verified = $this->verifyHMACJWTSignature($hashType, $this->getClientSecret(), $payload, $signature);
                break;
            default:
                throw new OpenIDConnectClientException('No support for signature type: ' . $header->alg);
        }
        return $verified;
    }

    /**
     * @param string $jwt encoded JWT
     * @return void
     * @throws OpenIDConnectClientException
     */
    public function verifySignatures(string $jwt)
    {
        if (!$this->getProviderConfigValue('jwks_uri')) {
            throw new OpenIDConnectClientException ('Unable to verify signature due to no jwks_uri being defined');
        }
        if (!$this->verifyJWTSignature($jwt)) {
            throw new OpenIDConnectClientException ('Unable to verify signature');
        }
    }

    /**
     * @param string $iss
     * @return bool
     * @throws OpenIDConnectClientException
     */
    protected function validateIssuer(string $iss): bool
    {
        if ($this->issuerValidator !== null) {
            return $this->issuerValidator->__invoke($iss);
        }

        return ($iss === $this->getIssuer() || $iss === $this->getWellKnownIssuer() || $iss === $this->getWellKnownIssuer(true));
    }

    /**
     * @param object $claims
     * @param string|null $accessToken
     * @return bool
     * @throws OpenIDConnectClientException
     */
    protected function verifyJWTClaims($claims, string $accessToken = null): bool
    {
        if(isset($claims->at_hash, $accessToken)) {
            if(isset($this->getIdTokenHeader()->alg) && $this->getIdTokenHeader()->alg !== 'none') {
                $bit = substr($this->getIdTokenHeader()->alg, 2, 3);
            } else {
                // TODO: Error case. throw exception???
                $bit = '256';
            }
            $len = ((int)$bit)/16;
            $expected_at_hash = $this->urlEncode(substr(hash('sha'.$bit, $accessToken, true), 0, $len));
        }
        return (($this->validateIssuer($claims->iss))
            && (($claims->aud === $this->clientID) || in_array($this->clientID, $claims->aud, true))
            && (!isset($claims->nonce) || $claims->nonce === $this->getNonce())
            && ( !isset($claims->exp) || ((is_int($claims->exp)) && ($claims->exp >= time() - $this->leeway)))
            && ( !isset($claims->nbf) || ((is_int($claims->nbf)) && ($claims->nbf <= time() + $this->leeway)))
            && ( !isset($claims->at_hash) || !isset($accessToken) || $claims->at_hash === $expected_at_hash )
        );
    }

    protected function urlEncode(string $str): string
    {
        $enc = base64_encode($str);
        $enc = rtrim($enc, '=');
        return strtr($enc, '+/', '-_');
    }

    /**
     * @param string $jwt encoded JWT
     * @param int $section the section we would like to decode
     * @return object
     */
    protected function decodeJWT(string $jwt, int $section = 0): stdClass {

        $parts = explode('.', $jwt);
        return json_decode(base64url_decode($parts[$section]), false);
    }

    /**
     *
     * @param string|null $attribute optional
     *
     * Attribute        Type        Description
     * user_id          string      REQUIRED Identifier for the End-User at the Issuer.
     * name             string      End-User's full name in displayable form including all name parts, ordered according to End-User's locale and preferences.
     * given_name       string      Given name or first name of the End-User.
     * family_name      string      Surname or last name of the End-User.
     * middle_name      string      Middle name of the End-User.
     * nickname         string      Casual name of the End-User that may or may not be the same as the given_name. For instance, a nickname value of Mike might be returned alongside a given_name value of Michael.
     * profile          string      URL of End-User's profile page.
     * picture          string      URL of the End-User's profile picture.
     * website          string      URL of End-User's web page or blog.
     * email            string      The End-User's preferred e-mail address.
     * verified         boolean     True if the End-User's e-mail address has been verified; otherwise false.
     * gender           string      The End-User's gender: Values defined by this specification are female and male. Other values MAY be used when neither of the defined values are applicable.
     * birthday         string      The End-User's birthday, represented as a date string in MM/DD/YYYY format. The year MAY be 0000, indicating that it is omitted.
     * zoneinfo         string      String from zoneinfo [zoneinfo] time zone database. For example, Europe/Paris or America/Los_Angeles.
     * locale           string      The End-User's locale, represented as a BCP47 [RFC5646] language tag. This is typically an ISO 639-1 Alpha-2 [ISO639‑1] language code in lowercase and an ISO 3166-1 Alpha-2 [ISO3166‑1] country code in uppercase, separated by a dash. For example, en-US or fr-CA. As a compatibility note, some implementations have used an underscore as the separator rather than a dash, for example, en_US; Implementations MAY choose to accept this locale syntax as well.
     * phone_number     string      The End-User's preferred telephone number. E.164 [E.164] is RECOMMENDED as the format of this Claim. For example, +1 (425) 555-1212 or +56 (2) 687 2400.
     * address          JSON object The End-User's preferred address. The value of the address member is a JSON [RFC4627] structure containing some or all of the members defined in Section 2.4.2.1.
     * updated_time     string      Time the End-User's information was last updated, represented as an RFC 3339 [RFC3339] datetime. For example, 2011-01-03T23:58:42+0000.
     *
     * @return mixed
     *
     * @throws OpenIDConnectClientException
     */
    public function requestUserInfo(string $attribute = null) {

        $user_info_endpoint = $this->getProviderConfigValue('userinfo_endpoint');
        $schema = 'openid';

        $user_info_endpoint .= '?schema=' . $schema;

        //The accessToken has to be sent in the Authorization header.
        // Accept json to indicate response type
        $headers = ["Authorization: Bearer $this->accessToken",
            'Accept: application/json'];

        $response = $this->fetchURL($user_info_endpoint,null,$headers);
        if ($this->getResponseCode() !== 200) {
            throw new OpenIDConnectClientException('The communication to retrieve user data has failed with status code '.$this->getResponseCode());
        }

        // When we receive application/jwt, the UserInfo Response is signed and/or encrypted.
        if ($this->getResponseContentType() === 'application/jwt' ) {
            // Check if the response is encrypted
            $jwtHeaders = $this->decodeJWT($response);
            if (isset($jwtHeaders->enc)) {
                // Handle JWE
                $jwt = $this->handleJweResponse($response);
            } else {
                // If the response is not encrypted then it must be signed
                $jwt = $response;
            }

            // Verify the signature
            $this->verifySignatures($jwt);

            // Get claims from JWT
            $claims = $this->decodeJWT($jwt, 1);

            // Verify the JWT claims
            if (!$this->verifyJWTClaims($claims)) {
                throw new OpenIDConnectClientException('Invalid JWT signature');
            }

            $user_json = $claims;
        } else {
            $user_json = json_decode($response, false);
        }

        $userInfo = $user_json;

        if($attribute === null) {
            return $userInfo;
        }

        if (property_exists($userInfo, $attribute)) {
            return $userInfo->$attribute;
        }

        return null;
    }

    /**
     *
     * @param string|null $attribute optional
     *
     * Attribute        Type    Description
     * exp              int     Expires at
     * nbf              int     Not before
     * ver              string  Version
     * iss              string  Issuer
     * sub              string  Subject
     * aud              string  Audience
     * nonce            string  nonce
     * iat              int     Issued At
     * auth_time        int     Authentication time
     * oid              string  Object id
     *
     * @return mixed
     *
     */
    public function getVerifiedClaims(string $attribute = null) {

        if($attribute === null) {
            return $this->verifiedClaims;
        }

        if (property_exists($this->verifiedClaims, $attribute)) {
            return $this->verifiedClaims->$attribute;
        }

        return null;
    }

    /**
     * @param string $url
     * @param string | null $post_body string If this is set the post type will be POST
     * @param array $headers Extra headers to be sent with the request. Format as 'NameHeader: ValueHeader'
     * @return bool|string
     * @throws OpenIDConnectClientException
     */
    protected function fetchURL(string $url, string $post_body = null, array $headers = []) {

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();

        // Determine whether this is a GET or POST
        if ($post_body !== null) {
            // curl_setopt($ch, CURLOPT_POST, 1);
            // Allows to keep the POST method even after redirect
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);

            // Default content type is form encoded
            $content_type = 'application/x-www-form-urlencoded';

            // Determine if this is a JSON payload and add the appropriate content type
            if (is_object(json_decode($post_body, false))) {
                $content_type = 'application/json';
            }

            // Add POST-specific headers
            $headers[] = "Content-Type: $content_type";
        }

        // Set the User-Agent
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());

        // If we set some headers include them
        if(count($headers) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $url);

        if (isset($this->httpProxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $this->httpProxy);
        }

        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Allows to follow redirect
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        /**
         * Set cert
         * Otherwise ignore SSL peer verification
         */
        if (isset($this->certPath)) {
            curl_setopt($ch, CURLOPT_CAINFO, $this->certPath);
        }

        if($this->verifyHost) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        if($this->verifyPeer) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeOut);

        // Download the given URL, and return output
        $output = curl_exec($ch);

        // HTTP Response code from server may be required from subclass
        $info = curl_getinfo($ch);
        $this->responseCode = $info['http_code'];
        $this->responseContentType = $info['content_type'];

        if ($output === false) {
            throw new OpenIDConnectClientException('Curl error: (' . curl_errno($ch) . ') ' . curl_error($ch));
        }

        // Close the cURL resource, and free system resources
        curl_close($ch);

        return $output;
    }

    /**
     * @throws OpenIDConnectClientException
     */
    public function getWellKnownIssuer(bool $appendSlash = false): string
    {
        return $this->getWellKnownConfigValue('issuer') . ($appendSlash ? '/' : '');
    }

    /**
     * @throws OpenIDConnectClientException
     */
    public function getIssuer(): string
    {

        if (!isset($this->providerConfig['issuer'])) {
            throw new OpenIDConnectClientException('The issuer has not been set');
        }

        return $this->providerConfig['issuer'];
    }

    /**
     * @return mixed
     * @throws OpenIDConnectClientException
     */
    public function getProviderURL() {
        if (!isset($this->providerConfig['providerUrl'])) {
            throw new OpenIDConnectClientException('The provider URL has not been set');
        }

        return $this->providerConfig['providerUrl'];
    }

    public function redirect(string $url) {
        header('Location: ' . $url);
        exit;
    }

    public function setHttpProxy(string $httpProxy) {
        $this->httpProxy = $httpProxy;
    }

    public function setCertPath(string $certPath) {
        $this->certPath = $certPath;
    }

    /**
     * @return string|null
     */
    public function getCertPath() {
        return $this->certPath;
    }

    public function setVerifyPeer(bool $verifyPeer) {
        $this->verifyPeer = $verifyPeer;
    }

    public function setVerifyHost(bool $verifyHost) {
        $this->verifyHost = $verifyHost;
    }

    /**
     * Controls whether http header HTTP_UPGRADE_INSECURE_REQUESTS should be considered
     * defaults to true
     */
    public function setHttpUpgradeInsecureRequests(bool $httpUpgradeInsecureRequests) {
        $this->httpUpgradeInsecureRequests = $httpUpgradeInsecureRequests;
    }

    public function getVerifyHost(): bool
    {
        return $this->verifyHost;
    }

    public function getVerifyPeer(): bool
    {
        return $this->verifyPeer;
    }

    public function getHttpUpgradeInsecureRequests(): bool
    {
        return $this->httpUpgradeInsecureRequests;
    }

    /**
     * Use this for custom issuer validation
     * The given function should accept the issuer string from the JWT claim as the only argument
     * and return true if the issuer is valid, otherwise return false
     */
    public function setIssuerValidator(callable $issuerValidator) {
        $this->issuerValidator = $issuerValidator;
    }

    /**
     * Use this for private_key_jwt client authentication
     * The given function should accept the token_endpoint string as the only argument
     * and return a jwt signed with your private key according to:
     * https://openid.net/specs/openid-connect-core-1_0.html#ClientAuthentication
     */
    public function setPrivateKeyJwtGenerator(callable $privateKeyJwtGenerator) {
        $this->privateKeyJwtGenerator = $privateKeyJwtGenerator;
    }

    public function setAllowImplicitFlow(bool $allowImplicitFlow) {
        $this->allowImplicitFlow = $allowImplicitFlow;
    }

    /**
     * @return bool
     */
    public function getAllowImplicitFlow(): bool
    {
        return $this->allowImplicitFlow;
    }

    /**
     *
     * Use this to alter a provider's endpoints and other attributes
     *
     * @param array $array
     *        simple key => value
     */
    public function providerConfigParam(array $array) {
        $this->providerConfig = array_merge($this->providerConfig, $array);
    }

    public function setClientSecret(string $clientSecret) {
        $this->clientSecret = $clientSecret;
    }

    public function setClientID(string $clientID) {
        $this->clientID = $clientID;
    }


    /**
     * Dynamic registration
     *
     * @throws OpenIDConnectClientException
     */
    public function register() {

        $registration_endpoint = $this->getProviderConfigValue('registration_endpoint');

        $send_object = (object ) array_merge($this->registrationParams, [
            'redirect_uris' => [$this->getRedirectURL()],
            'client_name' => $this->getClientName()
        ]);

        $response = $this->fetchURL($registration_endpoint, json_encode($send_object));

        $json_response = json_decode($response, false);

        // Throw some errors if we encounter them
        if ($json_response === false) {
            throw new OpenIDConnectClientException('Error registering: JSON response received from the server was invalid.');
        }

        if (isset($json_response->{'error_description'})) {
            throw new OpenIDConnectClientException($json_response->{'error_description'});
        }

        $this->setClientID($json_response->{'client_id'});

        // The OpenID Connect Dynamic registration protocol makes the client secret optional
        // and provides a registration access token and URI endpoint if it is not present
        if (isset($json_response->{'client_secret'})) {
            $this->setClientSecret($json_response->{'client_secret'});
        } else {
            throw new OpenIDConnectClientException('Error registering: Please contact the OpenID Connect provider and obtain a Client ID and Secret directly from them');
        }
    }

    /**
     * Introspect a given token - either access token or refresh token.
     * @see https://tools.ietf.org/html/rfc7662
     *
     * @param string $token
     * @param string $token_type_hint
     * @param string|null $clientId
     * @param string|null $clientSecret
     * @return mixed
     * @throws OpenIDConnectClientException
     * @throws Exception
     */
    public function introspectToken(string $token, string $token_type_hint = '', string $clientId = null, string $clientSecret = null) {
        $introspection_endpoint = $this->getProviderConfigValue('introspection_endpoint');
        $token_endpoint_auth_methods_supported = $this->getProviderConfigValue('token_endpoint_auth_methods_supported', ['client_secret_basic']);

        $post_data = ['token' => $token];

        if ($token_type_hint) {
            $post_data['token_type_hint'] = $token_type_hint;
        }
        $clientId = $clientId ?? $this->clientID;
        $clientSecret = $clientSecret ?? $this->clientSecret;

        // Convert token params to string format
        $headers = ['Authorization: Basic ' . base64_encode(urlencode($clientId) . ':' . urlencode($clientSecret)),
            'Accept: application/json'];

        if ($this->supportsAuthMethod('client_secret_jwt', $token_endpoint_auth_methods_supported)) {
            $client_assertion_type = $this->getProviderConfigValue('client_assertion_type');
            $client_assertion = $this->getJWTClientAssertion($this->getProviderConfigValue('introspection_endpoint'));

            $post_data['client_assertion_type']=$client_assertion_type;
            $post_data['client_assertion'] = $client_assertion;
            $headers = ['Accept: application/json'];
        }

        $post_params = http_build_query($post_data, '', '&');

        return json_decode($this->fetchURL($introspection_endpoint, $post_params, $headers), false);
    }

    /**
     * Revoke a given token - either access token or refresh token.
     * @see https://tools.ietf.org/html/rfc7009
     *
     * @param string $token
     * @param string $token_type_hint
     * @param string|null $clientId
     * @param string|null $clientSecret
     * @return mixed
     * @throws OpenIDConnectClientException
     */
    public function revokeToken(string $token, string $token_type_hint = '', string $clientId = null, string $clientSecret = null) {
        $revocation_endpoint = $this->getProviderConfigValue('revocation_endpoint');

        $post_data = ['token' => $token];

        if ($token_type_hint) {
            $post_data['token_type_hint'] = $token_type_hint;
        }
        $clientId = $clientId ?? $this->clientID;
        $clientSecret = $clientSecret ?? $this->clientSecret;

        // Convert token params to string format
        $post_params = http_build_query($post_data, '', '&');
        $headers = ['Authorization: Basic ' . base64_encode(urlencode($clientId) . ':' . urlencode($clientSecret)),
            'Accept: application/json'];

        return json_decode($this->fetchURL($revocation_endpoint, $post_params, $headers), false);
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName) {
        $this->clientName = $clientName;
    }

    /**
     * @return string
     */
    public function getClientID() {
        return $this->clientID;
    }

    /**
     * @return string
     */
    public function getClientSecret() {
        return $this->clientSecret;
    }

    /**
     * Set the access token.
     *
     * May be required for subclasses of this Client.
     */
    public function setAccessToken(string $accessToken) {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getIdToken(): string
    {
        return $this->idToken;
    }

    /**
     * @return object
     */
    public function getAccessTokenHeader() {
        return $this->decodeJWT($this->accessToken);
    }

    /**
     * @return object
     */
    public function getAccessTokenPayload() {
        return $this->decodeJWT($this->accessToken, 1);
    }

    /**
     * @return object
     */
    public function getIdTokenHeader() {
        return $this->decodeJWT($this->idToken);
    }

    /**
     * @return object
     */
    public function getIdTokenPayload() {
        return $this->decodeJWT($this->idToken, 1);
    }

    /**
     * @return object
     */
    public function getTokenResponse() {
        return $this->tokenResponse;
    }

    /**
     * Stores nonce
     */
    protected function setNonce(string $nonce): string
    {
        $this->setSessionKey('openid_connect_nonce', $nonce);
        return $nonce;
    }

    /**
     * Get stored nonce
     *
     * @return string
     */
    protected function getNonce() {
        return $this->getSessionKey('openid_connect_nonce');
    }

    /**
     * Cleanup nonce
     *
     * @return void
     */
    protected function unsetNonce() {
        $this->unsetSessionKey('openid_connect_nonce');
    }

    /**
     * Stores $state
     */
    protected function setState(string $state): string
    {
        $this->setSessionKey('openid_connect_state', $state);
        return $state;
    }

    /**
     * Get stored state
     *
     * @return string
     */
    protected function getState() {
        return $this->getSessionKey('openid_connect_state');
    }

    /**
     * Cleanup state
     *
     * @return void
     */
    protected function unsetState() {
        $this->unsetSessionKey('openid_connect_state');
    }

    /**
     * Stores $codeVerifier
     */
    protected function setCodeVerifier(string $codeVerifier): string
    {
        $this->setSessionKey('openid_connect_code_verifier', $codeVerifier);
        return $codeVerifier;
    }

    /**
     * Get stored codeVerifier
     *
     * @return string
     */
    protected function getCodeVerifier() {
        return $this->getSessionKey('openid_connect_code_verifier');
    }

    /**
     * Cleanup state
     *
     * @return void
     */
    protected function unsetCodeVerifier() {
        $this->unsetSessionKey('openid_connect_code_verifier');
    }

    /**
     * Get the response code from last action/curl request.
     *
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * Get the content type from last action/curl request.
     *
     * @return string|null
     */
    public function getResponseContentType()
    {
        return $this->responseContentType;
    }

    /**
     * Set timeout (seconds)
     *
     * @param int $timeout
     */
    public function setTimeout(int $timeout) {
        $this->timeOut = $timeout;
    }

    public function getTimeout(): int
    {
        return $this->timeOut;
    }

    /**
     * Use session to manage a nonce
     */
    protected function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    }

    protected function commitSession() {
        $this->startSession();

        session_write_close();
    }

    protected function getSessionKey(string $key) {
        $this->startSession();

        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return false;
    }

    protected function setSessionKey(string $key, $value) {
        $this->startSession();

        $_SESSION[$key] = $value;
    }

    protected function unsetSessionKey(string $key) {
        $this->startSession();

        unset($_SESSION[$key]);
    }

    /**
     * @throws Exception
     */
    protected function getJWTClientAssertion($aud): string
    {
        $jti = hash('sha256',bin2hex(random_bytes(64)));

        $now = time();

		$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
		$payload = json_encode([
			'sub' => $this->getClientID(),
			'iss' => $this->getClientID(),
			'aud' => $aud,
			'jti' => $jti,
			'exp' => $now + 3600,
			'iat' => $now,
		]);
		// Encode Header to Base64Url String
		$base64UrlHeader = $this->urlEncode($header);


		// Encode Payload to Base64Url String
		$base64UrlPayload = $this->urlEncode($payload);

		// Create Signature Hash
		$signature = hash_hmac(
			'sha256',
			$base64UrlHeader . "." . $base64UrlPayload,
			$this->getClientSecret(),
			true
		);

		// Encode Signature to Base64Url String
		$base64UrlSignature = $this->urlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function setUrlEncoding($curEncoding) {
        switch ($curEncoding)
        {
            case PHP_QUERY_RFC1738:
                $this->encType = PHP_QUERY_RFC1738;
                break;

            case PHP_QUERY_RFC3986:
                $this->encType = PHP_QUERY_RFC3986;
                break;

        	default:
                break;
        }
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function getResponseTypes(): array
    {
        return $this->responseTypes;
    }

    public function getAuthParams(): array
    {
        return $this->authParams;
    }

    /**
     * @return callable
     */
    public function getIssuerValidator() {
        return $this->issuerValidator;
    }


    /**
     * @return callable
     */
    public function getPrivateKeyJwtGenerator() {
        return $this->privateKeyJwtGenerator;
    }

    public function getLeeway(): int
    {
        return $this->leeway;
    }

    /**
     * @return string
     */
    public function getCodeChallengeMethod() {
        return $this->codeChallengeMethod;
    }

    public function setCodeChallengeMethod(string $codeChallengeMethod) {
        $this->codeChallengeMethod = $codeChallengeMethod;
    }

    /**
     * @throws OpenIDConnectClientException
     */
    protected function verifyJWKHeader($jwk)
    {
        throw new OpenIDConnectClientException('Self signed JWK header is not valid');
    }

    /**
     * @param string $jwe The JWE to decrypt
     * @return string the JWT payload
     * @throws OpenIDConnectClientException
     */
    protected function handleJweResponse(string $jwe): string
    {
        throw new OpenIDConnectClientException('JWE response is not supported, please extend the class and implement this method');
    }

    public function getSidFromBackChannel(): string
    {
        return $this->backChannelSid;
    }

    public function getSubjectFromBackChannel(): string
    {
        return $this->backChannelSubject;
    }

    public function supportsAuthMethod(string $auth_method, array $token_endpoint_auth_methods_supported): bool
    {
        # client_secret_jwt has to explicitly be enabled
        if (!in_array($auth_method, $this->token_endpoint_auth_methods_supported, true)) {
            return false;
        }

        return in_array($auth_method, $token_endpoint_auth_methods_supported, true);
    }

    protected function getUserAgent(): string
    {
        return "jumbojett/OpenID-Connect-PHP";
    }
}
