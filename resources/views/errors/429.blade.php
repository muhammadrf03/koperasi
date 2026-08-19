@include('errors.partials.error-page', [
    'code' => '429',
    'title' => 'Too Many Requests',
    'description' => 'You have made too many requests. Please wait a moment and try again.',
])
