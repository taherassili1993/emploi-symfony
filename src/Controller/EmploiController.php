<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Emploi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class EmploiController extends AbstractController
{
    /**
     *
     * @Route("/mes-emplois", name="mes-emplois")
     */
    public function mesEmplois(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $emploiRepository = $this->getDoctrine()->getRepository(Emploi::class);

        $user_id = $this->getUser()->getId();
        $user = $repository->findOneById($user_id);
        $emplois = $emploiRepository->findBy(['user' => $user]);

        return $this->render('emploi/mes_emplois.html.twig', ['emplois' => $emplois]);
    }

    /**
     *
     * @Route("/ajouter-emploi/{id}", name="ajouter-emploi")
     */
    public function ajouterEmploi(Request $request, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $emploiRepository = $this->getDoctrine()->getRepository(Emploi::class);
        $user_id = $this->getUser()->getId();
        $user = $repository->findOneById($user_id);

        if($id != 0)
        {
            $emploi = $emploiRepository->findOneById($id);
            if(!$emploi)
            {
                return $this->redirectToRoute('index');
            }
            if($emploi->getUser() != $user)
            {
                return $this->redirectToRoute('index');
            }
        } else {
            $emploi = new Emploi();
        }

        if($request->getMethod() == 'POST')
        {
            $titre = $request->get('titre', '');
            $societe = $request->get('societe', '');
            $contrat = $request->get('contrat', '');
            $salaire = $request->get('salaire', '');
            $addresse = $request->get('addresse', '');
            $description = $request->get('description', '');

            $emploi->setTitre($titre);
            $emploi->setSociete($societe);
            $emploi->setContrat($contrat);
            $emploi->setSalaire($salaire);
            $emploi->setAddresse($addresse);
            $emploi->setDescription($description);
            $emploi->setUser($user);
            $d = new \DateTime();
            $emploi->setCreatedAt($d);

            $em->persist($emploi);
            $em->flush();

            return $this->redirectToRoute('mes-emplois');
        }

        return $this->render('emploi/ajouter_emploi.html.twig', ['emploi' => $emploi]);
    }

    /**
     *
     * @Route("/delete-emploi/{id}", name="delete_emploi")
     */
    public function deleteEmploi(Request $request, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $emploiRepository = $this->getDoctrine()->getRepository(Emploi::class);
        $user_id = $this->getUser()->getId();
        $user = $repository->findOneById($user_id);
        $emploi = $emploiRepository->findOneById($id);
        if($emploi)
        {
            if($emploi->getUser() == $user)
            {
                $em->remove($emploi);
                $em->flush();
            }
        }
        // redirige la page
        return $this->redirectToRoute('mes-emplois');

    }

    /**
     *
     * @Route("/emplois", name="list-emploi")
     */
    public function emplois(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $emploiRepository = $this->getDoctrine()->getRepository(Emploi::class);

        $emplois = $emploiRepository->findAll();

        return $this->render('emploi/emplois.html.twig', ['emplois' => $emplois]);
    }


}
