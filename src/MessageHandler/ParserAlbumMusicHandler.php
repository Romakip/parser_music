<?php

namespace App\MessageHandler;

use App\Entity\MusicAlbum;
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
                        $this->createMusicAlbum($infoFile);
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
    public function createMusicAlbum($info)
    {
        $entityManager = $this->doctrine->getManager();
        $musicAlbum = new MusicAlbum();
        $musicAlbum->setName($info['tags']['id3v2']['title'][0] ?? 'unknown');
        $musicAlbum->setExecutor($info['tags']['id3v2']['artist'][0] ?? 'unknown');
        $musicAlbum->setYear($info['tags']['id3v2']['year'][0] ?? 'unknown');
        $musicAlbum->setGenre($info['tags']['id3v2']['genre'][0] ?? 'unknown');
        $entityManager->persist($musicAlbum);
        $entityManager->flush($musicAlbum);
    }


}