#この方法の欠点：メンバの中に同じ性格の人が複数いる場合については調べられない。

setwd('c:/cit')
install.packages('arules')
library(arules)

data <- read.transactions('メンバの性格と発生リスク.csv', sep = ',')
summary(data)

result <- apriori(data, parameter = list(support = 0.1, confidence = 0.5, minlen = 3, maxlen = 5))
result
inspect(result[1:3])

clabels <- c("ENFJ","ENFP","ENTJ","ENTP","ESFJ","ESFP","ESTJ","ESTP","INFJ","INFP","INTJ","INTP","ISFJ","ISFP","ISTJ","ISTP")
rlabels <- as.character(1:36)
rlabels <- rlabels[rlabels != '11']

rules <- subset(result, !(lhs %in% rlabels) & !(rhs %in% clabels))
rules
inspect(sort(rules, by = 'confidence'))
