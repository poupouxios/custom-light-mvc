# vim: set ft=ruby :

Vagrant.configure('2') do |config|

  config.vm.box = 'bento/debian-7.8'

  config.vm.hostname = 'light-mvc'
  config.vm.network :private_network, ip: '192.168.69.95'
  config.vm.network "forwarded_port", guest: 1080, host: 8080 
  config.vm.network "forwarded_port", guest: 1025, host: 8025 
 
  config.vm.synced_folder '~', '/home/master'
  config.vm.synced_folder '.', '/home/vagrant/source'
  config.vm.boot_timeout = 1000;
  
  config.vm.provider :virtualbox do |vb|
    vb.customize ['modifyvm', :id, '--cpus', '1', '--memory', 1024]
  end
 
  config.vm.provision :ansible do |ansible|
    ansible.playbook = 'ansible/playbook.yml'
  end

end
