require "net/http"
require "uri"
require "json"

# config valid only for current version of Capistrano
#lock '3.6.0'

set :application, 'smeincusa'
set :repo_url, 'git@git.assembla.com:wideopen/brd-sme.wot-cart.git'

set :branch, ENV['BRANCH'] if ENV['BRANCH']

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/app'

before "deploy:updated", :dependencies_and_assets
after :deploy, :complete_deployment
