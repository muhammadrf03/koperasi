@include('errors.partials.error-page', [
    'code' => '500',
    'title' => 'Internal Server Error',
    'description' => 'Something went wrong on our end. Please try again later.',
])
