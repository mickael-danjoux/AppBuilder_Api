<?php

namespace App\Security;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FirebaseAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly Auth $auth,
        private readonly UserRepository $userRepo,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    /**
     * @throws Exception
     */
    public function authenticate(Request $request): Passport
    {
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization'));
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($token);
        } catch (FailedToVerifyToken $e) {
            throw new CustomUserMessageAuthenticationException('Invalid token');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $data = $verifiedIdToken->claims();
        $useExist = $this->userRepo->findOneById($data->get('user_id'));
        if (!$useExist) {
            $firebaseUser = $this->auth->getUser($data->get('user_id'));
            $email = $firebaseUser->email;
            // No email -> External Provider
            if($email === null){
                $email = $firebaseUser->providerData['email'];
            }
            $user = new User($data->get('user_id'));
            $user->setEmail($email)->setFirstName('-')->setLastName('-');
            $this->em->persist($user);
            $this->em->flush();
        }
        return new SelfValidatingPassport(new UserBadge($data->get('user_id')));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];
        return new JsonResponse($data,
            ($exception->getCode() === Response::HTTP_FORBIDDEN) ? Response::HTTP_FORBIDDEN : Response::HTTP_UNAUTHORIZED);
    }
}
