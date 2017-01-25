# 深層学習用マシンの構築

## 参考資料

+ Chainer: http://docs.chainer.org/en/stable/install.html
+ TensorFlow: https://www.tensorflow.org/get_started/os_setup

## Ubuntu 16.04の設定

1. NVIDIAのドライバ（GUI）
1. IPアドレスの設定（GUI）
1. （GUIは何かと便利だから動かしておく。）


```
sudo apt update
sudo apt install -y openssh-server
sudo apt install -y samba ntp emacs build-essential byobu git mysql-server
sudo apt install -y phpmyadmin
sudo apt upgrade -y
sudo apt autoremove -y
sudo apt clean
```

1. NTPの設定
1. MySQL：https://www.digitalocean.com/community/tutorials/how-to-move-a-mysql-data-directory-to-a-new-location-on-ubuntu-16-04 の方法でMySQLのdatadirをHDDに移動した。MySQLを使いたい場合は矢吹に申請すること。

### ユーザの作成

`sudo adduser iwase`などとしてユーザを作成した（パスワードは全員「yabuki」）。

+iwase
+iwahashi
+oki
+oyama
+kato
+kawasaki
+kawabe
+suzuki
+suyama
+nakamura
+masuda

### Sambaの設定  

参考：https://www.hiroom2.com/2016/05/11/ubuntu-16-04%E3%81%ABsamba%E3%82%92%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB%E3%81%97%E3%81%A6windows-10%E3%81%A8%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB%E5%85%B1%E6%9C%89%E3%81%99%E3%82%8B/

`sudo pdbedit -a iwase`などとしてパスワードを設定した（全員「yabuki」）

## CUDA

### CUDA Toolkit 8.0

https://developer.nvidia.com/cuda-downloads のとおり。

```
sudo dpkg -i cuda-repo-ubuntu1604-8-0-local_8.0.44-1_amd64.deb
sudo apt-get update
sudo apt-get install -y cuda
```

### cuDNN

cuDNN v5.1 for CUDA 8.0 https://developer.nvidia.com/rdp/cudnn-download のとおり。

```
#展開してから
sudo cp -P cuda/include/cudnn.h /usr/local/cuda/include
sudo cp -P cuda/lib64/libcudnn* /usr/local/cuda/lib64
sudo chmod a+r /usr/local/cuda/include/cudnn.h /usr/local/cuda/lib64/libcudnn*
sudo ldconfig
```

### その他

```
sudo apt install -y libcupti-dev
```
