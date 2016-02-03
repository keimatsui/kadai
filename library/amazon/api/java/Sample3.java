package com.amazon.associates.sample;

import java.net.*;
import java.util.regex.*;
import org.apache.commons.io.*;

/**
 * ASINで指定したアイテムのレビューを取得する。（1ページ限定）
 * 
 * @author yabuki
 */
public class Sample3 {

  public static void main(String[] args) throws Exception {
    String asin = "400339481X";
    if (args.length != 0) {
      asin = args[0];
    }
    String charset = "UTF-8";
    String urlStr = "http://www.amazon.co.jp/product-reviews/" + asin + "/";
    //String urlStr = "http://www.amazon.co.jp/product-reviews/4873115655?pageNumber=2";//テスト用
    URL url = new URL(urlStr);
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
    System.err.printf("%s %s\n", connection.getResponseCode(), connection.getResponseMessage());
    String responseBody = IOUtils.toString(connection.getInputStream(), charset).replace("\n", "");//改行削除

    Pattern pattern1 = Pattern.compile("([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)");
    Pattern pattern2 = Pattern.compile("5つ星のうち\\s*([0-9|\\.]*)");

    boolean first = true;
    int people;
    int helpful;
    double star;
    for (String aReview : responseBody.split("class=\"a-section review\"")) {//これでHTMLを区切る
      if (first) {//最初は平均
        Matcher matcher2 = pattern2.matcher(aReview);
        if (matcher2.find()) {
          star = Double.parseDouble(matcher2.group(1));
          System.out.printf("星（平均）%f。\n", star);
        }
        first = false;
      } else {//レビュー
        Matcher matcher1 = pattern1.matcher(aReview);
        if (matcher1.find()) {
          people = Integer.parseInt(matcher1.group(1));
          helpful = Integer.parseInt(matcher1.group(2));
          star = Double.parseDouble(matcher1.group(3));
          System.out.printf("星%f。%d人中%d人が参考になった。\n", star, people, helpful);
        } else {
          Matcher matcher2 = pattern2.matcher(aReview);
          if (matcher2.find()) {
            star = Double.parseDouble(matcher2.group(1));
            System.out.printf("星%f。\n", star);
          }
        }
      }
    }
  }
}
