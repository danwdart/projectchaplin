#!/bin/bash
cd $(dirname $0)
CPID=/tmp/convert.pid
YPID=/tmp/youtube.pid
NPID=/tmp/node.pid
case $1 in
    start)
        php cli/cli.php cli youtube > logs/youtube.log 2>&1 &
        echo $! > $YPID
        php cli/cli.php cli convert > logs/convert.log 2>&1 &
        echo $! > $CPID
        node node/server.js > logs/node.log 2>&1 &
        echo $! > $NPID
        echo All services started
        ;;
    stop)
        kill $(cat $YPID) && rm $YPID
        kill $(cat $CPID) && rm $CPID
        kill $(cat $NPID) && rm $NPID
        echo All services stopped
    ;;
    status)
        if [[ "2" == "$(ps aux | grep $(cat $YPID) | wc -l)"]] &&
            [["2" == "$(ps aux | grep $(cat $CPID) | wc -l)"]] &&
            [["2" -eq "$(ps aux | grep $(cat $NPID) | wc -l)" ]]
        then
            echo All listeners running.
            exit 0
        else
            echo Some or all listeners not running.
            exit 1
        fi
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
        echo "Usage: $0 start|stop|restart|status|status-youtube|status-convert|status-node|log"
    ;;
esac
