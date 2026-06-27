<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Building;
use App\Entity\Character;
use App\Entity\User;
use App\Event\BuildingEvent;
use App\Form\BuildingType;
use App\Repository\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use LogicException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BuildingService implements BuildingServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BuildingRepository $buildingRepository,
        private readonly FormFactoryInterface $formFactory,
        private readonly SluggerInterface $slugger,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    public function serializeJson(mixed $object): string
    {
        $this->setLinks($object);

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static function (object $object): string {
                if ($object instanceof Building || $object instanceof Character) {
                    return (string) $object->getIdentifier();
                }
                if ($object instanceof User) {
                    return $object->getUserIdentifier();
                }

                throw new CircularReferenceException(
                    'A circular reference has been detected when serializing the object of class "'.get_debug_type($object).'".'
                );
            },
            'groups' => ['building'],
            'json_encode_options' => JSON_UNESCAPED_SLASHES,
        ];

        return $this->serializer->serialize($object, 'json', $context);
    }

    public function create(string $data): Building
    {
        $building = new Building();
        $this->submit($building, BuildingType::class, $data);
        $this->dispatcher->dispatch(new BuildingEvent($building), BuildingEvent::BUILDING_CREATED);
        $building->setSlug((string) $this->slugger->slug((string) $building->getName())->lower());

        $building->setIdentifier(hash('sha1', uniqid()));
        $this->isEntityFilled($building);

        $this->entityManager->persist($building);
        $this->entityManager->flush();
        $this->dispatcher->dispatch(new BuildingEvent($building), BuildingEvent::BUILDING_CREATED_POST_DATABASE);

        return $building;
    }

    public function submit(Building $building, string $formName, string|array $data): void
    {
        $dataArray = \is_array($data) ? $data : json_decode($data, true);
        if (null !== $data && !\is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> '.$data);
        }

        $dataArray ??= [];

        $form = $this->formFactory->create($formName, $building, ['csrf_protection' => false]);
        $form->submit($dataArray, false);

        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $cause = $error->getCause();
            $errorMsg = 'Error '.(\is_object($cause) ? $cause::class : 'unknown');
            $errorMsg .= ' --> '.($error->getMessageTemplate() ?? $error->getMessage());
            $errorMsg .= ' '.json_encode($error->getMessageParameters(), JSON_THROW_ON_ERROR);
            throw new LogicException($errorMsg);
        }
    }

    public function isEntityFilled(Building $building): void
    {
        $errors = $this->validator->validate($building);
        if (\count($errors) > 0) {
            $errorMsg = (string) $errors.'Wrong data for Entity -> ';
            $errorMsg .= json_encode($this->serializeJson($building), JSON_THROW_ON_ERROR);
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->buildingRepository->findAll();
    }

    public function findAllPaginated(InputBag $query): SlidingPagination
    {
        return $this->paginator->paginate(
            $this->findAll(),
            $query->getInt('page', 1),
            min(100, max(1, $query->getInt('size', 10)))
        );
    }

    public function update(Building $building, string $data): void
    {
        $this->submit($building, BuildingType::class, $data);
        $this->dispatcher->dispatch(new BuildingEvent($building), BuildingEvent::BUILDING_UPDATED);
        $building->setSlug((string) $this->slugger->slug((string) $building->getName())->lower());
        $this->isEntityFilled($building);

        $this->entityManager->persist($building);
        $this->entityManager->flush();
    }

    public function delete(Building $building): void
    {
        $this->entityManager->remove($building);
        $this->entityManager->flush();
    }

    public function setLinks(mixed $object): void
    {
        if ($object instanceof SlidingPagination) {
            foreach ($object->getItems() as $item) {
                $this->setLinks($item);
            }

            return;
        }

        if (\is_array($object)) {
            foreach ($object as $item) {
                $this->setLinks($item);
            }

            return;
        }

        if (!$object instanceof Building) {
            return;
        }

        $href = '/buildings/'.$object->getIdentifier();
        $object->setLinks([
            'self' => ['href' => $href],
            'update' => ['href' => $href],
            'delete' => ['href' => $href],
        ]);
    }

    public function getImages(int $number): array
    {
        $folder = __DIR__.'/../../public/images/buildings/';
        if (!is_dir($folder)) {
            return [];
        }

        $finder = new Finder();
        $finder->files()->in($folder);

        $images = [];
        foreach ($finder as $file) {
            $images[] = str_replace(__DIR__.'/../../public', '', $file->getPathname());
        }

        shuffle($images);

        return array_slice($images, 0, max(1, $number));
    }
}
