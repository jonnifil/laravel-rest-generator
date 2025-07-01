После установки пакета:
1. Зарегистрируйте сервис-провайдер RestPackageServiceProvider
2. Выполните в консоли  
 **php artisan vendor:publish --provider="Jonnifil\RestPackage\Providers\RestPackageServiceProvider"**
В проект скопируются классы App\Http\Controllers\ApiController и App\Repositories\BaseRepository
3. REST для конкретной сущности создаётся командой 
 **php artisan make:rest-api ModelName**
 В результате будет создана 
модель ModelName, 
репозиторий ModelNameRepository, 
контроллер App\Http\Controllers\Api\ModelNameController,
форм-реквесты создания и обновления ModelName, 
в файл routes/rest.php будет записан соответствующий модели роут apiResource (если файла нет, то он будет создан при первом вызове команды) 
4. Файл routes/rest.php надо будет вызвать в файле routes/api.php например:
   `Route::group(['prefix' => 'rest'], function () {
        include 'rest.php';
   });`