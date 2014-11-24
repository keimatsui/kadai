package com.amazon.associates.sample;

import java.io.*;
import java.net.*;
//import java.nio.charset.*;
//import java.nio.file.*;
import java.security.*;
import java.util.*;
import java.util.regex.*;
import javax.xml.xpath.*;
import lombok.*;
import org.apache.commons.io.*;
import org.w3c.dom.*;
import org.xml.sax.*;

@RequiredArgsConstructor(staticName = "of")
public class Amazon {

  @NonNull
  private String awsAccessKeyId;
  @NonNull
  private String awsSecretKey;
  @NonNull
  private String AssociateTag;

  @Data
  @AllArgsConstructor
  public class Review {

    private double star;
    private int people;
    private int helpful;
  }

  public String itemLookupUrl(String asin) throws UnsupportedEncodingException, NoSuchAlgorithmException, InvalidKeyException {
    Map<String, String> params = new HashMap<>();
    params.put("AssociateTag", AssociateTag);
    params.put("Operation", "ItemLookup");
    params.put("ItemId", asin);
    params.put("ResponseGroup", "ItemAttributes");//Reviewsでもリンクしか帰ってこないから同じこと
    params.put("Version", "2013-08-01");
    SignedRequestsHelper instance = new SignedRequestsHelper("ecs.amazonaws.jp", awsAccessKeyId, awsSecretKey);
    String url = instance.sign(params);
    return url;
  }

  public String get(String urlStr, String charset) throws MalformedURLException, IOException {
    URL url = new URL(urlStr);
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
    System.err.printf("%s %s\n", connection.getResponseCode(), connection.getResponseMessage());
    String responseBody = IOUtils.toString(connection.getInputStream(), charset);
    return responseBody;
  }

  public String extractReviewUrl(String xmlStr) throws XPathExpressionException {
    XPath xpath = XPathFactory.newInstance().newXPath();
    String version = "2013-08-01";
    String expression = "//*[namespace-uri() = 'http://webservices.amazon.com/AWSECommerceService/" + version + "'][local-name() = 'URL']";
    NodeList nodes = (NodeList) xpath.evaluate(expression, new InputSource(new StringReader(xmlStr)), XPathConstants.NODESET);

    String reviewUrl = null;
    for (int i = 0; i < nodes.getLength(); ++i) {
      Node linkNode = nodes.item(i);
      String tmp = linkNode.getTextContent();
      if (tmp.contains("review/product")) {
        reviewUrl = tmp;
      }
    }
    return reviewUrl;
  }

  public List<Review> extractReviews(String htmlStr) {
    List<Review> reviews = new LinkedList<>();

    Pattern pattern = Pattern.compile("([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)");
    Matcher matcher = pattern.matcher(htmlStr.replace("\n", ""));
    while (matcher.find()) {
      int people = Integer.parseInt(matcher.group(1));
      int helpful = Integer.parseInt(matcher.group(2));
      double star = Float.parseFloat(matcher.group(3));
      reviews.add(new Review(star, people, helpful));
    }
    if (htmlStr.contains("評価が高い有用性のあるレビュー")) {//最初の2件は重複
      reviews.remove(0);
      reviews.remove(0);
    }
    return reviews;
  }

}
