<?php

namespace App\Message;

class ParsMusicAlbumMessage
{
    private $pathToCatalogWithMusic; //Путь до каталога с музыкой

    /**
     * @param string $pathToCatalogWithMusic
     */
    public function __construct(string $pathToCatalogWithMusic)
    {
        $this->pathToCatalogWithMusic = $pathToCatalogWithMusic;
    }

    /**
     * @return string
     */
    public function getPathToCatalogWithMusic(): string
    {
        return $this->pathToCatalogWithMusic;
    }
}