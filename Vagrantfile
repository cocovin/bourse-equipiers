VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "ubuntu/trusty64"

  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "private_network", ip: "33.33.33.10"

  config.vm.synced_folder ".", "/vagrant", type: "nfs", mount_options: ['nolock', 'vers=3', 'udp', 'actimeo=1']

  config.vm.provision :ansible do |ansible|
    ansible.playbook = "etc/ansible/provision.yml"
  end

  config.vm.provider :virtualbox do |vb|
        # These statements aim at speeding up network (empiric)
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
        vb.customize ["modifyvm", :id, "--memory", "2048"]
    end

end
