BEGIN{s=0;s2=0} {s+=$1;s2+=$1*$1;} END{m=s/NR;printf("%f,%f\n",m,sqrt(s2/NR-m*m))}