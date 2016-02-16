set datafile separator ","
set xdata time
set timefmt "%Y/%m/%d"
set format x "%Y/%m"
set xl "month"
set yl "price"
set terminal png
set out "stock.png"
plot 'stock.csv' using 1:2:3:4:5 with candlesticks linetype -1 title ""
