<?php
// ※は自分で設定してください
define("ACCESS_KEY_ID"     , '※アクセスキー');//キーを書いたファイルをGitHubに置いてはいけない！
define("SECRET_ACCESS_KEY" , '※シークレットキー');//キーを書いたファイルをGitHubに置いてはいけない！
define("ASSOCIATE_TAG"     , 'inquisitor-22'); // 矢吹
define("ACCESS_URL"        , 'http://ecs.amazonaws.jp/onca/xml'); // 矢吹：修正した

$base_param = 'AWSAccessKeyId='.ACCESS_KEY_ID;

$params = array(); 
$params['Service']        = 'AWSECommerceService';
$params['Version']        = '2011-08-02'; //Versionは基本的には最新のものでOK
$params['Operation']      = 'ItemLookup';
$params['ItemId']         = '4150120145'; // 矢吹：フォームからPOSTされたISBNをここに埋め込む
$params['IdType']         = 'ISBN'; //今回はISBNから情報を取得するのでISBN
$params['SearchIndex']    = "Books"; //今回は本の情報なのでBooks 
$params['AssociateTag']   = ASSOCIATE_TAG;
$params['ResponseGroup']  = 'ItemAttributes,Offers, Images ,Reviews '; // 必要なレスポンスを設定(詳しくは下で説明)
$params['Timestamp']      = gmdate('Y-m-d\TH:i:s\Z');

//パラメータを自然順序付け・昇順で並び替え
ksort($params);

$canonical_string = $base_param;
foreach ($params as $k => $v) {
    $canonical_string .= '&'.urlencode_RFC3986($k).'='.urlencode_RFC3986($v);
}

function urlencode_RFC3986($str)
{
    return str_replace('%7E', '~', rawurlencode($str));
}

$parsed_url = parse_url(ACCESS_URL);
$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";

$signature = base64_encode(
                    hash_hmac('sha256', $string_to_sign, SECRET_ACCESS_KEY, true)
                );

$url = ACCESS_URL.'?'.$canonical_string.'&Signature='.urlencode_RFC3986($signature);

$response = file_get_contents($url); //Amazonへレスポンス

$parsed_xml = null;
// レスポンスを配列で取得
if (isset($response)) {
    $parsed_xml = simplexml_load_string($response);
}

// Amazonへのレスポンスが正常に行われていたら
if ($response && 
    isset($parsed_xml) && 
    !$parsed_xml->faultstring &&
    !$parsed_xml->Items->Request->Errors) {

    foreach ($parsed_xml->Items->Item as $current) {
        // 2で設定したResponseGroupから呼び出したい情報を取得
        $title          = $current->ItemAttributes->Title; // タイトル
        $author         = $current->ItemAttributes->Author; // 著者
        $manufacturer   = $current->ItemAttributes->Manufacturer; // 出版社
        $imgURL         = $current->MediumImage->URL; // 本の表紙の中サイズのURL(サイズは小中大から選べる)  
        $bookURL        = $current->DetailPageURL; // Amazonの本のページのURL

        // 管理しやすいように文字コードの宣言やスペースの削除等を行う
        $title = mb_convert_kana($title, "as", "UTF-8");

        $authors = $author[0];
        // 著者が複数いる場合
        if (count($author) > 1) {
            for ($i = 1; $i < count($author); $i++) {
                $authors = $authors. ",". $author[$i];
            }
        }

        // amazonへのURLを短縮(詳しくは下で)
        $URL = substr($bookURL, 0, 24). "dp/". substr($bookURL, -10);
        
        // 矢吹：試しにタイトルを表示させる
        echo $title;
    }
}