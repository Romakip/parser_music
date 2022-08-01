<?php

namespace App\Command;

use App\Entity\MusicAlbum;
use App\Entity\Track;
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
        $tracks = $this->entityManager
            ->getRepository(Track::class)
            ->findAll();

        if (!count($tracks)) {
            $output->writeln("В базе не нашлось музыкальных треков");
        }

        foreach ($tracks as $track) {
            $genre = $track->getGenre() ? $track->getGenre() : 'Неизвестен';

            // Получаем всех исполнителей трека
            $executors = $track->getExecutors() ?
                implode(', ', $track->getExecutors()->map(function($executor) { return $executor->getName(); })->getValues()) : 'Неизвестны';

            $output->writeln(
                "Название трека: '{$track->getTitle()}',
                Исполнители: '{$executors}',
                Музыкальный альбом: '{$track->getMusicAlbum()->getName()}'
                Жанр: '{$genre}'
                Год: '{$track->getYear()}',
             ");
        }

        return Command::SUCCESS;
    }
}