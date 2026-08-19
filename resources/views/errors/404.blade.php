@include('errors.partials.error-page', [
    'code' => '404',
    'title' => 'Page not found',
    'description' => 'The page you are looking for does not exist or has been moved.',
    'homeHref' => '/',
    'homeLabel' => 'Go home',
    'browseHref' => '/',
    'browseLabel' => 'Browse pages',
])
