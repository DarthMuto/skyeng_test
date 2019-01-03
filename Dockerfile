FROM ubuntu

RUN echo '2019-01-02' # intermediate containers version
RUN apt-get update
RUN apt-get install -y software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update

RUN echo "Europe/Moscow" > /etc/timezone
RUN apt-get install -y nginx php7.2-fpm

RUN apt-get install -y openssh-server vim

RUN echo 'root:skyeng' | chpasswd

RUN rm -rf /var/www
ADD www /var/www
ADD docker_exec.sh /
ADD etc /etc
ADD root /root

EXPOSE 80 22

CMD ["/docker_exec.sh"]

