#タイトル,1 ,2 ,3 ,4 ,5 
#ケリ姫スイーツ,8703 ,7654 ,40346 ,120019 ,396464 
#LINE ポコパン,12549 ,14028 ,68635 ,119597 ,198020 
#というようなCSVファイルを作ってRで読み込む
setwd("c:/cit")
myData <- read.csv("Book1.csv")
head(myData)

#分析の前に、件数を割合に直す
myData$sum <- rowSums(myData[,-1])
myData2 <- myData[,c(2,3,4,5,6)]/myData$sum

#主成分分析を実行する
myResult <-prcomp(myData2)

#主成分を表示する
barplot(sort(myResult$rotation[,1])) #第1主成分
barplot(sort(myResult$rotation[,2])) #第2主成分

#主成分スコアの確認
myResult$x

#主成分スコアの図示
biplot(myResult)

#平均評価点（星数）を計算する
myData2$avg <- myData2[,1] * 1 + myData2[,2] * 2 + myData2[,3] * 3 + myData2[,4] * 4  + myData2[,5] * 5 

#平均評価点と主成分スコアの相関を見る
#第1主成分はよく相関している（これはあたりまえであまり面白くない）
plot(myData2$avg, myResult$x[,1])

#第2主成分はよく相関していない。つまり、平均評価点を見てもらないことがここからわかるはず。（第2主成分の解釈が大事）
plot(myData2$avg, myResult$x[,2])
