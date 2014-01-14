setwd("c:/cit")
myData <- read.csv("sample.csv")
head(myData)

#主成分分析（全体を括弧で囲むと結果が表示される）
myResult <-prcomp(myData[,-1]) #1列目は不要

#結果の概要
summary(myResult)

#主成分（固有ベクトル）
myResult$rotation

#主成分の図示
barplot(sort(myResult$rotation[,1]))
barplot(sort(myResult$rotation[,2]))

#主成分スコア
myResult$x

#主成分スコアの図示
biplot(myResult)
