# Framework description

The framework used by the SE website is based on Apache URL rewriting working in concert with PHP to deliver correct HTTP responses for HTTP methods. HTTP method logic for resources is contained in files named `http-METHOD.php`, e.g. `http-put.php`. These files cannot be accessed directly via web requests; rather, a file `http-router.php` receives the request and routes it to the appropriate file. Non-method files, like `/help/about.php`, can be accessed directly via the web without any special routing.

HTTP methods may be tunneled via a `_method` parameter in `POST` requests to accomodate HTML forms, which can only issue `GET` and `POST`. For example, a `POST` request with `_method=PUT` will result in the framework executing `http-put.php`.

If a file, resource, or endpoint doesn't exist, the framework returns 404 regardless of authentication, authorization, or allowed methods.

If a file, resource, or endpoint does exist, the framework checks if the method is valid for the request. If it is, it can then respond appropriately. If the method isn't valid, the framework returns 405 with an `allow` header, and `accept-post` and `accept-patch` headers if appropriate.

## Rationale

We want to avoid using an established large framework for the following reasons:

- Such frameworks often include large swathes of functionality that we don't need.

- Buying into a large framework means being on the hook for updates to the framework for its entire lifetime. Frameworks can and do change, and it's undesirable to have to refactor established code to handle a framework refactor years from now, or run on a version of a framework that is no longer receiving security updates.

By designing and maintaining our own small framework, we can include only the parts we need, ensure correctness in HTTP responses, get better performance by handing off much of the work to Apache, and decouple from updates and refactors made by third parties years and decades from now.

The framework aims to leverage Apache's existing URL rewriting capabilities to route requests to files in the filesystem which represent HTTP method logic.

## URL structure

URLs never have trailing slashes. Apache rewrites URLs with trailing slashes to remove the trailing slash and redirects via HTTP 301.

Static files *typically* end in an appropriate extension (e.g. `image.jpg`), but dynamic URLs never end in `.php`. If a request is received for a file ending in `.php`, Apache rewrites it to remove the `.php` extension and redirects via HTTP 301.

## How a request is fulfilled

Requests start at Apache.

Static (e.g., `.xml` or `.jpg`) files are routed directly to real files on the filesystem, and can only accept `GET`, `HEAD`, and `OPTIONS`.

`mod_rewrite` then routes requests for any app resource (e.g. a `user`) that doesn't correspond to a real file on the filesystem. These rewrite rules are stored as `.conf` files in `./config/apache/rewrites/`. Rewrite rules *must not* have the `L` flag specified, as further framework-level processing by `mod_rewrite` occurs after app resource routing.

Dynamic (e.g. `.php`) files only accept `GET`, `HEAD`, and `OPTIONS` by default. In simple cases in which the file behaves like a static file (e.g. a "static" page exists, like `/about.php`), then the file is routed to PHP-FPM for processing and output.

However, if the request is for a resource that should accept other methods, like `POST /employees` or `PATCH `/employees/123`, then the request should be rewritten to direct to a file named `http-router.php`. These files contain logic to determine if the resource exists, and if so, the request is routed to files named `http-{method}.php`. This file has access to a global variable `$resource` which represents the resource located by `http-router.php`.

For example, `PATCH /users/123` -> `/users/http-router.php?userid=123` -> `/users/http-patch.php` with user #123 accessible via the PHP variable `$resource`.

If there is no corresponding `http-{method}.php` file, the framework outputs HTTP 405 with an `allow` header computed from the existing `http-{method}.php` files in the working directory.

Once Apache hands off a PHP file to PHP-FPM for execution, PHP-FPM is configured to automatically include `/lib/Core.php` at the top of the file. Therefore, `Core.php` is the entry point for all PHP scripts executed by PHP-FPM. Execution then continues to the requested file. Classes are auto-loaded from `/lib/`.

## Templates

Template files are stored in `/templates/`. These files expect specific variables to be passed in via the `Template::<function>(<variable>: <value>, ...)` pattern, e.g. `Template::EbookTable(ebooks: $ebooks, border: "red")`. Variable passed in this way are available within the template files themselves. Template stubs with type hints are in PHPDoc in `/lib/Template.php` and *must* reflect the real expected variables in individual template files. Individual template files must also include PHPDoc type hints at the top of each file describing the variables the file expects to be passed.

## Tests

To test the framework, run `run-tests.sh`. If the tests pass, there is no output. If the tests fail, a file `tests-output` is created and can can compared to `tests-golden`, which contains the expected correct test output.

Note that some endpoints may return `POST` in the `allow` header, even though `http-post.php` doesn't exist. This is because since HTML forms only allow `GET` and `POST`, we must tunnel all other methods via `POST` with a `_method` field containing the actual desired method (e.g. `_method=DELETE`). Therefore any endpoint that accepts `DELETE`, `PATCH`, or `PUT` must also accept `POST` to enable this tunneling.
