<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Building;
use App\Entity\Character;
use App\Entity\User;
use App\Event\CharacterEvent;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use DateTimeImmutable;
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

final class CharacterService implements CharacterServiceInterface
{
    /**
     * Jeu de données utilisé dans le TP pour alimenter la base.
     */
    private const DEFAULT_CHARACTERS = [
        [
            'name' => 'Anardil',
            'slug' => 'anardil',
            'kind' => 'Dame',
            'surname' => 'Amie du soleil',
            'caste' => 'Magicien',
            'knowledge' => 'Sciences',
            'intelligence' => 180,
            'strength' => 180,
            'image' => '/dames/anardil.webp',
        ],
        [
            'name' => 'Bjornek',
            'slug' => 'bjornek',
            'kind' => 'nain',
            'surname' => 'Marteau-de-fer',
            'caste' => 'Guerrier',
            'knowledge' => 'Forge',
            'intelligence' => 120,
            'strength' => 200,
            'image' => '/seigneurs/bjornek.webp',
        ],
        [
            'name' => 'Syrhia',
            'slug' => 'syrhia',
            'kind' => 'humaine',
            'surname' => 'Voix des brumes',
            'caste' => 'Prêtresse',
            'knowledge' => 'Mystique',
            'intelligence' => 170,
            'strength' => 90,
            'image' => '/dames/syrhia.webp',
        ],
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CharacterRepository $characterRepository,
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
            'groups' => ['character'],
            'json_encode_options' => JSON_UNESCAPED_SLASHES,
        ];

        return $this->serializer->serialize($object, 'json', $context);
    }

    public function save(Character $character): void
    {
        $this->entityManager->persist($character);
        $this->entityManager->flush();
    }

    public function create(string $data): Character
    {
        $character = new Character();
        $this->submit($character, CharacterType::class, $data);
        $this->dispatcher->dispatch(new CharacterEvent($character), CharacterEvent::CHARACTER_CREATED);
        $character->setSlug((string) $this->slugger->slug((string) $character->getName())->lower());

        if ($character->getSlug() === 'anardil') {
            $existing = $this->characterRepository->findOneBy(['slug' => 'anardil']);
            if ($existing !== null) {
                return $existing;
            }
        }

        $character->setIdentifier(hash('sha1', uniqid()));
        $now = new DateTimeImmutable();
        $character->setCreation($now);
        $character->setModification($now);
        $this->isEntityFilled($character);

        $this->entityManager->persist($character);
        $this->entityManager->flush();
        $this->dispatcher->dispatch(new CharacterEvent($character), CharacterEvent::CHARACTER_CREATED_POST_DATABASE);

        return $character;
    }

    public function submit(Character $character, string $formName, string|array $data): void
    {
        $dataArray = \is_array($data) ? $data : json_decode($data, true);
        if (null !== $data && !\is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> '.$data);
        }

        $dataArray ??= [];

        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
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

    public function isEntityFilled(Character $character): void
    {
        $errors = $this->validator->validate($character);
        if (\count($errors) > 0) {
            $errorMsg = (string) $errors.'Wrong data for Entity -> ';
            $errorMsg .= json_encode($this->serializeJson($character), JSON_THROW_ON_ERROR);
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    /**
     * @return array<int, Character>
     */
    public function findAll(): array
    {
        return $this->characterRepository->findAll();
    }

    public function findAllPaginated(InputBag $query): SlidingPagination
    {
        return $this->paginator->paginate(
            $this->findAll(),
            $query->getInt('page', 1),
            min(100, max(1, $query->getInt('size', 10)))
        );
    }

    public function findByMinLifePaginated(InputBag $query, int $life): SlidingPagination
    {
        return $this->paginator->paginate(
            $this->characterRepository->getAllByLifeLevel($life),
            $query->getInt('page', 1),
            min(100, max(1, $query->getInt('size', 10)))
        );
    }

    public function getAllByLifeLevel(int $level): array
    {
        return $this->characterRepository->getAllByLifeLevel($level);
    }

    public function update(Character $character, string $data): void
    {
        $this->submit($character, CharacterType::class, $data);
        $this->dispatcher->dispatch(new CharacterEvent($character), CharacterEvent::CHARACTER_UPDATED);
        $character->setSlug((string) $this->slugger->slug((string) $character->getName())->lower());
        $character->setModification(new DateTimeImmutable());
        $this->isEntityFilled($character);

        $this->entityManager->persist($character);
        $this->entityManager->flush();
    }

    public function delete(Character $character): void
    {
        $this->entityManager->remove($character);
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

        if (!$object instanceof Character) {
            return;
        }

        $href = '/characters/'.$object->getIdentifier();
        $object->setLinks([
            'self' => ['href' => $href],
            'update' => ['href' => $href],
            'delete' => ['href' => $href],
        ]);
    }

    public function getImages(int $number, ?string $kind = null): array
    {
        $folder = __DIR__.'/../../public/images/';
        if (!is_dir($folder)) {
            return [];
        }

        $finder = new Finder();
        $finder->files()->in($folder)->notPath('/buildings/');
        if ($kind !== null) {
            $finder->path('/'.preg_quote($kind, '/').'/');
        }

        $images = [];
        foreach ($finder as $file) {
            $images[] = str_replace(__DIR__.'/../../public', '', $file->getPathname());
        }

        shuffle($images);

        return array_slice($images, 0, max(1, $number));
    }

    public function getImagesKind(string $kind, int $number): array
    {
        return $this->getImages($number, $kind);
    }

    /**
     * Insère les personnages de base du TP, sans dupliquer les slugs existants.
     */
    public function seedDefaults(): array
    {
        $created = [];

        foreach (self::DEFAULT_CHARACTERS as $data) {
            if ($this->characterRepository->findOneBy(['slug' => $data['slug']]) !== null) {
                continue;
            }

            $character = new Character();
            $character->setName($data['name']);
            $character->setIdentifier($this->generateIdentifier());
            $character->setSlug($data['slug']);
            $character->setKind($data['kind']);
            $character->setSurname($data['surname']);
            $character->setCaste($data['caste']);
            $character->setKnowledge($data['knowledge']);
            $character->setIntelligence($data['intelligence']);
            $character->setStrength($data['strength']);
            $character->setImage($data['image']);

            $this->entityManager->persist($character);
            $created[] = $character;
        }

        if ($created !== []) {
            $this->entityManager->flush();
        }

        return $created;
    }

    public function resetAndSeed(): array
    {
        $this->entityManager->clear();
        $this->entityManager->getConnection()->executeStatement('TRUNCATE TABLE `character`');

        return $this->seedDefaults();
    }

    private function generateIdentifier(): string
    {
        return hash('sha1', uniqid('', true));
    }
}
