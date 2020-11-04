set :branch, 'development'

set :pty, true

set :linked_dirs, %w{vendor node_modules frontend/runtime frontend/web/uploads backend/runtime backend/web/uploads}
set :linked_files, %w{backend/web/index-test.php backend/web/index.php backend/config/params-local.php backend/config/main-local.php console/config/params-local.php console/config/main-local.php frontend/web/index-test.php frontend/web/index.php frontend/config/params-local.php frontend/config/main-local.php common/config/params-local.php common/config/main-local.php yii}

server '18.220.101.56',
  user: 'ubuntu',
  ssh_options: {
    keys: ['~/.ssh/wot-development.pem'],
    forward_agent: true,
    auth_methods: ["publickey"]
  }

parms = {
   text: "Starting deployment of branch:develop to staging server beta.smeincusa.com",
   channel: "#smeincusa",
   username: "smeincusa-Bot",
 }

 uri = URI.parse("https://hooks.slack.com/services/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")
 http = Net::HTTP.new(uri.host, uri.port)
 http.use_ssl = true

 request = Net::HTTP::Post.new(uri.request_uri)
 request.body = parms.to_json

 response = http.request(request)

desc "Install Dependencies and Compile Assets"
task :dependencies_and_assets do
    on roles(:all) do
        within release_path do
            info "Installing Dependecies at:"
            execute "pwd"
            execute "sudo chmod -R 777 /app/"
            execute "sudo chown -R ubuntu:ubuntu /app/"
            execute "cd #{release_path} && grunt prod"
            execute "cd #{release_path} && sudo chown -R ubuntu:ubuntu backend/web/assets"
            execute "cd #{release_path} && sudo chown -R ubuntu:ubuntu frontend/web/assets"
            execute "cd #{release_path} && sudo chown -R ubuntu:ubuntu backend/runtime"
            execute "cd #{release_path} && sudo chown -R ubuntu:ubuntu frontend/runtime"
            execute "cd #{release_path} && sudo chmod -R 0777 backend/web/assets"
            execute "cd #{release_path} && sudo chmod -R 0777 frontend/web/assets"
            execute "cd #{release_path} && sudo chmod -R 0777 backend/runtime"
            execute "cd #{release_path} && sudo chmod -R 0777 frontend/runtime"
            execute "cd #{release_path} && sudo chmod 0775 yii"
            execute "cd #{release_path}/frontend/web && sudo chown ubuntu:ubuntu uploads"
            info "Dependencies installed and assets compiled successfully and a whole bunch of other stuff"
        end
    end
end

desc "Complete Deployment"
task :complete_deployment do
    on roles(:all) do
        within release_path do

            execute "cd #{release_path} && ln -s #{release_path}/backend/web #{release_path}/frontend/web/admin"
            info "Deployment to Beta server completed"
            parms = {
                text: "Deployment completed successfully",
                channel: "#smeincusa",
                username: "smeincusa-Bot",
            }

            uri = URI.parse("https://hooks.slack.com/services/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")
            http = Net::HTTP.new(uri.host, uri.port)
            http.use_ssl = true

            request = Net::HTTP::Post.new(uri.request_uri)
            request.body = parms.to_json

            response = http.request(request)
        end
    end
end