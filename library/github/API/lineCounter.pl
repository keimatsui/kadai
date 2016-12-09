set datafile separator ","
set xdata time
set timefmt "%Y-%m-%d %H:%M:%S"
set xl "date time"
set yl "lines"
set terminal png
set out "lines.png"
plot 'count-result.csv' using 2:3 with lines lw 3 title 'total', 'count-result.csv' using 2:4 with lines lw 3 title 'test'
