cd $1
echo $(pwd)
echo "sudo git config --global --add safe.directory" $1
sudo git config --global --add safe.directory $1
echo "sudo git pull"
sudo git pull
echo $(pwd)
echo Ende
