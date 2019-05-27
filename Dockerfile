FROM centos:7

RUN yum install httpd
RUN systemctl start httpd.service


CMD ["/bin/bash"]
