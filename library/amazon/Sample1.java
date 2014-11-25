package com.amazon.associates.sample;

import java.util.*;

/**
 * 特定のアイテムについての情報をASINを指定して取得するためのURLを作る。
 *
 * @author yabuki
 */
public class Sample1 {

  public static void main(String[] args) throws Exception {
    String AssociateTag = "inquisitor-22";
    String asin = "400339481X";
    String Version = "2013-08-01";

    Map<String, String> params = new HashMap<>();
    params.put("AssociateTag", AssociateTag);
    params.put("Operation", "ItemLookup");
    params.put("ItemId", asin);
    params.put("ResponseGroup", "ItemAttributes,Reviews");//Reviewsリンクしか帰ってこないから意味なし
    params.put("Version", Version);
    SignedRequestsHelper instance = new SignedRequestsHelper();
    String apiUrl = instance.sign(params);
    System.out.println("APIのためのURL：\n" + apiUrl);
  }
}
