#!/bin/sh
cd "$1"
gs -dNOPAUSE -dBATCH -dSAFER -sDEVICE=pdfwrite -sPAPERSIZE=a4 -dPDFSETTINGS=/ebook -dCompatibilityLevel=1.4 -sOutputFile="$3" "$2"