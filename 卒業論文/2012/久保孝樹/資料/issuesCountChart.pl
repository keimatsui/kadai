set datafile separator ","
set xdata time
set timefmt "%Y-%m-%d %H:%M:%S"
set xl "date time"
set yl "number of issues"
set terminal png size 1680,1050
set border 15 lw 2
set tics font "Times New Roman,15"
set out "issuesCountChart.png"
plot 'issues.csv' using 1:2 with lines lw 2 title 'open issues', 'issues.csv' u 1:3 with lines lw 2 title 'closed issues'


