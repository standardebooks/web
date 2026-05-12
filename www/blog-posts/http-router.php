<?
/**
 * POST		/blog-posts
 * GET		/blog-posts/:blog-post-url-title
 * PATCH	/blog-posts/:blog-post-url-title
 */

if(Http::$Request->RelativePath == '/blog-posts'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		$blogPost = BlogPost::GetByUrlTitle(Http::$Request->QueryString->Get('blog-post-url-title'));

		if($blogPost->Published > NOW && (Http::$Request->Method == Enums\HttpMethod::Get || Http::$Request->Method == Enums\HttpMethod::Head)){
			throw new Exceptions\BlogPostNotFoundException();
		}

		Http::$Request->Route(resource: $blogPost);
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
