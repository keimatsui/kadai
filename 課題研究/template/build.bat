del *.bbl
uplatex -shell-escape draft && upbibtex draft  && uplatex -shell-escape draft && uplatex -shell-escape draft && dvipdfmx draft
