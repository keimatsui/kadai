package com.amazon.associates.sample;

import lombok.*;

/**
 * レビューを記憶するためのクラス
 *
 * @author yabuki
 */
@Data
@ToString
@AllArgsConstructor
public class Review {

  private double star;
  private int people;
  private int helpful;
  private int page;
}
