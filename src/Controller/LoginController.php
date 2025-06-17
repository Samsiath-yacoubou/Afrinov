<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Utilities\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('app_dash');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/motpsoubl', name: 'app_motpsoubl')]
    public function motdepasse(Request $request, EntityManagerInterface $em, SendMail $sendMail, UserRepository $userRepository): Response
    {
        $email = $request->request->get('email');

        if ($request->isMethod('POST') && $email) {
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $token = strval(rand(100000, 999999));
                $user->setToken($token);
                $em->persist($user);
                $em->flush();

                // Assurez-vous que la méthode d'envoi d'email est correcte
                $sendMail->smtpMail($email, 'Code de Réinitialisation', 'Votre code de réinitialisation est : ' . $token);

                // Redirection avec l'email dans l'URL
                return $this->redirectToRoute('app_veriftoken', ['email' => $email]);
            } else {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
            }
        }

        return $this->render('security/motdepasse.html.twig', [
            'email' => $email,
        ]);
    }
#[Route(path: '/veriftoken', name: 'app_veriftoken')]
public function veriftoken(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
{
    $email = $request->get('email'); 
    $user = $userRepository->findOneBy(['email' => $email]);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur introuvable.');
    }

    if ($request->isMethod('POST')) {
        $token = $request->request->get('token');

        if ($user->getToken() === $token) {
            // Debug: enregistrez dans les logs
            error_log("Redirection vers change-password pour email: ".$email);
            
            return $this->redirectToRoute('user_edit_password', [
                'email' => $email, // Modifié ici
            ]);
        } else {
            $this->addFlash('error', 'Code invalide.');
        }
    }

    return $this->render('security/verif_code.html.twig', [
        'email' => $email,
    ]);
}

    #[Route(path: '/change-password', name: 'user_edit_password')]
    public function editPassword(Request $request,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $em,UserRepository $userRepository): Response
     {
        $email = $request->query->get('email');
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('new_password');
            $confirmPassword = $request->request->get('confirm_password');

            // Validation des mots de passe
            if ($newPassword !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            } elseif (strlen($newPassword) < 6) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
            } else {
                // Hashage et enregistrement du nouveau mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);

                // On nettoie le token après utilisation
                $user->setToken(null);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès !');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/change_password.html.twig', [
            'email' => $email,
        ]);
    }






    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
