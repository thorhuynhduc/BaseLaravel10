#!/bin/sh
shutdown() {
  echo "shutting down container"

  # first shutdown any service started by runit
  for _srv in $(ls -1 /etc/service); do
    sv force-stop ${_srv}
  done

  # shutdown runsvdir command
  kill -HUP ${PID}
  wait ${PID}

  # give processes time to stop
  sleep 0.5

  # kill any other processes still running in the container
  for _pid  in $(ps -eo pid | grep -v PID  | tr -d ' ' | grep -v '^1$' | head -n -6); do
    timeout -t 5 /bin/sh -c "kill $_pid && wait $_pid || kill -9 $_pid"
  done
  exit
}

# set aws credential env
AWS_URI=$(strings /proc/1/environ | grep AWS_CONTAINER_CREDENTIALS_RELATIVE_URI)
echo -e "\n${AWS_URI/AWS_CONTAINER_CREDENTIALS_RELATIVE_URI/env[AWS_CONTAINER_CREDENTIALS_RELATIVE_URI]}" >> /etc/php81/php-fpm.d/www.conf
echo -e "\n${AWS_URI/AWS_CONTAINER_CREDENTIALS_RELATIVE_URI/env[AWS_CONTAINER_CREDENTIALS_RELATIVE_URI]}" >> /etc/php81/conf.d/custom.ini

AWS_REGION=$(strings /proc/1/environ | grep AWS_REGION)
echo -e "\n${AWS_REGION/AWS_REGION/env[AWS_REGION]}" >> /etc/php81/php-fpm.d/www.conf
echo -e "\n${AWS_REGION/AWS_REGION/env[AWS_REGION]}" >> /etc/php81/conf.d/custom.ini

AWS_DEFAULT_REGION=$(strings /proc/1/environ | grep AWS_DEFAULT_REGION)
echo -e "\n${AWS_DEFAULT_REGION/AWS_DEFAULT_REGION/env[AWS_DEFAULT_REGION]}" >> /etc/php81/php-fpm.d/www.conf
echo -e "\n${AWS_DEFAULT_REGION/AWS_DEFAULT_REGION/env[AWS_DEFAULT_REGION]}" >> /etc/php81/conf.d/custom.ini

AWS_EXECUTION_ENV=$(strings /proc/1/environ | grep AWS_EXECUTION_ENV)
echo -e "\n${AWS_EXECUTION_ENV/AWS_EXECUTION_ENV/env[AWS_EXECUTION_ENV]}" >> /etc/php81/php-fpm.d/www.conf
echo -e "\n${AWS_EXECUTION_ENV/AWS_EXECUTION_ENV/env[AWS_EXECUTION_ENV]}" >> /etc/php81/conf.d/custom.ini

rm -rf /etc/service/nginx
rm -rf /etc/service/php

exec env - PATH=$PATH runsvdir -P /etc/service &

PID=$!
echo "Started runsvdir, PID is $PID"
echo "wait for processes to start...."

sleep 5
for _srv in $(ls -1 /etc/service); do
    sv status ${_srv}
done

# catch shutdown signals
trap shutdown SIGTERM SIGHUP SIGQUIT SIGINT
wait ${PID}

shutdown