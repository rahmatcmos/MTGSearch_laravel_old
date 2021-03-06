<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider {

	public function register() {
		$this->app->bind('App\Repositories\Set\SetRepository', 'App\Repositories\Set\EloquentSetRepository');
		$this->app->bind('App\Repositories\Type\TypeRepository', 'App\Repositories\Type\EloquentTypeRepository');
		$this->app->bind('App\Repositories\Subtype\SubtypeRepository', 'App\Repositories\Subtype\EloquentSubtypeRepository');
		$this->app->bind('App\Repositories\Supertype\SupertypeRepository', 'App\Repositories\Supertype\EloquentSupertypeRepository');
		$this->app->bind('App\Repositories\Color\ColorRepository', 'App\Repositories\Color\EloquentColorRepository');
	}

}