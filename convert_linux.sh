#!/bin/sh
cd "$1"
/Applications/LibreOffice.app/Contents/MacOS/soffice --headless --convert-to pdf "$2"