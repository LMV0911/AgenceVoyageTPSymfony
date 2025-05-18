<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Voyage;
use App\Form\UserEditType;
use App\Form\VoyageForm;
use App\Repository\VoyageRepository;
use App\Repository\UserRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
  #[Route('/', name: 'admin_dashboard')]
  public function dashboard(
    UserRepository $userRepository,
    VoyageRepository $voyageRepository,
    CommentaireRepository $commentaireRepository
  ): Response {
    // On prend les 3 premiers de chaque type (pas besoin de createdAt)
    $lastUsers = $userRepository->findAll();
    $lastVoyages = $voyageRepository->findAll();
    $lastComments = $commentaireRepository->findAll();

    // On limite à 3 (ou ce que tu veux)
    $activities = [];

    foreach (array_slice($lastUsers, 0, 3) as $user) {
      $activities[] = [
        'type' => 'user',
        'message' => $user->getPrenom() . ' ' . $user->getNom() . ' s\'est inscrit',
      ];
    }
    foreach (array_slice($lastVoyages, 0, 3) as $voyage) {
      $activities[] = [
        'type' => 'voyage',
        'message' => 'Nouveau voyage : ' . $voyage->getTitre(),
      ];
    }
    foreach (array_slice($lastComments, 0, 3) as $comment) {
      $activities[] = [
        'type' => 'comment',
        'message' => 'Commentaire de ' . $comment->getAuteur()->getPrenom() . ' sur "' . $comment->getVoyage()->getTitre() . '"',
      ];
    }

    return $this->render('admin/dashboard.html.twig', [
      'nombre_utilisateurs' => $userRepository->count([]),
      'nombre_voyages' => $voyageRepository->count([]),
      'last_activities' => $activities,
    ]);
  }

  #[Route('/voyages', name: 'admin_voyage_index')]
  public function manageVoyages(VoyageRepository $voyageRepository): Response
  {
    $voyages = $voyageRepository->findAll();

    return $this->render('admin/voyage/index.html.twig', [
      'voyages' => $voyages,
    ]);
  }

  #[Route('/voyages/nouveau', name: 'admin_voyage_new', methods: ['GET', 'POST'])]
  public function newVoyage(Request $request, EntityManagerInterface $entityManager): Response
  {
    $voyage = new Voyage();
    $form = $this->createForm(VoyageForm::class, $voyage);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($voyage);
      $entityManager->flush();

      $this->addFlash('success', 'Voyage créé avec succès');
      return $this->redirectToRoute('admin_voyage_index');
    }

    return $this->render('admin/voyage/new.html.twig', [
      'form' => $form->createView(),
      'voyage' => $voyage,
    ]);
  }

  #[Route('/voyages/{id}/editer', name: 'admin_voyage_edit', methods: ['GET', 'POST'])]
  public function editVoyage(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(VoyageForm::class, $voyage);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();
      $this->addFlash('success', 'Voyage modifié avec succès');
      return $this->redirectToRoute('admin_voyage_index');
    }

    return $this->render('admin/voyage/edit.html.twig', [
      'form' => $form->createView(),
      'voyage' => $voyage,
    ]);
  }

  #[Route('/utilisateurs', name: 'admin_user_index')]
  public function manageUsers(UserRepository $userRepository): Response
  {
    $users = $userRepository->findAll();

    return $this->render('admin/user/index.html.twig', [
      'users' => $users,
    ]);
  }

  #[Route('/utilisateurs/{id}/editer', name: 'admin_user_edit', methods: ['GET', 'POST'])]
  public function editUser(
    User $user,
    Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
  ): Response {
    $form = $this->createForm(UserEditType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Gestion du mot de passe
      if ($form->get('plainPassword')->getData()) {
        $user->setPassword(
          $passwordHasher->hashPassword(
            $user,
            $form->get('plainPassword')->getData()
          )
        );
      }

      $entityManager->flush();
      $this->addFlash('success', 'Utilisateur mis à jour avec succès');
      return $this->redirectToRoute('admin_user_index');
    }

    return $this->render('admin/user/edit.html.twig', [
      'form' => $form->createView(),
      'user' => $user,
    ]);
  }

  #[Route('/utilisateurs/{id}/supprimer', name: 'admin_user_delete', methods: ['POST'])]
  public function deleteUser(
    User $user,
    Request $request,
    EntityManagerInterface $entityManager
  ): Response {
    if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
      $entityManager->remove($user);
      $entityManager->flush();
      $this->addFlash('success', 'Utilisateur supprimé avec succès');
    }

    return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/utilisateurs/{id}/bannir', name: 'admin_user_ban', methods: ['POST'])]
  public function banUser(
    User $user,
    Request $request,
    EntityManagerInterface $entityManager
  ): Response {
    if ($this->isCsrfTokenValid('ban' . $user->getId(), $request->request->get('_token'))) {
      $user->addRole('ROLE_BANNED');
      $entityManager->flush();
      $this->addFlash('success', 'Utilisateur banni avec succès');
    }

    return $this->redirectToRoute('admin_user_index');
  }

  #[Route('/utilisateurs/{id}/debannir', name: 'admin_user_unban', methods: ['POST'])]
  public function unbanUser(
    User $user,
    Request $request,
    EntityManagerInterface $entityManager
  ): Response {
    if ($this->isCsrfTokenValid('unban' . $user->getId(), $request->request->get('_token'))) {
      $user->removeRole('ROLE_BANNED');
      $entityManager->flush();
      $this->addFlash('success', 'Utilisateur débanni avec succès');
    }

    return $this->redirectToRoute('admin_user_index');
  }
}
