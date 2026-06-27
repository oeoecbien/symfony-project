<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingServiceInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;

final class BuildingController extends AbstractController
{
    public function __construct(
        private readonly BuildingServiceInterface $buildingService,
    ) {
    }

    #[Route('/buildings/', name: 'app_building_index', methods: ['GET'])]
    #[Cache(public: true, maxage: 3600, mustRevalidate: true)]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Number of the page',
        schema: new OA\Schema(type: 'integer', default: 1),
        required: false
    )]
    #[OA\Parameter(
        name: 'size',
        in: 'query',
        description: 'Number of records',
        schema: new OA\Schema(type: 'integer', default: 10, minimum: 1, maximum: 100),
        required: false
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns an array of Buildings',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Building::class))
        )
    )]
    #[OA\Response(response: 403, description: 'Access denied')]
    #[OA\Tag(name: 'Building')]
    public function index(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingIndex', null);

        return JsonResponse::fromJsonString($this->buildingService->serializeJson($this->buildingService->findAllPaginated($request->query)));
    }

    #[Route('/buildings/', name: 'app_building_create', methods: ['POST'])]
    #[OA\RequestBody(
        request: 'Building',
        description: 'Data for the Building',
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'name' => 'Silken',
                'caste' => 'Forteresse',
                'strength' => 150,
                'price' => 2000,
                'image' => '/buildings/chateau-silken.webp',
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the Building',
        content: new OA\JsonContent(ref: new Model(type: Building::class))
    )]
    #[OA\Response(response: 403, description: 'Access denied')]
    #[OA\Tag(name: 'Building')]
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingCreate', null);
        $building = $this->buildingService->create($request->getContent());

        $response = JsonResponse::fromJsonString(
            $this->buildingService->serializeJson($building),
            JsonResponse::HTTP_CREATED
        );
        $response->headers->set('Location', $this->generateUrl('app_building_display', [
            'identifier' => $building->getIdentifier(),
        ]));

        return $response;
    }

    #[Route(
        '/buildings/{identifier}',
        requirements: ['identifier' => '^([a-z0-9]{40})$'],
        name: 'app_building_display',
        methods: ['GET']
    )]
    #[Cache(public: true, maxage: 3600, mustRevalidate: true)]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Building',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the Building',
        content: new OA\JsonContent(ref: new Model(type: Building::class))
    )]
    #[OA\Response(response: 403, description: 'Access denied')]
    #[OA\Response(response: 404, description: 'Not found')]
    #[OA\Tag(name: 'Building')]
    public function read(
        #[MapEntity(expr: 'repository.findOneByIdentifier(identifier)')]
        Building $building
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingDisplay', $building);

        return JsonResponse::fromJsonString($this->buildingService->serializeJson($building));
    }

    #[Route(
        '/buildings/images',
        defaults: ['number' => 1],
        name: 'app_building_images_default',
        methods: ['GET']
    )]
    #[Route(
        '/buildings/images/{number}',
        defaults: ['number' => 1],
        requirements: ['number' => '^([0-9]{1,2})$'],
        name: 'app_building_images',
        methods: ['GET']
    )]
    #[OA\Parameter(
        name: 'number',
        in: 'path',
        description: 'Number of images',
        schema: new OA\Schema(type: 'integer', default: 1),
        required: false
    )]
    #[OA\Response(response: 200, description: 'Returns links for building images')]
    #[OA\Response(response: 403, description: 'Access denied')]
    #[OA\Tag(name: 'Building')]
    public function images(int $number = 1): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingIndex', null);

        return new JsonResponse($this->buildingService->getImages($number));
    }

    #[Route(
        '/buildings/{identifier:building}',
        requirements: ['identifier' => '^([a-z0-9]{40})$'],
        name: 'app_building_update',
        methods: ['PUT']
    )]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Building',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\RequestBody(
        request: 'Building',
        description: 'Data for the Building',
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'name' => 'Silken',
                'strength' => 140,
            ]
        )
    )]
    #[OA\Response(response: 204, description: 'No content')]
    #[OA\Response(response: 403, description: 'Access denied')]
    #[OA\Response(response: 404, description: 'Not found')]
    #[OA\Tag(name: 'Building')]
    public function update(Request $request, Building $building): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingUpdate', $building);
        $this->buildingService->update($building, $request->getContent());

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route(
        '/buildings/{identifier:building}',
        requirements: ['identifier' => '^([a-z0-9]{40})$'],
        name: 'app_building_delete',
        methods: ['DELETE']
    )]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Building',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\Response(response: 204, description: 'No content')]
    #[OA\Response(response: 403, description: 'Access denied')]
    #[OA\Response(response: 404, description: 'Not found')]
    #[OA\Tag(name: 'Building')]
    public function delete(Building $building): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingDelete', $building);
        $this->buildingService->delete($building);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
