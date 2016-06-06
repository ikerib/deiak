set :stage, 'prod'
set :branch, 'master'
set :deploy_to, '/var/www/deiak'
set :tmp_dir, -> { "#{fetch(:deploy_to)}/tmp" }
set :log_level, 'debug'

set :composer_install_flags, '--prefer-dist --no-interaction --optimize-autoloader'
SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"
set :controllers_to_clear, ["app_*.php"]

server '172.28.64.20', user: 'www-data', port: 22, roles: %w{app db web}
