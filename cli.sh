#!/bin/bash
case $1 in
    start)
        php cli/cli.php cli youtube > youtube.log & 2>&1
        php cli/cli.php cli convert > convert.log & 2>&2
        $0 log
        ;;
    stop)
        ps aux | grep cli.php | awk '{print $2}' | xargs kill
    ;;
    restart)
        $0 start
        $0 stop
    ;;
    log)
        tail -f youtube.log convert.log
    ;;
    *)
        echo "Usage: $0 start|stop|restart|log"
    ;;
esac
