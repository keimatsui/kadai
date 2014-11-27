package com.amazon.associates.sample;

import java.net.*;
import java.util.regex.*;
import org.apache.commons.io.*;

/**
 *
 * @author yabuki
 */
public class Sample3 {

  public static void main(String[] args) throws Exception {
    String asin = "400339481X";
    if (args.length != 0) {
      asin = args[0];
    }
    String charset = "Shift_JIS"; //驚くところ！
    String urlStr = "http://www.amazon.co.jp/product-reviews/" + asin + "/";
    URL url = new URL(urlStr);
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
    System.err.printf("%s %s\n", connection.getResponseCode(), connection.getResponseMessage());
    String responseBody = IOUtils.toString(connection.getInputStream(), charset);

    Pattern pattern = Pattern.compile("([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)");
    Matcher matcher = pattern.matcher(responseBody.replace("\n", ""));//改行削除
    while (matcher.find()) {
      int people = Integer.parseInt(matcher.group(1));
      int helpful = Integer.parseInt(matcher.group(2));
      double star = Double.parseDouble(matcher.group(3));
      System.out.printf("星%f。%d人中%d人が参考になった。\n", star, people, helpful);
    }
  }
}
