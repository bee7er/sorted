
This version is Homestead / Vagrant

To run locally:

    http://sorted.test

Use GIT

    20201228 changed to use SSH authentication

Mysql:

    use the vagrant ssh command line:
	
		mysql -uroot -psecret
		
	GRANT ALL ON sorted.* TO brian@'localhost' IDENTIFIED BY 'Canopy405';

Laracasts

https://laracasts.com/series/laravel-6-from-scratch/

    Had difficulty getting Homestead, VirtualBox and Vagrant updated
    Eventually got PHP7.3.11 installed and running locally
    Got PHP7.3.9 running in the virtual box

    Got the Laravel installer installed and running
        composer global require laravel/installer

    Got this new project going
        laravel new modchecker

    Got a command line running from the project root directory
        php artisan serve

        # Shows a command line and a local URL:
        # http://127.0.0.1:8000 goes to the starter website position

    Valet looks good if one is not using Homestead

Template

    Free template obtained from https://templated.co/

Webpack

    For packaging up the js and css resources in Laravel

    Look at webpack.mix.js

    We run the compilation of these with node:

        npm -v      # to see which version is installed

        # Run install of all the dependencies defined in package.json
        # Note that this adds thousands of files to the project
        # To stop these from being indexed go to PhpStorm | Preferences | Project | Directories and exclude node_modules

        npm install

        # To run the compile, as shown in the package.json file, run:

        npm run development

        # or

        npm run dev

Artisan

    # e.g. php artisan help make:controller

    # use artisan to make the various PHP classes needed

 * Generated model using artisan:
 *      php artisan make:model Post

// Generated controller using artisan:
//      php artisan make:controller PostsController

// Generate a migration file in order to create the posts table:
//      php artisan make:migration create_posts_table

        // Using a database in a controller function
//        $post = DB::table('posts')->where('slug', $slug)->first();
        // Using the Post model class
//        $post = Post::where('slug', $slug)->first();
        // Now fail if the slug is not found
        $post = Post::where('slug', $slug)->firstOrFail();

        // Not needed if failing
//        if (!$post) {
//            abort(404);
//        }

        return view('post', [
            'post' => $post
        ]);

Debugging

    //        dump("Article id: $id");
    //        dd($article);

The 7 RESTful controller actions

    # REpresentational State Transfer

    #see the Articles controller

    index - display a list
    show - display a given instance
    create - create a new instance
    store - save the new instance to the db
    edit - edit an instance
    update - persist the edited instance
    destroy - delete an instance

    # say we wanted a resource controller, check the help on the make:controller command

    php artisan help make:controller

    # Here we create a new model called Project together with its controller
    # by creating the model aswell we cause the controller to use the Project model
    # and declare it in the function arguments where appropriate in the controller functions

    php artisan make:controller ProjectsController -r -m Project

    # that takes care of the model and controller now we want the route to complement this set up
    # we set up the routes and use the request type (get, post, etc) to govern which controller function gets called
    # this means that everything you need to know is included in the request, which is stateless
    # an alternative would be to use the session to store which instance was being targeted, but
    # would be error prone and a crap design
    # in this way we avoid havibg to put verbs in the URI, since the verb is the request type (the HTTP verbs):
    GET, POST, PUT, PATCH, DELETE

    # Our 7 functions can conform to the RESTful standard by specifying the simple URI together with the HTTP verb:
    # note that we add two more so that we can get to the pages which allow us to present forms for adding and editing

    /projects - without params means get all
    /projects/:id - with an ide means for a single instance

    GET /projects   -   get all instances
    GET /projects/create    -   display the create instance form
    POST /projects  -   with POST params means create the instance, called from the create page

    GET /projects/:id   -   show the specified instance

    GET /projects/:id/edit  -   display the edit instance form
    PUT /projects/:id   -   update the specified instance, called from the edit page

    DELETE /projects/:id    -   delete the specified instance

