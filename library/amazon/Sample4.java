package com.amazon.associates.sample;

import java.net.*;
import java.util.*;
import java.util.regex.*;
import org.apache.commons.io.*;

/**
 *
 * @author yabuki
 */
public class Sample4 {

  public static void main(String[] args) throws Exception {
    String asin = "400339481X";
    String charset = "Shift_JIS"; //驚くところ！
    String urlStr = "http://www.amazon.co.jp/product-reviews/" + asin + "/";
    URL url = new URL(urlStr);
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
    System.err.printf("%s %s\n", connection.getResponseCode(), connection.getResponseMessage());
    String responseBody = IOUtils.toString(connection.getInputStream(), charset);

    Pattern pattern = Pattern.compile("([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)");
    Matcher matcher = pattern.matcher(responseBody.replace("\n", ""));//改行削除
    List<Review> reviews = new LinkedList<>();
    while (matcher.find()) {
      int people = Integer.parseInt(matcher.group(1));
      int helpful = Integer.parseInt(matcher.group(2));
      double star = Double.parseDouble(matcher.group(3));
      reviews.add(new Review(star, people, helpful, 1));
    }
    if (responseBody.contains("評価が高い有用性のあるレビュー")) {//最初の2件は重複
      reviews.remove(0);
      reviews.remove(0);
    }

    double totalStar = 0;//星の総数
    double totalStarWeighted = 0;//星の重み付き総数
    double totalWeight = 0;//重み
    for (Review r : reviews) {
      System.out.println(r);
      double star = r.getStar();
      totalStar += star;
      double weight = 1.0 * r.getHelpful() / r.getPeople();
      totalStarWeighted += star * weight;
      totalWeight += weight;
    }
    int numReviews = reviews.size();
    System.out.println("レビュアー数：" + numReviews);
    System.out.println("平均スター：" + totalStar / numReviews);
    System.out.println("平均スター（重み付き）" + totalStarWeighted / totalWeight);

  }
}
