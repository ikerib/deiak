#set :deploy_config_path, "/config/deploy.rb"
#set :stage_config_path, "/config/deploy/"

# Load DSL and set up stages
require 'capistrano/setup'

# Include default deployment tasks
require 'capistrano/deploy'
require 'capistrano/composer'
require 'capistrano/symfony'


Dir.glob('/tasks/*.cap').each { |r| import r }
