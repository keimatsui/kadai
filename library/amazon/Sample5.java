package com.amazon.associates.sample;

import java.io.*;
import java.net.*;
import java.util.*;
import java.util.regex.*;
import org.apache.commons.io.*;

/**
 * ASINで指定したアイテムのレビューを取得・記憶し、重み付き評価値を求める。（複数ページ対応）
 * 
 * @author yabuki
 */
public class Sample5 {

  static List<Review> extractReviews(String urlStr, int page) throws MalformedURLException {
    try {
      String charset = "Shift_JIS"; //驚くところ！
      System.err.println(urlStr);

      URL url = new URL(urlStr);
      HttpURLConnection connection;
      connection = (HttpURLConnection) url.openConnection();
      System.err.printf("%s %s\n", connection.getResponseCode(), connection.getResponseMessage());
      String responseBody = IOUtils.toString(connection.getInputStream(), charset).replace("\n", "");//改行削除

      Pattern pattern1 = Pattern.compile("([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)");
      Pattern pattern2 = Pattern.compile("5つ星のうち\\s*([0-9|\\.]*)");

      List<Review> reviews = new LinkedList<>();
      for (String aReview : responseBody.split("<!-- BOUNDARY -->")) {
        Matcher matcher1 = pattern1.matcher(aReview);
        int people = 0;
        int helpful = 0;
        double star = 0;
        if (matcher1.find()) {
          people = Integer.parseInt(matcher1.group(1));
          helpful = Integer.parseInt(matcher1.group(2));
          star = Double.parseDouble(matcher1.group(3));
          reviews.add(new Review(star, people, helpful, page));
        } else {
          Matcher matcher2 = pattern2.matcher(aReview);
          if (matcher2.find()) {
            star = Double.parseDouble(matcher2.group(1));
            reviews.add(new Review(star, people, helpful, page));
          }
        }
      }
      if (page == 1) {
        System.out.println("平均スター（ウェブ上）：" + reviews.get(0));//１件目は平均
      }
      reviews.remove(0);
      if (responseBody.contains("評価が高い有用性のあるレビュー")) {//最初の2件は重複
        reviews.remove(0);
        reviews.remove(0);
      }

      //次のページがあるか
      Pattern pattern3 = Pattern.compile("<a href=\"([^\\\"]*?)\">次へ");
      Matcher matcher3 = pattern3.matcher(responseBody);
      if (matcher3.find()) {
        String nextPageUrlStr = matcher3.group(1);
        reviews.addAll(extractReviews(nextPageUrlStr, page + 1));
      }
      return reviews;
    } catch (IOException ex) {//エラーが発生したら、5秒Sleepしてやり直す
      System.err.println(ex.getLocalizedMessage());
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
      int page = r.getPage();
      if (page == 1) {
        ++numReviewsPage1;
        totalStarPage1 += star;
      }
      int people = r.getPeople();
      if (people != 0) {
        double weight = 1.0 * r.getHelpful() / people;
        totalStarWeighted += star * weight;
        totalWeight += weight;
        if (page == 1) {
          totalStarWeightedPage1 += star * weight;
          totalWeightPage1 += weight;

        }
      }
    }
    int numReviews = reviews.size();
    System.out.println("レビュアー数：" + numReviews);
    System.out.println("平均スター（計算結果）：" + totalStar / numReviews);
    System.out.println("平均スター（1ページ目）：" + totalStarPage1 / numReviewsPage1);
    System.out.println("平均スター（重み付き）" + totalStarWeighted / totalWeight);
    System.out.println("平均スター（1ページ目・重み付き）" + totalStarWeightedPage1 / totalWeightPage1);

  }
}