Form Management

    # see the create article form and controller functions create and store

    # especially note the use of the @csrf directive which generates a CSRF token in the form

    # and we use the bulma CSS from cdnjs for form field styling

    # check out the form construction in article create and edit blades

    # note use of the $errors laravel supplied variable and then the @error / @enderror directive

    # note route model binding mechanism where we ask Laravel to supply an Article object in the show function
    # Laravel looks at the route and the function and will instantiate an Article object for us:

        # it effectively does the following, where the id=1:
        Article::where('id', 1)->first();
        # and passes that object.  See the show method two versions
        # parameter names must match between route and controller function

        # check out the Laravel Model function getRouteKeyName() which can be used to use a column name other than id

    # Laravel helps with the combining of validation and create/update methods, also see fillable/guarded protected member variables

    # Laravel named routes assists with route maintenance, by avoiding duplication

    # we can add the path() function to the Article class to give us a neat helper method to access the route to a given article show() function

Eloquent Relationships

    Say that a user can add articles.  We can then say that a user 'has many' articles

        $user->articles

    Relationships which cover most situations:

        hasOne
        hasMany
        belongsTo

    Plus, belongsToMany, will handle most things

    Two more, more complex:

        morphMany
        morphToMany

    To get going we need to add a foreign key to the Article table, using the migration class
    This is ok, but clears our data, too
    We can use the UserFactory class to add a dummy user

    It uses the Faker library for this purpose

        php artican tinker

        > factory(App\User::class)->create();

    The factory call will trigger the define function in the UserFactory class to add a new dummy user

    To add 4 users:

        > factory(App\User::class, 4)->create();

    We can make a factory for Articles:

        php artisan help make:factory
        php artisan make:factory ArticleFactory -m "App\Article"

    Inside the factory we use Faker to build some data and in fact also reference the User factory for a new user, too

    We can also override any attributes we wish, so to make a set of articles all owned by the same user:


        php artican tinker

        > factory(App\Article::class)->create(['user_id' => 3]);
        > factory(App\Article::class, 2)->create(['user_id' => 5]);

    One to many relationship: user to article for author
    Many to many relationship: article and tag

    # nb had to run autoload load rebuild to see new seeder class

        composer dump-autoload

    Looked at the use of attach and detach to automatically set the articles to tags records

For the auth chapters I created a new project 'laravel6'

	I edited the .idea/laravel6.iml file to exclude node_modules from indexing.  It wasn't there in fact but I wanted to get in and exclude it in case we do install node modules.
	
	Installed Laravel ui artisan library because we are gong to need it
	
		composer require laravel/ui --dev
		
	It adds a ui section and a ui artisan command
	
	See:
		php artisan help ui

	We use the ui command to swap out the front end scaffolding (hmm?)
	
	Jeffrey Way says any of the 3 options would work but he prefers vue:
	
		php artisan ui vue --auth
		
	The --auth option installs the authentication UI scaffolding, too
	
	We must run the install and recompilation of assets following this command
	
		npm install && npm run dev
		
	We get the registration and login for free, once we've created the database and run migrate
	
	Laravel HomeController uses the $this->middleware('auth') function to authenticate the login user
	
	See the corresponding auth sections in the home view file
	
	Password reset processing:
		1. Click "Forgot Password' link
		2. Fill out a form with their email address
		3. Prepare a unique token and associate it with the user's account
		4. Send an email with a unique link back to our website thatconfirms email ownership
		5. Link back to website, confirm the token, and set a new password
		
	By default emails are going to mailtrap.io, which is a way of testing emails.  They go to the mailtrap server but do not actually go out to the target mail address
	
	Another testing option is to use the 'log' driver
	
	We tested the reset password process provided by laravel and then stepped through the code, which uses traits and a bunch of built in facilities to generate a token, send an email, reset and save the new password, etc
	
	COLLECTIONS
	
	Using tinker we can run:
	
		App\Article::first();
		
	Which gives us back a single object
	
		App\Article::all();
		
	Works, and gives us back a collection.  In tinker run:
	
		$articles = App\Article::all();
		$articles->first(function ($article) { return strlen($article->title) < 5; });
		
	See the collection functions.  Also can create a collection using the collect() function:
	
		collect(['one', 'two','three']);
		collect(['one', 'two','three'])->first();
		etc.
		
		collect(['one', 'two','three', ['four', 'five', 'six']]);
		# Convert to a single array:
		collect(['one', 'two','three', ['four', 'five', 'six']])->flatten();
		
		collect(['one', 'two','three', ['four', 'five', 'six']])->flatten()->flip();
		
	All the collection functions return a collection so they can be chained together
	
	We can obtain a collection of Articles with their tags like so, using eager loading:
	
		$articles = App\Article::with('tags')->get();
		
	We can use the pluck() function to pick out an attribute from all articles in our collection:
	
		$articles->pluck('title');
		$articles->pluck('tags');
		etc.
		
	We can use chained functions to get all the tag names:
	
		$articles->pluck('tags')->collapse()->pluck('name');
		
	collapse() function is similar to flatten()but works with objects
	
	And we can squeeze out duplicates with:
		
		$articles->pluck('tags')->collapse()->pluck('name')->unique();
		
	There is a pseudo collapse function which allows us to use the dot operator in the collection functions:
	
		$articles->pluck('tags.*.name')->collapse()->unique();
		
	That achieves the same thing as before and is a little more brief
	
	
