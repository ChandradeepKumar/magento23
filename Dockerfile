FROM centos:7

RUN mkdir /demo1

expose 8080
CMD ["/bin/bash"]
