<?php
namespace App\Controller;

use App\Entity\Emploi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class IndexController extends AbstractController
{
    /**
     *
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        return $this->render('index.html.twig', ['name' => 'hello world !!!']);
    }

    /**
     *
     * @Route("/recherche", name="recherche-emploi")
     */
    public function emplois(Request $request)
    {
        $q = $request->query->get('q', '');

        $em = $this->getDoctrine()->getManager();
        $emploiRepository = $this->getDoctrine()->getRepository(Emploi::class);

        $emplois = $emploiRepository->search(0, 10, $q);

        return $this->render('emploi/emplois.html.twig', ['emplois' => $emplois]);
    }

}
