package com.amazon.associates.sample;

import java.util.*;
import java.net.*;
import javax.xml.xpath.*;
import org.w3c.dom.*;
import org.xml.sax.*;

/**
 * ASINで指定したアイテムの商品名をAPIで取得する。
 *
 * @author yabuki
 */
public class Sample2 {

  public static void main(String[] args) throws Exception {
    String AssociateTag = "inquisitor-22";
    String asin = "400339481X";
    if (args.length != 0) {
      asin = args[0];
    }
    String Version = "2013-08-01";

    Map<String, String> params = new HashMap<>();
    params.put("AssociateTag", AssociateTag);
    params.put("Operation", "ItemLookup");
    params.put("ItemId", asin);
    params.put("ResponseGroup", "ItemAttributes,Reviews");//Reviewsリンクしか帰ってこないから意味なし
    params.put("Version", Version);
    SignedRequestsHelper instance = new SignedRequestsHelper();
    String apiUrl = instance.sign(params);
    System.out.println("APIのためのURL:\n" + apiUrl);

    XPath xpath = XPathFactory.newInstance().newXPath();
    String expression = "//*[namespace-uri() = 'http://webservices.amazon.com/AWSECommerceService/" + Version + "'][local-name() = 'Title']";

    URL url = new URL(apiUrl);
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
    InputSource is = new InputSource(connection.getInputStream());
    Node node = (Node) xpath.evaluate(expression, is, XPathConstants.NODE);
    String title = node.getTextContent();
    System.out.println("Title:\n" + title);
  }

}
