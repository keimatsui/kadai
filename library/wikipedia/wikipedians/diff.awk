/^<li/{print gensub(/.*\((([+-]([0-9]|,)+)|0)\).*/, "\\1", $0)}