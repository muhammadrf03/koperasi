@include('errors.partials.error-page', [
    'code' => '502',
    'title' => 'Bad Gateway',
    'description' => 'The server received an invalid response from the upstream server.',
])
