rm -f stock.csv
cp $1.csv stock.csv

gnuplot history.pl

cp stock.png $1.png
rm -f stock.png

