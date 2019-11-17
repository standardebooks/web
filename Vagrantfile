Vagrant.configure("2") do |config|
	config.vm.box = "ubuntu/bionic64"
	config.vm.provision :shell, path: "scripts/vagrant/provision",
		args: ["se-tools"]
	config.vm.network :forwarded_port, guest: 80, host: 8080
	config.vm.synced_folder ".", "/standardebooks.org",
		owner: "www-data", group: "www-data",
		mount_options: ["dmode=775,fmode=777"]
end
