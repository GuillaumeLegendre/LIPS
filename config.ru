require 'rubygems'
require 'sinatra'
require './app'

set :run, false
set :environment, :production
set :raise_errors, true

run Sinatra::Application
