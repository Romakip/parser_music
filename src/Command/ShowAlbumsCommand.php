<?php

namespace App\Command;

use App\Entity\MusicAlbum;
use App\Repository\MusicAlbumRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowAlbumsCommand extends Command
{
    protected static $defaultName = 'app:show-music-albums';

    protected static $defaultDescription = 'Отображает музыкальные альбомы';

    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $musicAlbums = $this->entityManager
            ->getRepository(MusicAlbum::class)
            ->findAll();

        if (!count($musicAlbums)) {
            $output->writeln("В базе не нашлось альбомов");
        }

        foreach ($musicAlbums as $album) {
            $output->writeln(
                "Название альбома: '{$album->getName()}',
                Исполнитель: '{$album->getExecutor()}',
                Год: '{$album->getYear()}',
                Жанр: '{$album->getGenre()}'
             ");
        }
        return Command::SUCCESS;
    }
}