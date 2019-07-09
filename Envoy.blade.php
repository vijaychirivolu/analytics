@servers(['web' => 'deploy@142.93.125.4'])

@task('foo', ['on' => 'web'])
    ls -la
@endtask
