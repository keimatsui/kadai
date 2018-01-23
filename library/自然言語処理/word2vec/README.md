# word2vecを用いた短文の主成分分析

## 準備

[研究室公式仮想マシン](https://github.com/yabukilab/machine)にPythonをインストールしてから先に進む。

### MeCab（形態素解析ツール）

```
sudo apt install -y mecab mecab-ipadic-utf8 libmecab-dev
pip install mecab-python3
```

### gensim（word2vec用ライブラリ)

```
pip install gensim
```

### word2vec学習済みモデル

http://www.cl.ecei.tohoku.ac.jp/~m-suzuki/jawiki_vector/ で配布されているものをダウンロード，展開する。（entity_vector/entity_vector.model.txtをテキストエディタで開いてみよ。）

```
cd /vagrant
wget http://www.cl.ecei.tohoku.ac.jp/~m-suzuki/jawiki_vector/data/20170201.tar.bz2

tar xf 20170201.tar.bz2
```

## 練習

`python`としてインタラクティブシェルを起動する。

「父」のベクトルを表示する。

```
import gensim
model = gensim.models.KeyedVectors.load_word2vec_format('entity_vector/entity_vector.model.bin', binary=True)

tmp = model['父']
print(tmp)
```

後の作業のために，カンマ区切りの文字列にする方法を確認する。

```
','.join(map(str, tmp))
```

ベクトルの計算

パリ→「東京→日本」，女→「男→父」，数学→「物理学→ノーベル賞」

```
print(model.most_similar(positive=['日本', 'パリ'], negative=['東京']))
print(model.most_similar(positive=['父', '女'], negative=['男']))
print(model.most_similar(positive=['ノーベル賞', '数学'], negative=['物理学']))
```

「本日は晴天なり。」というテキストを，単語のベクトルの平均で表現する。

```
import numpy as np

vec = model['本日']
vec = np.add(vec, model['は'])
vec = np.add(vec, model['晴天'])
vec = np.add(vec, model['なり'])

print(np.divide(vec, 4))#単語数は4
```

この方法だと，モデルに含まれない単語があると失敗する。

```
model['あああああ']
```

例外が発生しても無視するようにする。（有効な単語をiで数える）

```
i = 0
vec = np.zeros(model.vector_size)#単語ベクトルと同じ要素数（200）のベクトル
try:
  vec = np.add(vec, model['あああああ'])
  i += 1
except:
  pass#無視
```

単語がリストになっている場合は次のとおり。

```
words = ['本日', 'は', '晴天', 'なり', 'あああああ']
vec = np.zeros(model.vector_size)
i = 0
for word in words:
  try:
    vec = np.add(vec, model[word])
    i += 1
  except:
    pass

vec = np.divide(vec, i)
print(vec)
```

文章の場合は，MeCabを使って単語に分ける。

```
import MeCab
mecab = MeCab.Tagger('-Owakati')
tmp = mecab.parse('本日は晴天なり')
print(tmp)
words = tmp.strip().split()
print(words)
```

一連の作業を関数にまとめる。

```
def sentenceToVector(sentence):
  words = mecab.parse(sentence).strip().split()
  vec = np.zeros(model.vector_size)
  i = 0
  for word in words:
    try:
      vec = np.add(vec, model[word])
      i += 1
    except:
      pass
  return np.divide(vec, i)

sentenceToVector('本日は晴天なりあああああ')
```

ここでインタラクティブシェルは終了（Ctrl-D）。

## 本番

仮想マシンの/vagrantで実行する。このフォルダはホストからも見える（c:/vagrant/machine）。

s2v.pyは標準入力から読み込んだテキストに対して，上の処理を行った結果を出力するスクリプトである。

入力データは次のような形式で用意する（test.csv）。最初にラベルを書き，カンマの後にテキストを書く。（最初のカンマのみ，区切り文字として使う。）

```
A,吾輩は猫である。名前はまだ無い。
A,どこで生れたかとんと見当がつかぬ。
B,石炭をば早や積み果てつ。
B,中等室の卓のほとりはいと静にて、熾熱燈の光の晴れがましきも徒なり。
```

実行

```
cat test.csv
```

```
cat test.csv | python s2v.py
```

```
cat test.csv | python s2v.py > vectors.csv
```

### 主成分分析

Pythonでやってもよいが，ここでは（Windowsの）Rを使う。

#### 準備

きれいなbiplotのためのパッケージをインストールする。

```
install.packages(’devtools’)
devtools::install_github(’vqv/ggbiplot’)
```

#### 実行

```
setwd('c:/vagrant/machine')

myData <- read.csv('vectors.csv', head = F)

myResult <- prcomp(myData[, -1])#主成分分析（1列目はラベルだから除外する）

library(ggbiplot)
#1列目でグループ（色）分け
ggbiplot(myResult, var.axes = F, groups = myData[, 1])