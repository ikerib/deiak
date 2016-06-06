# config valid only for current version of Capistrano
lock '3.4.0'

set :default_stage, "prod"
set :application, 'deiak'
set :repo_url, 'https://github.com/PasaiakoUdala/deiak.git'
#set :repo_tree, -> { "#{fetch(:deploy_to)}/repo" }

set :linked_files, %w{app/config/parameters.yml}
set :linked_dirs, %w{var/logs var/cache var/sessions}
set :keep_releases, '5'

# Default value for :scm is :git
set :scm, 'git'

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :pty is false
# set :pty, true

after 'deploy:starting', 'composer:install_executable'
