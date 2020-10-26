<?php

namespace Modules\Infrastructure\Providers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Referral\Exceptions\HashIdNotFoundCustomException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
class RouteServiceProvider extends ServiceProvider
{

    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Infrastructure\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Infrastructure', '/Routes/api.php'));
    }

    protected function getModel($model, $routeKey)
    {
        $id = $this->getId($model, $routeKey);
        $modelInstance = resolve($model);
        return $modelInstance->findOrFail($id);
    }

    protected function getId($model, $routeKey)
    {
        $modelNameArr = explode('\\',$model);
        $modelName = end($modelNameArr);
        try {
            return Hashids::connection($model)->decode($routeKey)[0] ?? null;
        } catch (\Exception $e) {
            throw new HashIdNotFoundCustomException($modelName);
        }
    }
}
