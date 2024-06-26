<?php 

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response
    {
        //on récupère les datas envoyées par le front
        $data = json_decode($request->getContent(), true);
        //on crée un nouvel utilisateur
        $user = new User();
        //on lui set les paramètres
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setFirstName($data['firstName']);
        $user->setRoles(['ROLE_USER']);
        $user->setIsAdmin(false);
        //on hache le mot de passe
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $data['password']
            )
        );
        //on persiste l'utilisateur
        $entityManager->persist($user);

        $profil = new Profil();
        $profil->setUserId($user);
        $profil->setBiography('...');
        $profil->setFiliere(null);

        $entityManager->persist($profil);
        //on flush
        $entityManager->flush();

        //on retourne une réponse json
        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );
        
    }
}