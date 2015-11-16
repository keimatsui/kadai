ASIN=4873115655 のレビューを調べる。

作業ディレクトリを作成する。

```
export asin=4873115655
mkdir $asin
cd $asin
```

□上記の意味がわかってから先に進む。

http://www.amazon.co.jp/product-reviews/4873115655 を開き、レビューのページ数を確認する。（本記事執筆時点で6）

1ページ目、http://www.amazon.co.jp/product-reviews/4873115655/ref=cm_cr_dp_see_all_btm?pageNumber=1 を取得する。

```
wget -O 1.html http://www.amazon.co.jp/product-reviews/${asin}/ref=cm_cr_dp_see_all_btm?pageNumber=1
```

□上記の意味がわかってから先に進む。

「○人中●人が」の○を取り出す。sedの正規表現と後方参照を用いる。

```
sed -n 's/.*>\([0-9]*\)\s*人中.*/\1/p' 1.html
```

□上記の意味がわかってから先に進む。

最初の2件をスキップする。

```
sed -n 's/.*>\([0-9]*\)\s*人中.*/\1/p' 1.html | awk 'NR>2'
```

□上記の意味がわかってから先に進む。

```
sed -n 's/.*>\([0-9]*\)\s*人中、\([0-9]*\)\s*人の方が.*5つ星のうち\s*\([0-9\|\.]*\).*/\1 \2 \3/p' 1.html | awk 'NR>2'
```

□上記の意味がわかってから先に進む。

ここまでの方法は、未評価のレビューに対応していない。

ASIN=400339481X のレビューを調べる。（本記事執筆時点で未評価のレビューがある）

作業ディレクトリを作成する。

```
cd ..
export asin=400339481X
mkdir $asin
cd $asin
```

1ページ目、http://www.amazon.co.jp/product-reviews/400339481X/ref=cm_cr_dp_see_all_btm?pageNumber=1を取得する。

```
wget -O 1.html http://www.amazon.co.jp/product-reviews/${asin}/ref=cm_cr_dp_see_all_btm?pageNumber=1
```

```
sed 's/.*>\([0-9]*\)\s*人中、\([0-9]*\)\s*人の方が.*5つ星のうち\s*\([0-9\|\.]*\).*投稿者.*/RESULT \1 \2 \3/' 1.html |\
sed 's/.*5つ星のうち\s*\([0-9\|\.]*\).*投稿者.*/RESULT 0 0 \1/' |\
sed -n 's/^RESULT\s\(.*\)/\1/p' |\
awk 'NR>2'
```

□上記の意味がわかってから先に進む。

あとはこれをすべてのページについて実行すればよい。（ASINとページ数を与えたら、上記を繰り返すようなシェルスクリプトを書いてもよい。）
