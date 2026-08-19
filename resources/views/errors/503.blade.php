@include('errors.partials.error-page', [
    'code' => '503',
    'title' => 'Service Unavailable',
    'description' => 'The service is temporarily unavailable. Please try again later.',
])
