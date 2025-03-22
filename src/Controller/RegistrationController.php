<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du mot de passe en clair et de la confirmation
            $plainPassword = $form->get('plainPassword')->getData();
            $passwordConfirm = $form->get('passwordConfirm')->getData();

            // Vérifier que les mots de passe correspondent
            if ($plainPassword !== $passwordConfirm) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_register');
            }

            // Hachage du mot de passe
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

            // Initialiser isVerified à false (l'utilisateur doit vérifier son email)
            $user->setIsVerified(false);

            // Persist l'utilisateur dans la base de données
            $em->persist($user);
            $em->flush();

            // Créer l'email de confirmation avec le lien signé
            $email = (new TemplatedEmail())
                ->from('alshahoudmohamed95@gmail.com')
                ->to($user->getEmail())
                ->subject('Vérifiez votre adresse e-mail')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'user' => $user,  // ✅ Passer l'utilisateur au template
                    'signedUrl' => $this->generateUrl('app_verify_email', [
                        'id' => $user->getId(),
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                    'expiresAtMessageKey' => '24 heures', // Remplace ceci par la vraie durée d'expiration si nécessaire
                ]);

            // Envoi de l'email de confirmation
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);

            // Rediriger l'utilisateur vers la page de confirmation
            return $this->redirectToRoute('app_register_confirmation');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $em): Response
    {
        $userId = $request->query->get('id');

        if (null === $userId) {
            $this->addFlash('error', 'Lien de vérification invalide.');
            return $this->redirectToRoute('app_register');
        }

        $user = $em->getRepository(User::class)->find($userId);

        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_register');
        }

        // Valider le lien de confirmation
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
            $this->addFlash('success', 'Votre email a été vérifié avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur de validation du lien.');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/register/confirmation', name: 'app_register_confirmation')]
    public function registerConfirmation(): Response
    {
        return $this->render('registration/confirmation.html.twig');
    }
}