Service Container

	It is a container for storing and retrieving services
	
	A service in this context can be anything, a string, object, collection, etc
	
	The container binds the 'service' using a key
	
		$container->bind($key, $service);		# stores the service
		
		$container->resolve($key);		# retrieves the service
		
	The mechanism could just be an array in the container object. The example showed a function being stored inside which an object is instantiated using whatever config and other parameters were provided.  The container resolved the contained function by invoking the call_user_func method on it, which caused the object to be instantiated and returned.
	
	Laravel's container is the application itself, obtained using the app() function
	
	So we can use the app() container in the same way.
	
		app()->bind('example', function () { return new Example(); });
		
	We can access the example service using another helper function:
	
		resolve('example');
		
	Jeffery showed these examples working by inserting them into a dummy route, which shows the result in the browser
		
	Let's say we need a config item in order to instantiate our example.  In services.php we can add a new section with the key 'foo' and a corresponding value 'bar'.  In the bid function we can then obtain the services configured value as follows:
	
		app()->bind('example', function () { 
			// get the value configured for foo and pass it to the constructor
			$foo = config('services.foo');
			
			return new Example($foo); 
		});
		
	And we can get it back using 
	
		resolve('example');
		
	But we do this using the Inversion of Control (IoC) container.  By just asking for the Example class:
	
		resolve(App\Example::class);
		
	The container will look for a service of that name, it won't find it, so then it looks into the classes available to it in the class path, it does find it, and returns it.
	
	If the constructor of that class has any objects, they too will be instantiated
	
	A preferred way of doing this is with the make() function, which is identical to the resolve() function:
	
		$example = app()->make(App\Example::class);
		etc.
		
	We can even simply ask for the example object in the function signature:
	
		Route::get('/', function (App\Example $example) {
		
				ddd($example);
		});
		
	This is called 'automatic resolution'.  If possible Laravel will automatically pass in what we need.
	
	If it is impossible to automatically build the object then Laravel will stop with a bind exception.  Now we can explicitly bind the procedure for instantiating the object into the service container so that Laravel can use that instead of the automatic version:
	
		app()->bind('App\Example', function () {
			$obj = new OtherObject();
			$foo = 9098;	// Laravel cannot know how to set this required parameter
			
			return new App\Example($obj, $foo);
		})

	We would probably use App\Example::class as the key in this case.
	
	We would store these kind of functions in the Providers folder, AppServiceProvider class.  See the register()
	function in that class.
	
	public function register() {
	
		// NB Providers have a member variable $app, so we can use $this->app-> instead of app()
	
		app()->bind('App\Example', function () {
			// get instantiation details
			...
			return new App\Example($obj $foo);
		});
		
		etc.
		
		// Also, we may not want multiple instances of the object  We can ask for a singleton
		$this->app->singleton('App\Example', function () {
			// get instantiation details
			...
			return new App\Example($obj $foo);
		});		
	}

	When testing invoke the resolve() function twice to show that the same object is returned in the singleton example
	
