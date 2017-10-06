# 深層学習

## 深層学習用計算機

矢吹研究室では、深層学習用の以下の計算機を用意している。

+ deep01
+ deep02：10.100.192.22（Core i7 6700K, 主記憶64GB, GeForce 1080GTX）

これらのマシンがどのように作られているかは、machines.mdを参照。

## 計算機の使い方

### ログイン

矢吹から指定されたユーザ名とパスワードで、SSHでログインする。WindowsマシンからはTeraTermなどのアプリで、Vagrant仮想マシンからはsshコマンドで入れる。

パスワードを変更したければ、ログイン後に`passwd`。下記の共有フォルダのパスワードは（たしか）変更されないことに注意。

### 共有フォルダ

サーバのファイルにWindowsマシンからアクセスする方法は、研究室の共有フォルダにアクセスする方法と同じである。（deep02なら\\10.100.192.22\ユーザ名）

### 自分用の環境構築

#### Python (Anaconda)

Anacondaのインストール https://www.continuum.io/downloads#linux から最新版をダウンロードしてもよい。

```
bash ~yabuki/Anaconda3-4.2.0-Linux-x86_64.sh
```

#### 環境変数

開発のために必要な環境変数を`.bash_protile`に追記する。この設定は一度だけ行えばよい。変更する場合は、`nano .bash_profile`などとして、ファイルを編集する。

```
cat << "EOF" >> .bash_profile
export CUDA_HOME=/usr/local/cuda
export CUDA_PATH=$CUDA_HOME
export PATH=$HOME/anaconda3/bin:$CUDA_HOME/bin:$PATH
export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:$CUDA_HOME/lib64:$CUDA_HOME/extras/CUPTI/lib64
EOF
```

ログインし直すか、`source .bash_profile`

##### Pythonの仮想環境

以下のように仮想環境を使うと、Pythonの環境がごちゃごちゃにならなくてよい。

1. 環境を構築する：`conda create -n 環境名`
1. 環境に入る：`source activate 環境名`
1. 環境から出る：`source deactivate 環境名`

#### GPUの確認

```
nvidia-smi
```

### 深層学習用ライブラリ

#### Chainer

新環境構築

```
conda create -n chainer
```

新環境利用開始

```
source activate chainer
```

chainerのインストール

```
CUDA_PATH=/usr/local/cuda pip install chainer --no-cache-dir -vvvv
```

Version 1.20.0のサンプルを取得

参考：https://groups.google.com/forum/#!topic/chainer-jp/ViW_gViL0rM

```
git clone https://github.com/pfnet/chainer.git
cd chainer
git checkout refs/tags/v1.20.0
```

MNISTの実行

```
cd examples/mnist

#CPU版の動作確認
python train_mnist.py

#GPU版の動作確認
python train_mnist.py --gpu=0
```

新環境利用終了

```
source deactivate chainer
```


#### TensorFlow

新環境構築

```
conda create -n tf
```

新環境利用開始

```
source activate tf
```

TensorFlowのインストール

```
pip install tensorflow-gpu
```

動作確認

```
python -m tensorflow.models.image.mnist.convolutional
```

新環境利用終了

```
source deactivate tf
```
