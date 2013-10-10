#!/bin/sh
cd "$1"
/usr/bin/libreoffice --headless --convert-to pdf "$2"