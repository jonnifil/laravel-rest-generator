После установки пакета:
1. Зарегистрируйте сервис-провайдер RestPackageServiceProvider
2. Выполните в консоли  
 **php artisan vendor:publish --provider="Jonnifil\RestPackage\Providers\RestPackageServiceProvider"**
В проект скопируются классы App\Http\Controllers\ApiController, App\Repositories\BaseRepository и реестр фильтров авторизации Services/Auth/FilterMap.php
3. REST для конкретной сущности создаётся командой 
 **php artisan make:rest-api ModelName**
 В результате будет создана 
модель ModelName, 
репозиторий ModelNameRepository, 
контроллер App\Http\Controllers\Api\ModelNameController,
форм-реквесты создания и обновления ModelName, 
соответствующий ресурс и коллекция к нему
в файл routes/rest.php будет записан соответствующий модели роут apiResource (если файла нет, то он будет создан при первом вызове команды) 
4. Файл routes/rest.php надо будет вызвать в файле routes/api.php например:
   `Route::group(['prefix' => 'rest'], function () {
        include 'rest.php';
   });`
5. Если уже создана таблица и название модели соответствует соглашениям Laravel об именовании, то в модели будет добавлен блок phpDoc с описанием полей, в реквесты добавится минимальная валидация по полям, в ресурс - массив полей.
   Валидацию и массив полей в ресурсе следует доработать, согласно бизнес логике проекта.
   В остальном вы получите работоспособный РЕСТ по сущности.