#!/bin/bash
SCRIPT_DIR=$(dirname "$(realpath $0)")
TGDB_File="$SCRIPT_DIR/"tgdb.php
TGDB_File_new="$SCRIPT_DIR/"tgdb.txt

cd "$SCRIPT_DIR/"

rm $TGDB_File

wget https://xlx169.26269.de/ysf/download/tgdb.txt
cp $TGDB_File_new $TGDB_File

sudo chown svxlink:svxlink $TGDB_File_new $TGDB_File
sudo chmod 755 $TGDB_File_new $TGDB_File

rm $TGDB_File_new
