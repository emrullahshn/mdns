<?php

namespace App\Controller;

use App\Entity\Api\AccessToken;
use App\Entity\User;
use App\Library\Utils;
use App\Service\SalePriceCalculator\SalePriceCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ApiController
 * @package App\Controller
 * @Route(path="/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @var SalePriceCalculatorService $salePriceCalculatorService
     */
    private $salePriceCalculatorService;

    /**
     * ApiController constructor.
     * @param SalePriceCalculatorService $salePriceCalculatorService
     */
    public function __construct(SalePriceCalculatorService $salePriceCalculatorService)
    {
        $this->salePriceCalculatorService = $salePriceCalculatorService;
    }

    /**
     * @Route(path="/login", name="login", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     * @throws Exception
     */
    public function login(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $params = json_decode($request->getContent(),true);

        $email = $params['email'];
        $password = $params['password'];

        /**
         * @var User $user
         */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null){
            return new JsonResponse(
                ['errorMessage' => 'User not found!'],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        $isPasswordValid = $passwordEncoder->isPasswordValid($user, $password);

        if ($isPasswordValid === false) {
            return new JsonResponse(
                ['errorMessage' => 'Check user credentials!'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        $accessToken = (new AccessToken())
            ->setUser($user)
            ->setToken(Utils::generateToken())
            ->setDeletedAt((new \DateTime('+1 day')));
        $entityManager->persist($accessToken);

        $entityManager->flush();

        return new JsonResponse(['token' => $accessToken->getToken()], 200);
    }

    /**
     *
     * @Route("/sale-price-calculate")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function salePriceCalculate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $content = json_decode($request->getContent(), true);
        return new JsonResponse(['basket' => $this->salePriceCalculatorService->calculateSalePriceForBasket($content['basket'])]);
    }
}
