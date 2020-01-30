<?php 
	namespace App\Providers;
	use App\TB_Company;
	use App\TB_Division;
	use App\TB_Position;
	use App\TB_Territory;
	use App\TB_Contact;
	use App\Quote;
	use App\Sales;
	use App\User;
	use Illuminate\Support\ServiceProvider;

	class DynamicClassname extends ServiceProvider
	{
		public function boot()
		{
			view()->composer('*', function($view){
				$view->with('company', TB_Company::all());		
			});

			view()->composer('*', function($view){
				$view->with('division', TB_Division::all());		
			});

			view()->composer('*', function($view){
				$view->with('position', TB_Position::all());		
			});

			view()->composer('*', function($view){
				$view->with('territory', TB_Territory::all());		
			});
			view()->composer('*', function($view){
				$view->with('code', TB_Contact::all());
			});
			view()->composer('*', function($view){
				$view->with('owner', User::all());
			});
			view()->composer('*', function($view){
				$view->with('quote', Quote::all());
			});
			view()->composer('*', function($view){
				$view->with('sales', Sales::all());
			});
		}
	}
 ?>