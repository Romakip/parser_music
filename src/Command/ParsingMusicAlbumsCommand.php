<?php

namespace App\Command;

use App\Message\ParsMusicAlbumMessage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ParsingMusicAlbumsCommand extends Command
{
    protected static $defaultName = 'app:parsing-music-albums';

    protected static $defaultDescription = 'Парсинг музыкальных фалов формата mp3, в качестве аргумента
        передайте путь к каталогу с музыкой';

    protected $bus;

    protected  $pathToMusic; //Путь до каталога с музыкой

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('path_music', InputArgument::REQUIRED, 'Укажите путь к каталогу с музыкой');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->pathToMusic = $input->getArgument('path_music');
        $this->bus->dispatch(new ParsMusicAlbumMessage($this->pathToMusic));
        $output->writeln('Начинается парсинг музыкального альбома по пути: ' . $this->pathToMusic);
        return Command::SUCCESS;
    }
}