#!/bin/bash

delDate=$(date -d "$(date +"%Y%m%d") - 15 days" +"%Y%m%d")

path=/path/to/backup/folder/*/

# dirs=()

for dir in $path;
 do

 base=$(basename "$dir")
 IFS='-' read -ra parts <<< "$base"
 dirDate=${parts[0]}${parts[1]}${parts[2]}

 if [ $dirDate -lt $delDate ]
 then
  rmdir $dir
#  echo "'$dir' removed"
 fi

 #dirs+=("$(basename "$dir")")
 done

#echo ${dirs[@]};
