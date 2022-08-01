<?php

namespace App\MessageHandler;

use App\Entity\Executor;
use App\Entity\Genre;
use App\Entity\MusicAlbum;
use App\Entity\Track;
use App\Message\ParsMusicAlbumMessage;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use getID3;

class ParserAlbumMusicHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $doctrine;
    private $logger;

    private $pathToCatalogWithMusic; //Путь до каталога с музыкальными альбомами

    /**
     * @param EntityManagerInterface $entityManager
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    /**
     * @param ParsMusicAlbumMessage $musicAlbum
     * @return void
     */
    public function __invoke(ParsMusicAlbumMessage $musicAlbum)
    {
        $this->pathToCatalogWithMusic = $musicAlbum->getPathToCatalogWithMusic();

        $finder = new Finder();
        $finder->files()->in($this->pathToCatalogWithMusic);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                if ($file->getExtension() === 'mp3') {
                    $getId3 = new getID3();
                    $infoFile = $getId3->analyze($file->getRealPath());
                    if (!empty($infoFile['tags']['id3v2'])) {
                        $this->createTrack($infoFile);
                    } else {
                        $this->logger->warning('Не удалось получить информацию о музыкальном файле: '
                            . $file->getFilename());
                    }
                }
            }
        }
    }

    /**
     * @param $info
     * @return void
     * Сохранение информации о музыкальном альбоме
     */
    public function createTrack($info)
    {
        $entityManager = $this->doctrine->getManager();

        // Создаем трек
        $track = new Track();
        $track->setTitle($info['tags']['id3v2']['title'][0] ?? 'unknown');

        // Добавляем жанр
        $genre = $entityManager->getRepository(Genre::class)->findOneBy(array('name' => strtolower($info['tags']['id3v2']['genre'][0])));
        if ($genre) {
            $track->setGenre($genre);
        }

        // Добавляем исполнителя
        $executor = $entityManager->getRepository(Executor::class)->findOneBy(array('name' => $info['tags']['id3v2']['artist'][0]));
        if ($executor) {
            $track->addExecutor($executor);
        } else {
            $newExecutor = new Executor();
            $newExecutor->setName($info['tags']['id3v2']['artist'][0]);
            $entityManager->persist($newExecutor);
            $entityManager->flush($newExecutor);

            $track->addExecutor($newExecutor);
        }

        // Добавляем музыкальный альбом
        $musicAlbum = $entityManager->getRepository(MusicAlbum::class)->findOneBy(array('name' => $info['tags']['id3v2']['album'][0]));
        if ($musicAlbum) {
            $track->setMusicAlbum($musicAlbum);
        } else {
            $newMusicAlbum = new MusicAlbum();
            $newMusicAlbum->setName($info['tags']['id3v2']['album'][0]);
            $entityManager->persist($newMusicAlbum);
            $entityManager->flush($newMusicAlbum);

            $track->setMusicAlbum($newMusicAlbum);
        }

        // Устанавливаем год
        $track->setYear($info['tags']['id3v2']['year'][0] ?? 'unknown');

        $entityManager->persist($track);
        $entityManager->flush($track);
    }
}