@include('errors.partials.error-page', [
    'code' => '400',
    'title' => 'Bad Request',
    'description' => 'The request could not be understood by the server due to malformed syntax.',
])
