<?
/**
 * POST		/blog-posts
 * GET		/blog-posts/:blog-post-url-title
 * PATCH	/blog-posts/:blog-post-url-title
 */

if($_SERVER['SCRIPT_NAME'] == '/blog-posts'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		$blogPost = BlogPost::GetByUrlTitle(HttpInput::Str(GET, 'blog-post-url-title'));

		if($blogPost->Published > NOW && (HttpInput::$RequestMethod == Enums\HttpMethod::Get || HttpInput::$RequestMethod == Enums\HttpMethod::Head)){
			throw new Exceptions\BlogPostNotFoundException();
		}

		HttpInput::RouteRequest(resource: $blogPost);
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
