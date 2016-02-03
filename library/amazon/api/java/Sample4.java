package com.amazon.associates.sample;

import java.net.*;
import java.util.*;
import java.util.regex.*;
import org.apache.commons.io.*;

/**
 * ASINで指定したアイテムのレビューを取得・記憶し、重み付き評価値を求める。（1ページ限定）
 * 
 * @author yabuki
 */
public class Sample4 {

  public static void main(String[] args) throws Exception {
    String asin = "400339481X";
    if (args.length != 0) {
      asin = args[0];
    }
    String charset = "Shift_JIS"; //驚くところ！
    String urlStr = "http://www.amazon.co.jp/product-reviews/" + asin + "/";
    //String urlStr = "http://www.amazon.co.jp/product-reviews/4873115655?pageNumber=2";//テスト用
    URL url = new URL(urlStr);
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
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
        reviews.add(new Review(star, people, helpful, 1));
      } else {
        Matcher matcher2 = pattern2.matcher(aReview);
        if (matcher2.find()) {
          star = Double.parseDouble(matcher2.group(1));
          reviews.add(new Review(star, people, helpful, 1));
        }
      }
    }
    System.out.println("平均スター（ウェブ上）：" + reviews.get(0));//１件目は平均
    reviews.remove(0);
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
      int people = r.getPeople();
      if (people != 0) {//評価のあるレビューのみ
        double weight = 1.0 * r.getHelpful() / people;
        totalStarWeighted += star * weight;
        totalWeight += weight;
      }
    }
    int numReviews = reviews.size();
    System.out.println("レビュアー数：" + numReviews);
    System.out.println("平均スター（計算結果）：" + totalStar / numReviews);
    System.out.println("平均スター（重み付き）" + totalStarWeighted / totalWeight);
  }
}
