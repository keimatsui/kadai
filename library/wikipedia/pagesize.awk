BEGIN{FS=","}
{
  printf("echo %d,$(wget \"http://ja.wikipedia.org/w/index.php?title=利用者:%s&action=history\" -O - | gawk -f byte.awk | sed 's/,//g' | sed 's/空/0/g' | head -n 1) >> pagesize.csv\n", NR, $2);
}