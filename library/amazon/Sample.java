package com.amazon.associates.sample;

import java.util.*;

public class Sample {

  public static void main(String[] args) throws Exception {
    Amazon api = Amazon.of("awsAccessKeyId", "awsSecretKey", "AssociateTag");
    String url = api.itemLookupUrl("400339481X");
    System.out.println(url);

    String apiResponse = api.get(url, "UTF-8");
    String reviewUrl = api.extractReviewUrl(apiResponse);
    System.out.println(reviewUrl);

    String reviewHtml = api.get(reviewUrl, "Shift_JIS");
//    System.out.println(reviewHtml);

    //ファイルから読む場合
//    String htmlFile = "c:/work/review.html";
//    StringBuilder sb = new StringBuilder();
//    for (String line : Files.readAllLines(Paths.get(htmlFile), "Shift_JIS")) {
//      sb.append(line);
//    }
//    String reviewHtml = sb.toString();
    List<Amazon.Review> reviews = api.extractReviews(reviewHtml);
    int numReviews = reviews.size();
    System.out.println("レビュアー数：" + numReviews);
    double totalStar = 0;//星の総数
    double totalStarWeighted = 0;//星の重み付き総数
    double totalWeight = 0;//重み
    for (Amazon.Review r : reviews) {
      System.out.println(r);
      double star = r.getStar();
      totalStar += star;
      double weight = 1.0 * r.getHelpful() / r.getPeople();
      totalStarWeighted += star * weight;
      totalWeight += weight;
    }
    System.out.println("平均スター：" + totalStar / numReviews);
    System.out.println("平均スター（重み付き）" + totalStarWeighted / totalWeight);
  }

}
