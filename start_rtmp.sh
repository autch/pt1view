#!/bin/sh

# CH
# RTMP_URL
# PRESET

start_default() {
  /usr/local/bin/recpt1 --b25 --strip "$CH" - - \
    | ffmpeg -v 0 \
       -i /dev/stdin \
       -vcodec libx264 -vprofile baseline -level 3.0 -preset fast -vb 2400k \
       -vf yadif=0:-1,scale=iw/2:-1 \
       -acodec libfaac -ab 128k \
       -threads 8 -movflags +faststart \
       -f flv "$RTMP_URL"
}

start_1seg() {
  /usr/local/bin/recpt1 --b25 --strip "$CH" - - \
    | ffmpeg -v 0 \
       -i /dev/stdin \
       -r 15 -sws_flags lanczos -vf yadif,scale=320:-1,hqdn3d \
       -c:v libx264 -vprofile baseline -level 1.2 -preset fast -vb 320k \
       -acodec libfaac -ab 96k \
       -threads 8 -movflags +faststart \
       -f flv "$RTMP_URL"
}

case "$PRESET" in
  "1seg")
    start_1seg
    ;;
  *)
    start_default
    ;;
esac
