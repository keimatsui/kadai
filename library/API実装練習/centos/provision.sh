tr -d \\r < /vagrant/prefectures.csv > /vagrant/prefectures2.csv
yum -y install httpd php php-mysql php-pdo mariadb-server nano
chkconfig httpd on
chkconfig mariadb on
service httpd start
service mariadb start
ln -s /vagrant/api /var/www/html/api
echo '<?php phpinfo();' > /vagrant/api/info.php
echo "create database tutorial charset=utf8; grant all on tutorial.* to test@localhost identified by 'pass';" | mysql -uroot --default-character-set=utf8
echo "create table prefectures (id int primary key, prefecture varchar(20), prefecture_ruby varchar(50), capital varchar(20), capital_ruby varchar(50), index(prefecture));" | mysql -uroot --default-character-set=utf8 tutorial
echo "load data local infile '/vagrant/prefectures2.csv' into table prefectures fields terminated by ',' ignore 1 lines (id,prefecture,prefecture_ruby,capital,capital_ruby);" | mysql -uroot --default-character-set=utf8 tutorial
sed -i -e "151s/    AllowOverride None/    AllowOverride All/g" /etc/httpd/conf/httpd.conf
cp /vagrant/.htaccess /var/www/html/
chmod 604 /var/www/html/.htaccess
service httpd restart
