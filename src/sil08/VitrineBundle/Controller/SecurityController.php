<?php

namespace sil08\VitrineBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        /*
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('sil08_vitrine_homepage');
        }
        */

        $referer = $request->headers->get('referer');
        $baseUrl = $request->getBaseUrl();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));

        if(strpos($lastPath, 'administration') !== false){
            $lastPath = '/accueil';
        }

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('sil08_vitrine_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('sil08VitrineBundle:Security:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'lastPath' => $lastPath
        ));
    }
}