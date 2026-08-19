@include('errors.partials.error-page', [
    'code' => '405',
    'title' => 'Method Not Allowed',
    'description' => 'The requested method is not supported for this resource.',
])
