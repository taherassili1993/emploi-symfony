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

            return $this->redirectToRoute('index');
        }

        return $this->render('emploi/ajouter_emploi.html.twig', ['emploi' => $emploi]);
    }

    /**
     *
     * @Route("/delete-formation/{id}", name="delete_formation")
     */
    public function deleteFormation(Request $request, $id = 0)
    {
        /*$em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $fromationRepository = $this->getDoctrine()->getRepository(Formation::class);
        $user_id = $this->getUser()->getId();
        $user = $repository->findOneById($user_id);
        $formation = $fromationRepository->findOneById($id);
        if($formation)
        {
            if($formation->getUser() == $user)
            {
                $em->remove($formation);
                $em->flush();
            }
        }
        // redirige la page
        return $this->redirectToRoute('profil');*/

    }

}
