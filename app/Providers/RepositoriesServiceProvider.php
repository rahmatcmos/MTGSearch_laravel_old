<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider {

	public function register() {
		$this->app->bind('App\Repositories\Set\SetRepository', 'App\Repositories\Set\EloquentSetRepository');
	}

}