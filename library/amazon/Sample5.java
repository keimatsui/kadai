package com.amazon.associates.sample;

import java.io.*;
import java.net.*;
import java.util.*;
import java.util.regex.*;
import org.apache.commons.io.*;

/**
 *
 * @author yabuki
 */
public class Sample5 {

  static List<Review> extractReviews(String urlStr, int page) throws MalformedURLException {
    try {
      String charset = "Shift_JIS"; //驚くところ！
      System.out.println(urlStr);

      URL url = new URL(urlStr);
      HttpURLConnection connection;
      connection = (HttpURLConnection) url.openConnection();
      System.err.printf("%s %s\n", connection.getResponseCode(), connection.getResponseMessage());
      String responseBody = IOUtils.toString(connection.getInputStream(), charset);
      Pattern pattern = Pattern.compile("([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)");
      Matcher matcher = pattern.matcher(responseBody.replace("\n", ""));//改行削除
      List<Review> reviews = new LinkedList<>();
      while (matcher.find()) {
        int people = Integer.parseInt(matcher.group(1));
        int helpful = Integer.parseInt(matcher.group(2));
        double star = Double.parseDouble(matcher.group(3));
        reviews.add(new Review(star, people, helpful, page));
      }
      if (responseBody.contains("評価が高い有用性のあるレビュー")) {//最初の2件は重複
        reviews.remove(0);
        reviews.remove(0);
      }

      //次のページがあるか
      pattern = Pattern.compile("<a href=\"([^\\\"]*?)\">次へ");
      matcher = pattern.matcher(responseBody);
      if (matcher.find()) {
        String nextPageUrlStr = matcher.group(1);
        reviews.addAll(extractReviews(nextPageUrlStr, page + 1));
      }
      return reviews;
    } catch (IOException ex) {//エラーが発生したら、5秒Sleepしてやり直す
      System.out.println(ex.getLocalizedMessage());
      try {
        Thread.sleep(5000);
      } catch (InterruptedException e) {
      }
      return extractReviews(urlStr, page);
    }

  }

  public static void main(String[] args) throws Exception {
    String asin = "4274065979";//ハッカーと画家 コンピュータ時代の創造者たち
    //String asin = "B00IFTTOAK";//マリオカート8
    if (args.length != 0) {
      asin = args[0];
    }
    String urlStr = "http://www.amazon.co.jp/product-reviews/" + asin + "/";
    List<Review> reviews = new LinkedList<>();
    reviews.addAll(extractReviews(urlStr, 1));

    double totalStar = 0;//星の総数
    double totalStarWeighted = 0;//星の重み付き総数
    double totalWeight = 0;//重み
    int numReviewsPage1 = 0;//1ページ目のレビュアー数
    double totalStarPage1 = 0;//1ページ目の星の総数
    double totalStarWeightedPage1 = 0;//1ページ目の星の重み付き総数
    double totalWeightPage1 = 0;//1ページ目の重み
    for (Review r : reviews) {
      System.out.println(r);
      double star = r.getStar();
      totalStar += star;
      double weight = 1.0 * r.getHelpful() / r.getPeople();
      totalStarWeighted += star * weight;
      totalWeight += weight;
      if (r.getPage() == 1) {
        ++numReviewsPage1;
        totalStarPage1 += star;
        totalStarWeightedPage1 += star * weight;
        totalWeightPage1 += weight;
      }
    }
    int numReviews = reviews.size();
    System.out.println("レビュアー数：" + numReviews);
    System.out.println("平均スター：" + totalStar / numReviews);
    System.out.println("平均スター（1ページ目）：" + totalStarPage1 / numReviewsPage1);
    System.out.println("平均スター（重み付き）" + totalStarWeighted / totalWeight);
    System.out.println("平均スター（1ページ目・重み付き）" + totalStarWeightedPage1 / totalWeightPage1);

  }
}
