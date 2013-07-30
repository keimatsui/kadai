customer_management
===================

マッキーソフト株式会社『PHPシステム開発の教科書』（技術評論社, 2012）の顧客管理アプリケーション。

##インストール

1. XAMPPのインストール：http://www.apachefriends.org/jp/xampp-windows.html からXAMPP Windows版のインストーラをダウンロードし、実行する。インストールの様子を撮影した動画がhttp://youtu.be/WPb3xVddtxg にあるが、Microsoft Visual C++ 2008 SP1 Redistributabe Package (x86)はインストールしなくても大丈夫かもしれない。
1. サービスの起動：ApacheとMySQLを起動する。
1. ファイルの準備：https://github.com/taroyabuki/customer_management/archive/master.zip をダウンロードし、`C:/xampp/htdocs`に展開する。（ディレクトリ構成がハードコーディングされているため、展開したディレクトリの名前をcakeに変える必要がある。`C:/xampp/htdocs/cake/app/...`というディレクトリ構造になればよい。）
1. データベースの作成：[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/) にアクセスし、「インポート」をクリック、`C:/xampp/htdocs/cake/custom/customer_management_preparation.sql`をインポートする（ファイルを指定する以外はデフォルトのままでよい）。「SQL」をクリックし、`customer_management_preparation.sql`の中身を貼り付けて実行してもよい。
1. 管理ユーザの作成：[http://localhost/cake/users/add](http://localhost/cake/users/add) にアクセスし、管理ユーザを作成する(UsernameとPasswordだけ入力すればよい)。
1. ウェブアプリの利用開始：[http://localhost/cake/](http://localhost/cake/) にアクセスし、上で作成したユーザ情報を入力すると、アプリを利用することができる。

###オプショナル（必須ではない）

####Unicode追加面への対応

「[![𠮷](http://www.unicode.org/cgi-bin/refglyph?24-20BB7)](http://www.unicode.org/cgi-bin/GetUnihanData.pl?codepoint=20bb7)[![野](http://www.unicode.org/cgi-bin/refglyph?24-91CE)](http://www.unicode.org/cgi-bin/GetUnihanData.pl?codepoint=91CE)[![家](http://www.unicode.org/cgi-bin/refglyph?24-5BB6)](http://www.unicode.org/cgi-bin/GetUnihanData.pl?codepoint=5bb6)」の「[![𠮷](http://www.unicode.org/cgi-bin/refglyph?24-20BB7)](http://www.unicode.org/cgi-bin/GetUnihanData.pl?codepoint=20bb7)」のような文字を使いたい場合は、途中で以下の作業を行う（「吉」を使うのが正解ではあるが）。

1. `customer_management_preparation.sql`の`utf8`を`utf8mb4`に置換する。
1. `database.php`の`'encoding' => 'utf8',`を`'encoding' => 'utf8mb4',`に置換する。

##アンインストール

最初からやり直したいときは、以下の手順でウェブアプリをアンインストールし、上述の手順1に戻ればよい。

1. データベースの削除：[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/) にアクセスし、「データベース」をクリック、customer_managementをチェックして削除する。「SQL」をクリックして「`DELETE DATABASE customer_management`」というSQL文を実行してもよい。
1. ファイルの削除：`C:/xampp/htdocs/cake`を削除する。
