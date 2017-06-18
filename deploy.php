<?php
namespace Deployer;

require 'recipe/laravel.php';

// Configuration

set('repository', 'ssh://git@gitlab.connortumbleson.com:22774/iBotPeaches/PandaLove.git');
set('keep_releases', 1);
set('git_tty', true);

add('shared_files', []);
add('shared_dirs', []);
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
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('whoami');
    run('sudo systemctl restart php-fpm.service');
});
after('deploy:symlink', 'php-fpm:restart');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
