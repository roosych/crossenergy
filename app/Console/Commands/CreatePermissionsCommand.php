<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;

class CreatePermissionsCommand extends Command
{
    protected $signature = 'permissions:create';

    protected $description = 'Create ';

    public function handle()
    {
        $this->createPermissions();
        $this->info('Готово, Босс! Пермишены на месте');
        return Command::SUCCESS;
    }

    public function createPermissions()
    {
        $policies = Gate::policies();
        foreach ($policies as $model => $policy) // $policy - это класс, получаем его методы функцией  get_class_methods()
        {
            $methods = $this->getPolicyMethods($policy);

            foreach ($methods as $method)
            {
                //firstOrCreate() чтобы не создавать дубликатов при запуске команды если такая запись уже есть
                Permission::query()->firstOrCreate([
                    'action' => $method,
                    'model' => $model,
                ]);
            }
        }
    }

    public function getPolicyMethods($policy)
    {
        $methods = get_class_methods($policy);

        // фильтруем методы и возвращаем массив, чтобы не получать лишние которые приходят с трейтов
        return array_filter($methods, function ($method){
            return !in_array($method, [
                'denyWithStatus',
                'denyAsNotFound',
            ]);
        });
    }
}
