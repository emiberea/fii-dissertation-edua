<?php

namespace EB\UserBundle\Security\Http\Authentication;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /** @var Router $router */
    private $router;

    /** @var AuthorizationChecker $authorizationChecker */
    private $authorizationChecker;

    /**
     * @param HttpUtils $httpUtils
     * @param array $options
     * @param Router $router
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(HttpUtils $httpUtils, array $options, Router $router, AuthorizationChecker $authorizationChecker)
    {
        parent::__construct($httpUtils, $options);
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->httpUtils->createRedirectResponse($request, 'eb_admin_dashboard_index');
        }

        return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
    }
}
