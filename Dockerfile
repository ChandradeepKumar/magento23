FROM centos:7

RUN yum install zip

expose 8080
CMD ["/bin/bash"]
