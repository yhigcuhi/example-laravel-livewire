# Docker EC2最短作成
## EC2 生成
1. インスタンス起動
1. OSは AWS Linux 2023で一旦（特に決めてない）
1. key pairは手元にあるやつ
1. VPCを選択し、public 内にて セキュリティグループとしてはssh 0.0.0.0の22で繋げるやつ用意
1. 後の設定は任意
1. SSHで接続
1. [ssh config変更](https://qiita.com/kenkono/items/434070d17097115ec5d8)
1. セキュリティグループ設定 SSH Custom PORT
1. 必要なものインストール

## SSH CONFIG ProxyJump
```config
Host test-bastion
  HostName AAA.AAA.AAA.AAA
  IdentityFile ~/.ssh/key/xxx.pem
  User ec2-user
  ForwardAgent yes
  Port カスタムポート
Host test-ec2-app
  # プライベートないのs
  HostName BBB.BBB.BBB.BB
  User ec2-user
  IdentityFile ~/.ssh/key/専用あれば.pem
  ProxyJump test-bastion
  Port そいつ用ポート
```
## EC2 生成コマンド
```json
{
  "MaxCount": 1,
  "MinCount": 1,
  "ImageId": "ami-0506f0f56e3a057a4",
  "InstanceType": "t2.micro",
  "KeyName": "test-bastion",
  "EbsOptimized": false,
  "NetworkInterfaces": [
    {
      "SubnetId": "subnet-0e6f4bf216327e22d",
      "AssociatePublicIpAddress": true,
      "DeviceIndex": 0,
      "Groups": [
        "<groupId of the new security group created below>"
      ]
    }
  ],
  "TagSpecifications": [
    {
      "ResourceType": "instance",
      "Tags": [
        {
          "Key": "Name",
          "Value": "test-ec2-docker"
        }
      ]
    }
  ],
  "MetadataOptions": {
    "HttpTokens": "required",
    "HttpEndpoint": "enabled",
    "HttpPutResponseHopLimit": 2
  },
  "PrivateDnsNameOptions": {
    "HostnameType": "ip-name",
    "EnableResourceNameDnsARecord": false,
    "EnableResourceNameDnsAAAARecord": false
  }
}
```

## AWS Linux 2023 構築
### 初め
sudo dnf update
### Git install
sudo dnf install -y git

### Docker install
[参考 その1](https://zenn.dev/rock_penguin/articles/28875c7b0a5e30)
[参考 その2](https://oddguy.hatenablog.com/entry/2023/05/16/104117)

```bash
sudo dnf install -y docker
sudo systemctl start docker
sudo chmod 666 /var/run/docker.sock
sudo service docker restart
```
## Docker Compose install
[参考 その1](https://zenn.dev/rock_penguin/articles/28875c7b0a5e30)
```bash
sudo curl -L "https://github.com/docker/compose/releases/download/v2.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### Docker laravelとして...
Laravel 資材としての準備
1. cp .env.example .env
1. php artisan key:generate
1. php artisan storage:link

ホスト側に戻り
curl localhost:8080 → パーミッションエラー
1. sudo chown -R ec2-user:ec2-user ~/example-laravel-livewire/
1. chmod 777 -R ~/example-laravel-livewire/laravel-src/storage/

nginx → 80へ

# LAMP EC2最短作成s
## EC2 生成
docker 同様のため略...

## AWS Linux 2023 構築
### 初め
sudo dnf update
### Git install
sudo dnf install -y git
### LAMP 構築
#### Apache
1. dnf list | grep httpd
1. sudo dnf install -y httpd
#### PHP
1. dnf list | grep php
1. sudo dnf install -y php8.2.x86_64
※ composer の都合から
1. sudo dnf install -y  php-fpm php-mysqli php-json php-devel
#### MySQL (Mariadb)
1. dnf list | grep mariadb
1. sudo dnf install -y mariadb105-server
※ 10.5しかない...
#### LAMP 組み立てる
1. sudo systemctl start httpd
1. sudo systemctl is-enabled httpd
1. sudo usermod -a -G apache ec2-user
1. sudo chown -R ec2-user:apache /var/www
1. sudo systemctl start mariadb
1. sudo mysql_secure_installation
※ root パスワード変更とか
1. sudo systemctl enable mariadb

#### Laravel 動かせるようにする
1. cd /var/www/html
1. git clone
1. composer install
```bash
# composer install
cd
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e5325b19b381bfd88ce90a5ddb7823406b2a38cff6bb704b0acc289a09c8128d4a8ce2bbafcd1fcbdc38666422fe2806') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
# composer コマンド使えるように
sudo mv composer.phar /usr/local/bin/composer
# 確認
php /usr/local/bin/composer
# ec2-user コマンド使えるように
echo "export PATH=~/.config/composer/vendor/bin:$PATH" >> ~/.bash_profile
source ~/.bash_profile
# git 落としたやつ composer install 略...
```

※ ↓　から面倒になって試してない
1. laravel .env 設定値変更(mysql繋ぐとか)
1. httpd.conf document root変更
/var/www/html/gitのプロ..略/laravelの中/public
※ php artisan serve でいいのかな？
