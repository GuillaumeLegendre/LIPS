require "sinatra"
require "sinatra/json"
require "json"
require 'open3'


class Sinatra::Application


  ### Uncomment if session is required
  # configure do
  #   enable :session
  # end

  post '/available_languages' do
    json :languages => ["C", "Java", "PHP"]
  end

  post '/compilation' do
    json = JSON.parse(params[:json])
    stdin, stdout, stderr, wait_thr = Open3.popen3(json["languages"], '-e '+ json["code"])
    stdout = stdout.gets(nil)
    stderr = stderr.read()#gets(nil)
    # puts exit_code = wait_thr.inspect
    # puts stdin = stdin.inspect

    json "test" => {"stdout" => stdout.to_s, "stderr" => stderr.to_s}
  end

  error 404 do
    'Not Found'
  end
end