Facades

	We can use a helper function to return a view, as in:
	
		...
		return view('welcome');
		
	Can also use a Laravel facade to achieve the same thing:
	
		return View::make('welcome');

	Looking at the code for any facade, we find
	
		protected static function getFacadeAccessor() {
			return '<facade name>';
		}
		
	The facade name returned is the key into the service container by which we obtain the service object to perform the desired function.
	
	So when we say 
	
		\View::make();
		
	The View is a static interface which proxies to the underlying class which does the actual work
	
	Another example using the File facade
	
		File::get(public_path('index.php'));
		
	You can see in the facade class that the underlying class is @see \Illuminate\Filesystem\Filesystem
	
	We can use dependency injection to bring this class into the action method:
	
	    public function index(Filesystem $file)
	    {
			// Now we don't need to use the facade at all, we can use the file system object directly
			// This is EXACTLY the same thing a the following line using the facade
			return $file->get(public_path('index.php'));
		
	        return File::get(public_path('index.php'));

	        // Can use the request helper function or the Request facade
	//        return request('name');
	        return Request::input('name');

	        // an use the view helper or the View facade
	//        return view('home');
	        return View::make('home');
	    }

	There are facades for almost all of the Laravel framework.  Check out the all in the Facade folder.
	
	E.g.
	// Remember somethng for 60 seconds and use the function when referenced
		Cache::remember('foo', 60, function () {
			return 'bar';
		});
		
		Cache::get('foo');


	Jeffrey warns about over using the facades because they end up being scattered throughout the code and are a hidden dependency.  If you are using lots of the perhaps the scope of the class has grown too big and should be refactored.  If you use dependency injection of these objects in the constructor at least you can see what is being used just by looking at the constructor method.
	
	Also, he tends to use the helper functions when in the Model layer, and will use facades in the controllers.  Just a style thing really.
	

Service Providers

	In the Laravel framework directory, you can see that the framework is split up into components.  Each component has a service provider class.  Each of these provide a service to the framework.  It may register one or more keys in the service container or trigger some functionality after the framework has been booted.
	
	Any service provider can implement two methods, register() and boot()

	Look at Filesystem.  It registers a singleton called 'files'.
	
	There is no boot() method here but if there was it would get called after all the register methods have been called.
	
	The list of providers that get called is in the config/app.php configuration file under 'providers'.
	
	So they all get called, all the service providers get registered then all the boot functions get called.  Thus, all the various services they provide get registered can can be used later by resolving the service from the container.
	
	Accessed:
		resolve('files')->get('<file>');
		
		or, using the app() alias
		
		app('files')->get('<file>');

	So, the service providers register the service identifying each one by its key and the facades access the service using the same key.
	
	
Vue 2 - following the Step by step course in Laracasts
@see vue2 project

    Kick off in Chrome with http://vue2.test

    See the index.html + main.js pairs in the project which show the code for each instalment

    The html and custom css is provided by bulma.  They provide a set of components which give great functionality
     and are a starting point for bullet lists, tabs etc

     @see https://bulma.io/documentation/components/


    Episode 12

        We use the v-on mechanism for communicating between the child component and its parent.

        The parent says when the event is applied then let me know via this method:

            <div id="root" class="container">

                <coupon v-on:applied="method"></coupon>

                # such as
                <coupon v-on:applied="onCouponApplied"></coupon>

                # or this is equivalent
                <coupon @applied="onCouponApplied"></coupon>

            </div>

    Episode 13

        In episode 12 we saw how easy it was for the child component to communicate with the parent (root) instance
        by broadcasting an event, which the parent listened for.  In this episode we look at communicating between
        other components, which are perhaps siblings, not just parent child.

        Note that we can always instantiate a new instance of vue:

            window.Event = new Vue();

        We can use this to globally fire events upon which other components will act.

        Any view instance can emit and listen for event

        NB we emit using $emit and listen using $on

    Episode 14

        using named slots when you want multiple pieces of content

        we start by getting the html code for a more traditional, classic modal dialog from bulma:

            https://bulma.io/documentation/components/modal/

    Episode 15

        using a view specific component, i.e. a component for use on a particular page, not meant to be reused and
        not part of a SPA (single page application) application

        we use an inline template, which is defined or generated into the html, @see main15/index15


