Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/bionic64"
  config.vm.provision :shell, path: "scripts/vagrant/provision.sh"
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.synced_folder ".", "/standardebooks.org",
	  owner: "www-data", group: "www-data"
end
