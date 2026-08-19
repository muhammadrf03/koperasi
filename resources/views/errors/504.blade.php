@include('errors.partials.error-page', [
    'code' => '504',
    'title' => 'Gateway Timeout',
    'description' => 'The server did not receive a timely response from the upstream server.',
])
