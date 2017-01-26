del *.bbl

uplatex -synctex=1 -shell-escape draft && upbibtex draft  && uplatex -synctex=1 -shell-escape draft && uplatex -synctex=1 -shell-escape draft && dvipdfmx draft
