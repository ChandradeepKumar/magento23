FROM centos:7

RUN yum install httpd
RUN systemctl start httpd.service
RUN yum install mariadb-server mariadb
RUN systemctl start mariadb
RUN yum install php

CMD ["/bin/bash"]
