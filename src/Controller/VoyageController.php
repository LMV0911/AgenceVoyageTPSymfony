<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageForm;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/voyage')]
final class VoyageController extends AbstractController
{
    #[Route(name: 'app_voyage_index', methods: ['GET'])]
    public function index(VoyageRepository $voyageRepository): Response
    {
        return $this->render('voyage/index.html.twig', [
            'voyages' => $voyageRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_voyage_show', methods: ['GET', 'POST'])]
    public function show(
        Voyage $voyage,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->getUser()) {
            $commentaire->setAuteur($this->getUser());
            $commentaire->setVoyage($voyage);
            $commentaire->setCreatedAt(new \DateTimeImmutable());
            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté.');
            return $this->redirectToRoute('app_voyage_show', ['id' => $voyage->getId()]);
        }

        return $this->render('voyage/show.html.twig', [
            'voyage' => $voyage,
            'comment_form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_voyage_delete', methods: ['POST'])]
    public function delete(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $voyage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
    }
}
