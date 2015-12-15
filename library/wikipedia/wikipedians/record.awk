BEGIN{FS=","}
{
  printf("echo %d,$(wget \"http://ja.wikipedia.org/w/index.php?title=特別:投稿記録/%s&limit=500\" -O - | gawk -f diff.awk | gawk -f stat.awk) >> record.csv\n", NR, $2);
}