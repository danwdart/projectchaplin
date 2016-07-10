#!/bin/bash
case $1 in
    start)
        cd cli
        php cli.php cli youtube > logs/youtube.log & 2>&1
        echo $! > ../youtube.pid
        php cli.php cli convert > logs/convert.log & 2>&1
        echo $! > ../convert.pid
        cd ..
        node node/server.js > logs/node.log & 2>&1
        echo $! > node.pid
        ;;
    stop)
        kill $(cat youtube.pid) && rm youtube.pid
        kill $(cat convert.pid) && rm convert.pid
        kill $(cat node.pid) && rm node.pid
    ;;
    status-youtube)
        if [[ "2" == "$(ps aux | grep $(cat youtube.pid) | wc -l)" ]]
        then
            echo YouTube listener running.
            exit 0
        else
            echo YouTube listener not running.
            exit 1
        fi
    ;;
    status-convert)
        if [[ "2" == "$(ps aux | grep $(cat convert.pid) | wc -l)" ]]
        then
            echo Convert listener running.
            exit 0
        else
            echo Convert listener not running.
            exit 1
        fi
    ;;
    status-node)
        if [[ "2" -eq "$(ps aux | grep $(cat node.pid) | wc -l)" ]]
        then
            echo Node listener running.
            exit 0
        else
            echo Node listener not running.
            exit 1
        fi
    ;;
    restart)
        $0 stop
        $0 start
    ;;
    log)
        tail -f logs/youtube.log logs/convert.log logs/node.log
    ;;
    *)
        echo "Usage: $0 start|stop|restart|log"
    ;;
esac
