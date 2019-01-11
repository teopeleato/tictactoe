<?php 

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class BlogController extends Controller
{
    
    public function listAction()
    {
        // ...
        return new Response(
          '<html><body>entro en listAction...</body></html>'
        );
        
    }

    
    public function showAction($slug)
    {
        // $slug will equal the dynamic part of the URL
        // e.g. at /blog/yay-routing, then $slug='yay-routing'

        // ...
        return new Response(
          '<html><body>entro en showAction...</body></html>'
        );
    }
}