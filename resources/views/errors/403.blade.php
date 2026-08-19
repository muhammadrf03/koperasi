@include('errors.partials.error-page', [
    'code' => '403',
    'title' => 'Forbidden',
    'description' => 'You do not have permission to access this page.',
])
