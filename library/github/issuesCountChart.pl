set datafile separator ","
set xdata time
set timefmt "%Y-%m-%d %H:%M:%S"
set xl "date time"
set yl "number of issues"
set terminal png
set out "issuesCountChart.png"
plot 'issues.csv' using 1:2 with lines lw 3 title 'open issues', 'issues.csv' u 1:3 with lines lw 3 title 'closed issues'
