<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DestroyCrud extends Command
{
    protected $signature = 'destroy:crud
                            {name : Resource name, e.g., }
                            {--impl : Remove repository and Service implementation}
                            {--model : Remove model}';

    protected $description = 'Remove responsitory, service and controller for a given resource';

    public function handle()
    {
        $name = $this->argument('name');
        $impl = $this->option('impl');
        $model = $this->option('model');

        $this->info("🗑️ Removing CRUD for {$name}");

        // -----------------------------
        // 1️⃣ Repository Interface
        // -----------------------------
        $interfacePath = app_path("Repositories/{$name}Repository.php");
        $this->deleteFile($interfacePath, "Interface {$name}Repository");

        // -----------------------------
        // 2️⃣ Repository Implementation
        // -----------------------------
        if ($impl) {
            $implPath = app_path("Repositories/{$name}RepositoryImpl.php");
            $this->deleteFile($implPath, "Implementation {$name}RepositoryImpl");
        }

        // -----------------------------
        // 3️⃣ Service Interface
        // -----------------------------
        $serviceInterfacePath = app_path("Services/{$name}Service.php");
        $this->deleteFile($serviceInterfacePath, "Interface {$name}Service");

        // -----------------------------
        // 4️⃣ Service Implementation
        // -----------------------------
        if ($impl) {
            $serviceImplPath = app_path("Services/{$name}ServiceImpl.php");
            $this->deleteFile($serviceImplPath, "Implementation {$name}ServiceImpl");
        }

        // -----------------------------
        // 5️⃣ Controller
        // -----------------------------
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");
        $this->deleteFile($controllerPath, "Controller {$name}Controller");

        $this->info("\n✅ CRUD for {$name} removed successfully.");

        if($model) {
            $modelPath = app_path("Models/{$name}.php");

            if(file_exists($modelPath)) {
                $this->deleteFile($modelPath, "Model {$model}");
            }
        }
    }

    protected function deleteFile(string $path, string $label)
    {
        if(File::exists($path)) {
            File::delete($path);
            $this->info("Delete: {$label}");
        }else {
            $this->warn("Not fount: {$label}");
        }
    }
}
