<?php

namespace Deployer;

require 'recipe/laravel.php';

// Configuration

set('repository', 'ssh://git@gitlab.connortumbleson.com:22774/iBotPeaches/PandaLove.git');
set('keep_releases', 1);
set('git_tty', false);

add('shared_files', []);
add('shared_dirs', [
    'public/uploads',
]);
add('writable_dirs', []);

// Hosts
host('pandalove.club')
    ->stage('production')
    ->identityFile('~/.ssh/id_rsa')
    ->forwardAgent(true)
    ->port(22774)
    ->user('pandalove')
    ->set('deploy_path', '/home/pandalove/deploys');

// Tasks

desc('Restart PHP-FPM service');
task('gulp:asset', function () {
    cd('{{release_path}}');
    run('/opt/yarn-v1.7.0/bin/yarn add gulp');
    run('gulp --production');
});
after('deploy:symlink', 'gulp:asset');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
