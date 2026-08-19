@include('errors.partials.error-page', [
    'code' => '401',
    'title' => 'Unauthorized',
    'description' => 'You need to log in to access this page.',
])
