# Музыкальный парсер

### Инструкция 
1) Для сборки приложения необходимо выполнить следующие команды:
   * **docker-compose build --no-cache**,
   * **docker-compose up**

2) Чтобы получить список музыкальных альбомов, добавленных в приложение, используется команда: 
   * **команда docker exec -it project_container bash -c "php bin/console app:show-music-albums"**
   
3) Чтобы начать парсить каталог с музыкальными альбомами:
   * **docker exec -it project_container bash -c "php bin/console app:parsing-music-albums {path_to_catalog}"** 
   
   (Для примера каталог public/music наполнен музыкальными файлами)
   
4) Чтобы погрузить свой каталог в приложение используется команда:
   * **docker cp {local_path_to_catalog} project_container:{app_path_to_catalog}** 
   
   (Например, docker cp /home/music project_container:/tmp/music)
