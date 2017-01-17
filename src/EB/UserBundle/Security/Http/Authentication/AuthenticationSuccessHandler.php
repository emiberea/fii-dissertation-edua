<?php

namespace EB\UserBundle\Security\Http\Authentication;

use EB\UserBundle\Entity\AbstractUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var AbstractUser $user */
        $user = $token->getUser();
        if ($user instanceof AbstractUser && in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->httpUtils->createRedirectResponse($request, '/admin/dashboard'); // TODO: use router service instead of hard-coding the URL
        }

        return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
    }
}
