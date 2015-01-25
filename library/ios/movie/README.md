# ビデオ入力動画から動くものを消すアプリ

## 参考資料

* [ビデオカメラアプリの作り方(2) - ビデオ画像の表示](http://news.mynavi.jp/column/iphone/041/)
* [リアルタイム加工カメラアプリの作り方 iOS](http://nyaonyaokun.hatenablog.com/entry/2014/12/28/044344)

## 準備

[ビデオカメラアプリの作り方(2) - ビデオ画像の表示](http://news.mynavi.jp/column/iphone/041/)のサンプルアプリが実機で動くようにする（`VideoCamera-1.zip`）。（そのまま動かそうとするとXcodeのバージョンの違い等でエラーになるが、ダイアログに応えると自動的に修正される。）

## 実装

`VideoCameraViewController.h`の`IBOutlet UIImageView*   _imageView;`のあとに以下を追記する。

```
    NSInteger _frames;//起動してからのフレーム数
    float* _total;//RGB値の累積を記録する（これをフレーム数で割れば平均値が求まる）
```
`VideoCameraViewController.m`の`[_session startRunning];`のあとに以下を追記する。

```
_frames = -10;//最初の数フレームは無視する
```

`VideoCameraViewController.m`の`CGColorSpaceRelease(colorSpace);`のあとに以下を追記する。

```
    if (_frames == 1) {
        _total = (float*)malloc(sizeof(float) * height * width * 3);
    }
    for (int j = 0; j < height; j++) {
        for (int i = 0; i < width; i++) {
            UInt8 *tmp3 = base + j * bytesPerRow + i * 4;
            UInt8 r = *(tmp3 + 2);
            UInt8 g = *(tmp3 + 1);
            UInt8 b = *(tmp3);
            
            unsigned long p = ((j * width) + i) * 3;
            if (_frames > 0) {
                if (_frames == 1) {
                    _total[p] = r;
                    _total[p + 1] = g;
                    _total[p + 2] = b;
                } else {
                    _total[p] += r;
                    _total[p + 1] += g;
                    _total[p + 2] += b;
                }
                *(tmp3 + 2) = _total[p]/_frames;
                *(tmp3 + 1) = _total[p + 1]/_frames;
                *(tmp3) = _total[p + 2]/_frames;
            }
        }
    }
    _frames++;
```